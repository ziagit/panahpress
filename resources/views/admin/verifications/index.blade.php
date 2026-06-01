@extends('layouts.admin')

@section('content')
    <div class="panel-card">
        <div class="panel-header">
            <div>
                <span class="badge">{{ __('messages.admin_panel') }}</span>
                <h1>{{ __('messages.verifications') }}</h1>
                <p class="text-muted">{{ __('messages.welcome_admin') }}</p>
            </div>
            <div class="panel-actions">
                <a href="{{ route('admin.verifications.create', ['locale' => $locale]) }}" class="button">{{ __('messages.create_verification_card') }}</a>
            </div>
        </div>

        <form method="GET" action="{{ route('admin.verifications.index', ['locale' => $locale]) }}" style="display:flex; flex-wrap:wrap; gap:0.75rem; align-items:center; margin-bottom:1rem;">
            <div class="form-field" style="flex:1 1 240px; margin-bottom:0;">
                <label for="search">{{ __('messages.search') }}</label>
                <input id="search" type="search" name="search" value="{{ $search }}" placeholder="{{ __('messages.search') }}" />
            </div>
            <button type="submit" class="button">{{ __('messages.search') }}</button>
            @if($search !== '')
                <a href="{{ route('admin.verifications.index', ['locale' => $locale]) }}" class="text-muted">Clear</a>
            @endif
        </form>

        <div class="table-wrap">
            <table class="table">
                <thead>
                    <tr>
                        <th>{{ __('messages.card_id') }}</th>
                            <th>{{ __('messages.full_name') }}</th>
                            <th>{{ __('messages.occupation') }}</th>
                            <th>{{ __('messages.birth_date') }}</th>
                            <th>{{ __('messages.issue_date') }}</th>
                            <th>{{ __('messages.expiry_date') }}</th>
                            <th>{{ __('messages.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($cards as $card)
                            <tr>
                                <td>{{ $card->code }}</td>
                                <td>
                                    <div style="display:flex; gap:0.75rem; align-items:center;">
                                        @if($card->photo)
                                            <img src="{{ asset('storage/'.$card->photo) }}" alt="{{ $card->full_name }}" style="width:42px;height:42px;object-fit:cover;border-radius:999px;">
                                        @endif
                                        <strong>{{ $card->full_name }}</strong>
                                    </div>
                                </td>
                                <td>{{ $card->occupation }}</td>
                                <td>{{ $card->birth_date?->format('Y-m-d') }}</td>
                                <td>{{ $card->issue_date?->format('Y-m-d') }}</td>
                                <td>{{ $card->expiry_date?->format('Y-m-d') }}</td>
                                <td>
                                    <a href="{{ route('verify.show', ['locale' => $locale, 'verificationCard' => $card]) }}">{{ __('messages.view_more') }}</a>
                                    <a href="{{ route('admin.verifications.edit', ['locale' => $locale, 'verification' => $card]) }}" style="margin-left:0.75rem;">{{ __('messages.edit') }}</a>
                                    <form action="{{ route('admin.verifications.destroy', ['locale' => $locale, 'verification' => $card]) }}" method="POST" style="display:inline; margin-left:0.75rem;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" style="background:none;border:0;padding:0;color:#b91c1c;cursor:pointer;">{{ __('messages.delete') }}</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" style="text-align:center; color:#64748b; padding:2rem;">{{ __('messages.no_posts') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div style="margin-top:1rem;">
                {{ $cards->links() }}
            </div>
        </div>
    </div>
@endsection
