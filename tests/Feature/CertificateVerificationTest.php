<?php

namespace Tests\Feature;

use App\Models\Certificate;
use App\Models\User;
use App\Models\VerificationCard;
use App\Services\WeatherService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class CertificateVerificationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('local');
        $this->mock(WeatherService::class, fn ($mock) => $mock->shouldReceive('current')->andReturn([]));
    }

    private function certificate(array $attributes = []): Certificate
    {
        return Certificate::create(array_merge([
            'number' => 'PP-CERT-2026-001', 'recipient_name' => 'Sample Recipient',
            'title' => 'Certificate of Appreciation', 'issuer' => 'Panah Press',
            'image_path' => 'certificates/example.png', 'issue_date' => today()->subDay()->toDateString(),
        ], $attributes));
    }

    public function test_public_lookup_and_scan_link_show_matching_certificate_in_both_languages(): void
    {
        $certificate = $this->certificate();
        foreach (['en', 'fa'] as $locale) {
            $url = route('certificates.show', ['locale' => $locale, 'token' => $certificate->verification_token]);
            $this->get(route('certificates.verify', ['locale' => $locale]))->assertOk();
            $this->get(route('certificates.verify', ['locale' => $locale, 'number' => strtolower($certificate->number), 'verification_code' => $certificate->verification_token]))->assertRedirect($url);
            $this->get($url)->assertOk()->assertSee('Sample Recipient')->assertSee('certificate-body')->assertSee('certificate-picture')->assertHeader('X-Robots-Tag', 'noindex, nofollow');
        }
        $this->get(route('certificates.verify', ['locale' => 'en', 'number' => $certificate->number, 'verification_code' => str_repeat('x', 48)]))->assertSessionHasErrors('number');
        $this->get('/en/verify/certificates/'.str_repeat('x', 48))->assertNotFound();
    }

    public function test_status_handles_revocation_expiry_and_future_issue_dates(): void
    {
        $certificate = $this->certificate(['expiry_date' => today()]);
        $url = route('certificates.show', ['locale' => 'en', 'token' => $certificate->verification_token]);
        $this->get($url)->assertSee('Certificate Verified');
        $certificate->update(['expiry_date' => today()->subDay()]);
        $this->get($url)->assertSee('Certificate Expired')->assertDontSee('Certificate Verified');
        $certificate->update(['is_revoked' => true]);
        $this->get($url)->assertSee('Certificate Revoked')->assertDontSee('Certificate Verified');
        $certificate->update(['is_revoked' => false, 'issue_date' => today()->addDay(), 'expiry_date' => null]);
        $this->get($url)->assertSee('Certificate Not Yet Valid');
    }

    public function test_only_admin_can_manage_certificates(): void
    {
        $this->get('/en/admin/certificates')->assertRedirect();
        $this->actingAs(User::factory()->create(['role' => 'author']))->get('/en/admin/certificates')->assertForbidden();
        $this->post('/en/admin/certificates', [])->assertForbidden();
        $this->actingAs(User::factory()->create(['role' => 'admin']))->get('/en/admin/certificates')->assertOk();
        $this->get('/en/admin/certificates/create')->assertOk();
    }

    public function test_admin_can_upload_replace_and_revoke_with_stable_verification_link(): void
    {
        $this->actingAs(User::factory()->create(['role' => 'admin']));
        $data = ['number' => 'pp-cert-2026-002', 'recipient_name' => 'Test Person', 'title' => 'Appreciation', 'issuer' => 'Panah Press', 'issue_date' => today()->toDateString()];
        $this->post('/en/admin/certificates', [...$data, 'image' => UploadedFile::fake()->image('certificate.png')])->assertRedirect('/en/admin/certificates');
        $certificate = Certificate::firstOrFail();
        $token = $certificate->verification_token;
        $path = $certificate->image_path;
        Storage::disk('local')->assertExists($path);
        $this->get('/en/admin/certificates/'.$certificate->id.'/edit')->assertOk()->assertSee($token);
        $this->get('/en/admin/certificates/'.$certificate->id.'/qr')->assertOk()->assertHeader('Content-Type', 'image/svg+xml');
        $this->put('/en/admin/certificates/'.$certificate->id, [...$data, 'is_revoked' => 1, 'image' => UploadedFile::fake()->image('replacement.png')])->assertRedirect('/en/admin/certificates');
        $certificate->refresh();
        $this->assertTrue($certificate->is_revoked);
        $this->assertSame($token, $certificate->verification_token);
        Storage::disk('local')->assertMissing($path);
        Storage::disk('local')->assertExists($certificate->image_path);
        $this->get(route('certificates.image', ['locale' => 'en', 'token' => $token]))->assertOk();
        $this->post('/en/admin/certificates', [...$data, 'image' => UploadedFile::fake()->image('duplicate.png')])->assertSessionHasErrors('number');
        $this->put('/en/admin/certificates/'.$certificate->id, [...$data, 'expiry_date' => today()->subDay()->toDateString()])->assertSessionHasErrors('expiry_date');
        $this->post('/en/admin/certificates', [...$data, 'number' => 'OTHER', 'image' => UploadedFile::fake()->create('script.svg', 1, 'image/svg+xml')])->assertSessionHasErrors('image');
    }

    public function test_existing_card_verification_still_works(): void
    {
        $card = VerificationCard::create(['code' => 'P001', 'security_code' => '123456', 'full_name' => 'Press Person', 'occupation' => 'Reporter', 'birth_date' => '1990-01-01', 'issue_date' => today(), 'expiry_date' => today()->addYear()]);
        $this->get('/en/verify')->assertOk()->assertSee('Verify Certificate');
        $this->get('/en/verify?code=P001&security_code=123456')->assertRedirect(route('verify.show', ['locale' => 'en', 'verificationCard' => $card, 'securityCode' => '123456']));
        $this->get('/en/verify/P001?securityCode=123456')->assertOk()->assertSee('Press Person');
    }
}
