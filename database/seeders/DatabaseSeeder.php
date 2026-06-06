<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $menuTree = [
            [
                'en' => 'Afghanistan',
                'fa' => 'افغانستان',
                'slug' => 'afghanistan',
                'sort_order' => 1,
                'children' => [
                    ['en' => 'Human Rights', 'fa' => 'حقوق بشر', 'slug' => 'human-rights', 'sort_order' => 1],
                    ['en' => 'Security', 'fa' => 'امنیت', 'slug' => 'security', 'sort_order' => 2],
                    ['en' => 'Immigration', 'fa' => 'مهاجرت', 'slug' => 'immigration', 'sort_order' => 3],
                    ['en' => 'Media', 'fa' => 'رسانه', 'slug' => 'media', 'sort_order' => 4],
                    ['en' => 'Politics', 'fa' => 'سیاست', 'slug' => 'politics', 'sort_order' => 5],
                    ['en' => 'Taliban Internal Rift', 'fa' => 'شکاف داخلی طالبان', 'slug' => 'taliban-internal-rift', 'sort_order' => 6],
                    ['en' => 'Women', 'fa' => 'زنان', 'slug' => 'women', 'sort_order' => 7],
                    ['en' => 'Human Rights Violations by Taliban', 'fa' => 'نقض حقوق بشر توسط طالبان', 'slug' => 'human-rights-violations-by-taliban', 'sort_order' => 8],
                ],
            ],
            [
                'en' => 'World',
                'fa' => 'جهان',
                'slug' => 'world',
                'sort_order' => 2,
                'children' => [
                    ['en' => 'US', 'fa' => 'آمریکا', 'slug' => 'us', 'sort_order' => 1],
                    ['en' => 'Africa', 'fa' => 'آفریقا', 'slug' => 'africa', 'sort_order' => 2],
                    ['en' => 'Asia', 'fa' => 'آسیا', 'slug' => 'asia', 'sort_order' => 3],
                    ['en' => 'Europe', 'fa' => 'اروپا', 'slug' => 'europe', 'sort_order' => 4],
                    ['en' => 'Middle East', 'fa' => 'خاورمیانه', 'slug' => 'middle-east', 'sort_order' => 5],
                    ['en' => 'Amu Region', 'fa' => 'منطقه آمو', 'slug' => 'amu-region', 'sort_order' => 6],
                    ['en' => 'South Asia', 'fa' => 'جنوب آسیا', 'slug' => 'south-asia', 'sort_order' => 7],
                    ['en' => 'West Asia', 'fa' => 'غرب آسیا', 'slug' => 'west-asia', 'sort_order' => 8],
                ],
            ],
            [
                'en' => 'Business',
                'fa' => 'اقتصاد',
                'slug' => 'business',
                'sort_order' => 3,
                'children' => [
                    ['en' => 'Economy', 'fa' => 'اقتصاد', 'slug' => 'economy', 'sort_order' => 1],
                ],
            ],
            [
                'en' => 'Sports',
                'fa' => 'ورزش',
                'slug' => 'sports',
                'sort_order' => 4,
                'children' => [],
            ],
            [
                'en' => 'Art & Culture',
                'fa' => 'هنر و فرهنگ',
                'slug' => 'art-culture',
                'sort_order' => 5,
                'children' => [],
            ],
            [
                'en' => 'Opinions',
                'fa' => 'دیدگاه‌ها',
                'slug' => 'opinions',
                'sort_order' => 6,
                'children' => [
                    ['en' => 'Economy', 'fa' => 'اقتصاد', 'slug' => 'opinion-economy', 'sort_order' => 1],
                    ['en' => 'Global', 'fa' => 'جهانی', 'slug' => 'global', 'sort_order' => 2],
                    ['en' => 'Human Rights', 'fa' => 'حقوق بشر', 'slug' => 'opinion-human-rights', 'sort_order' => 3],
                    ['en' => 'On the Road in America', 'fa' => 'در راه آمریکا', 'slug' => 'on-the-road-in-america', 'sort_order' => 4],
                    ['en' => 'Politics', 'fa' => 'سیاست', 'slug' => 'opinion-politics', 'sort_order' => 5],
                    ['en' => 'Society', 'fa' => 'جامعه', 'slug' => 'society', 'sort_order' => 6],
                ],
            ],
            [
                'en' => 'Special Events',
                'fa' => 'رویدادهای ویژه',
                'slug' => 'special-events',
                'sort_order' => 7,
                'children' => [
                    ['en' => 'Taliban’s Fourth Year in Power', 'fa' => 'چهارمین سال طالبان در قدرت', 'slug' => 'talibans-fourth-year-in-power', 'sort_order' => 1],
                    ['en' => 'International Women’s Day', 'fa' => 'روز جهانی زن', 'slug' => 'international-womens-day', 'sort_order' => 2],
                    ['en' => 'Two Years Since Fall', 'fa' => 'دو سال پس از سقوط', 'slug' => 'two-years-since-fall', 'sort_order' => 3],
                ],
            ],
            [
                'en' => 'Videos',
                'fa' => 'ویدیوها',
                'slug' => 'videos',
                'sort_order' => 8,
                'children' => [],
            ],
        ];

        $categories = collect();

        foreach ($menuTree as $menuItem) {
            $parent = Category::updateOrCreate(
                ['slug' => $menuItem['slug']],
                [
                    'name_en' => $menuItem['en'],
                    'name_fa' => $menuItem['fa'],
                    'parent_id' => null,
                    'is_active' => true,
                    'sort_order' => $menuItem['sort_order'],
                ]
            );

            $categories->push($parent);

            foreach ($menuItem['children'] as $child) {
                $categories->push(
                    Category::updateOrCreate(
                        ['slug' => $child['slug']],
                        [
                            'name_en' => $child['en'],
                            'name_fa' => $child['fa'],
                            'parent_id' => $parent->id,
                            'is_active' => true,
                            'sort_order' => $child['sort_order'],
                        ]
                    )
                );
            }
        }

        $admin = User::updateOrCreate(
            ['email' => 'admin@panahpress.com'],
            [
                'name' => 'PanahPress Admin',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ]
        );

        $categoryMap = $categories->keyBy('slug');

        $posts = [
            [
                'category' => 'human-rights',
                'en' => 'Shiite cleric says Taliban have increased pressure on Jafari followers',
                'fa' => 'روحانی شیعه می‌گوید طالبان فشار بر پیروان مذهب جعفری را افزایش داده‌اند',
                'content_en' => 'A Kabul-based Shiite cleric says restrictions on Jafari students and worshippers have intensified in recent months, deepening concerns over sectarian pressure and social division. Community members say they are asking for legal protections and access to education without pressure to change their religious identity.',
                'content_fa' => 'یک روحانی شیعه در کابل می‌گوید محدودیت‌ها بر دانش‌آموزان و عبادت‌کنندگان جعفری در ماه‌های اخیر افزایش یافته و نگرانی‌ها درباره فشار مذهبی و شکاف اجتماعی را بیشتر کرده است. اعضای جامعه می‌گویند به دنبال تضمین‌های قانونی و دسترسی به آموزش بدون فشار برای تغییر هویت مذهبی خود هستند.',
                'days_ago' => 1,
            ],
            [
                'category' => 'women',
                'en' => 'UN Women warns Taliban decree could normalize child marriage',
                'fa' => 'سازمان زنان سازمان ملل هشدار داد که فرمان طالبان می‌تواند ازدواج کودکان را عادی‌سازی کند',
                'content_en' => 'UN Women says recent restrictions may push vulnerable families toward earlier marriages for girls as economic pressure and school closures continue to reshape household decisions. Rights advocates argue the policy environment is making it harder for girls to remain in class and reach adulthood safely.',
                'content_fa' => 'سازمان زنان سازمان ملل می‌گوید محدودیت‌های اخیر ممکن است خانواده‌های آسیب‌پذیر را به ازدواج زودهنگام دختران سوق دهد؛ در حالی که فشار اقتصادی و بسته‌ماندن مدارس همچنان تصمیم‌های خانوادگی را تغییر می‌دهد. فعالان حقوق زن می‌گویند فضای سیاستی کنونی، ادامه تحصیل دختران و رسیدن امن آنان به بزرگسالی را دشوارتر کرده است.',
                'days_ago' => 2,
            ],
            [
                'category' => 'women',
                'en' => 'Karzai repeats call for girls to return to schools, universities',
                'fa' => 'کرزی بار دیگر خواستار بازگشت دختران به مدارس و دانشگاه‌ها شد',
                'content_en' => 'Former president Hamid Karzai renewed his appeal for the reopening of schools and universities for girls, saying the country needs educated women to move forward. The comments come as activists and families continue to call for broader access to classrooms and higher education.',
                'content_fa' => 'حامد کرزی، رئیس‌جمهور پیشین افغانستان، بار دیگر خواستار بازگشایی مدارس و دانشگاه‌ها برای دختران شد و گفت کشور برای پیشرفت به زنان تحصیل‌کرده نیاز دارد. این سخنان در حالی مطرح می‌شود که فعالان و خانواده‌ها همچنان خواهان دسترسی گسترده‌تر به آموزش هستند.',
                'days_ago' => 3,
            ],
            [
                'category' => 'economy',
                'en' => 'Women in Ghor say loss of aid has crippled small businesses',
                'fa' => 'زنان در غور می‌گویند قطع کمک‌ها کسب‌وکارهای کوچک را فلج کرده است',
                'content_en' => 'Women who once depended on small grants to support tailoring, poultry, and home-based trade say the disappearance of aid has left many businesses struggling to survive. Local traders say even modest cash support can keep families employed and small shops open.',
                'content_fa' => 'زنانی که پیش‌تر برای حمایت از خیاطی، مرغداری و تجارت خانگی به کمک‌های کوچک متکی بودند، می‌گویند قطع کمک‌ها باعث شده بسیاری از کسب‌وکارها برای بقا تلاش کنند. تاجران محلی می‌گویند حتی کمک نقدی محدود هم می‌تواند خانواده‌ها را سر کار نگه دارد و مغازه‌های کوچک را باز نگه دارد.',
                'days_ago' => 4,
            ],
            [
                'category' => 'economy',
                'en' => 'In Herat, child laborers work among drug users, crime',
                'fa' => 'در هرات، کودکان کار میان معتادان و جرم زندگی می‌کنند',
                'content_en' => 'Families in Herat say poverty has pushed children into street labor, where they are exposed to addiction, theft, and unsafe work conditions. Social workers warn that without stronger support and schooling, the cycle of poverty will keep expanding.',
                'content_fa' => 'خانواده‌ها در هرات می‌گویند فقر کودکان را به کار خیابانی کشانده است؛ جایی که در معرض اعتیاد، سرقت و شرایط کاری ناامن قرار می‌گیرند. مددکاران اجتماعی هشدار می‌دهند که بدون حمایت بیشتر و آموزش، چرخه فقر همچنان گسترش خواهد یافت.',
                'days_ago' => 5,
            ],
            [
                'category' => 'health',
                'en' => 'Afghanistan launches new polio campaign amid continuing access challenges',
                'fa' => 'افغانستان در میان چالش‌های دسترسی، کارزار تازه فلج اطفال را آغاز کرد',
                'content_en' => 'Health officials say a new vaccination campaign aims to reach children in districts where access has remained difficult. Medical workers are urging families to cooperate with teams as the country continues efforts to eliminate polio and strengthen basic public health coverage.',
                'content_fa' => 'مقام‌های صحی می‌گویند کارزار تازه واکسیناسیون تلاش دارد به کودکانی در ولسوالی‌هایی برسد که دسترسی به آن‌ها دشوار مانده است. کارمندان صحی از خانواده‌ها می‌خواهند با تیم‌ها همکاری کنند، در حالی که کشور همچنان برای ریشه‌کن کردن فلج اطفال و تقویت خدمات ابتدایی صحی تلاش می‌کند.',
                'days_ago' => 6,
            ],
            [
                'category' => 'security',
                'en' => 'Taliban defense minister says some countries seek to destabilize Afghanistan',
                'fa' => 'وزیر دفاع طالبان می‌گوید برخی کشورها به دنبال بی‌ثبات کردن افغانستان هستند',
                'content_en' => 'The defense minister accused unnamed foreign actors of trying to weaken security and stir unrest across Afghanistan. Officials say they remain focused on border control and internal stability, while critics warn that public trust depends on transparency and restraint.',
                'content_fa' => 'وزیر دفاع طالبان برخی بازیگران خارجیِ نامشخص را متهم کرد که می‌خواهند امنیت را تضعیف و ناآرامی ایجاد کنند. مقام‌ها می‌گویند بر کنترل مرزها و ثبات داخلی تمرکز دارند، در حالی که منتقدان هشدار می‌دهند اعتماد عمومی به شفافیت و خویشتن‌داری وابسته است.',
                'days_ago' => 7,
            ],
            [
                'category' => 'world',
                'en' => 'Trump says Iran deal, Strait of Hormuz reopening are near',
                'fa' => 'ترامپ می‌گوید توافق با ایران و بازگشایی تنگه هرمز نزدیک است',
                'content_en' => 'A new diplomatic proposal on Iran has raised hopes of a regional thaw, with talks reportedly touching on the Strait of Hormuz and broader security guarantees. Analysts say even partial progress could affect trade, shipping, and politics across the Middle East.',
                'content_fa' => 'یک پیشنهاد تازه دیپلماتیک درباره ایران امیدها را برای کاهش تنش منطقه‌ای افزایش داده است؛ مذاکراتی که گفته می‌شود به تنگه هرمز و تضمین‌های امنیتی گسترده‌تر نیز پرداخته است. تحلیلگران می‌گویند حتی پیشرفت محدود هم می‌تواند بر تجارت، کشتیرانی و سیاست در خاورمیانه اثر بگذارد.',
                'days_ago' => 8,
            ],
            [
                'category' => 'world',
                'en' => 'UN says new $1.8 billion US aid will expand humanitarian operations',
                'fa' => 'سازمان ملل می‌گوید کمک جدید ۱.۸ میلیارد دلاری آمریکا عملیات بشردوستانه را گسترش می‌دهد',
                'content_en' => 'UN officials say fresh funding will help humanitarian agencies extend health, food, and protection programs in countries facing acute crises. Aid leaders say the challenge is not only raising money, but also ensuring assistance reaches families quickly and safely.',
                'content_fa' => 'مقام‌های سازمان ملل می‌گویند تأمین مالی تازه به نهادهای بشردوستانه کمک می‌کند تا برنامه‌های صحی، غذایی و حمایتی را در کشورهایی با بحران شدید گسترش دهند. مسئولان کمک‌رسانی می‌گویند چالش فقط جمع‌آوری پول نیست، بلکه رساندن سریع و امن کمک‌ها به خانواده‌هاست.',
                'days_ago' => 9,
            ],
            [
                'category' => 'sports',
                'en' => 'Afghanistan recall Farooqi from England’s Vitality Blast',
                'fa' => 'افغانستان فاروقی را از لیگ ویتالیتی انگلیس فراخواند',
                'content_en' => 'National selectors have recalled fast bowler Fazalhaq Farooqi, reflecting the team’s push to balance international duties with domestic and franchise commitments. Coaches say the move is part of keeping the squad fresh ahead of upcoming fixtures.',
                'content_fa' => 'انتخاب‌کنندگان ملی، فازل‌حق فاروقی را فراخوانده‌اند؛ اقدامی که نشان می‌دهد تیم می‌خواهد میان مسئولیت‌های بین‌المللی و تعهدات داخلی و لیگ‌ها تعادل ایجاد کند. مربیان می‌گویند این تصمیم بخشی از آماده‌سازی تیم برای دیدارهای پیش‌رو است.',
                'days_ago' => 10,
            ],
            [
                'category' => 'sports',
                'en' => 'Afghanistan names squads for India Test and ODI series',
                'fa' => 'افغانستان فهرست تیم‌های خود را برای مسابقات تست و یک‌روزه برابر هند اعلام کرد',
                'content_en' => 'Selectors announced national squads for a packed series schedule, signaling a focus on depth and consistency across formats. Cricket officials say the selections are meant to support emerging players while keeping experienced names at the center of the setup.',
                'content_fa' => 'کمیته انتخاب تیم‌های ملی، فهرست بازیکنان را برای یک برنامه فشرده مسابقات اعلام کرد و بر عمق ترکیب و ثبات در قالب‌های مختلف تأکید داشت. مسئولان کریکت می‌گویند این انتخاب‌ها برای حمایت از بازیکنان جوان و حفظ چهره‌های باتجربه در مرکز تیم انجام شده است.',
                'days_ago' => 11,
            ],
            [
                'category' => 'art-culture',
                'en' => 'Kabul poetry night brings writers back to public stage',
                'fa' => 'شب شعر کابل نویسندگان را دوباره به صحنه عمومی آورد',
                'content_en' => 'Poets and writers gathered in Kabul for an evening of readings that organizers said was meant to restore public literary life after years of disruption. Attendees described the event as a rare but welcome reminder of the city’s cultural energy.',
                'content_fa' => 'شاعران و نویسندگان در کابل برای یک شب شعر گرد هم آمدند؛ برنامه‌ای که برگزارکنندگان می‌گویند برای احیای زندگی ادبی عمومی پس از سال‌ها اختلال برگزار شد. شرکت‌کنندگان این برنامه را یادآوری نادری از انرژی فرهنگی شهر توصیف کردند.',
                'days_ago' => 12,
            ],
            [
                'category' => 'special-events',
                'en' => 'Taliban’s Fourth Year in Power: Afghans weigh stability and loss',
                'fa' => 'چهارمین سال طالبان در قدرت: افغان‌ها ثبات و زیان را می‌سنجند',
                'content_en' => 'As the Taliban mark another year in power, families are reflecting on what has changed in security, livelihoods, and public life. The anniversary has become a moment to compare promises of stability with the lived reality of restrictions and hardship.',
                'content_fa' => 'همزمان با آنکه طالبان سال دیگری از حاکمیت خود را پشت سر می‌گذارند، خانواده‌ها درباره تغییرات در امنیت، معیشت و زندگی عمومی تأمل می‌کنند. این سالگرد به فرصتی برای مقایسه وعده‌های ثبات با واقعیت محدودیت‌ها و سختی‌های روزمره تبدیل شده است.',
                'days_ago' => 13,
            ],
            [
                'category' => 'special-events',
                'en' => 'International Women’s Day: Afghan women call for access to education',
                'fa' => 'روز جهانی زن: زنان افغان خواستار دسترسی به آموزش شدند',
                'content_en' => 'On International Women’s Day, Afghan women and advocates renewed their call for education, work, and participation in public life. Their message stressed that dignity and opportunity should remain central to the country’s future.',
                'content_fa' => 'در روز جهانی زن، زنان افغان و مدافعان حقوق بشر بار دیگر خواستار آموزش، کار و حضور در زندگی عمومی شدند. پیام آنان تأکید داشت که کرامت و فرصت باید همچنان در مرکز آینده کشور باقی بماند.',
                'days_ago' => 14,
            ],
            [
                'category' => 'special-events',
                'en' => 'Two Years Since Fall: Kabul families describe daily life now',
                'fa' => 'دو سال پس از سقوط: خانواده‌های کابل از زندگی روزمره امروز می‌گویند',
                'content_en' => 'Residents in Kabul say daily life has changed in ways they still measure every day, from schooling and employment to transport and family budgets. The milestone has prompted a mix of reflection, fatigue, and cautious hope.',
                'content_fa' => 'ساکنان کابل می‌گویند زندگی روزمره به شکلی تغییر کرده که هر روز آن را در آموزش، کار، رفت‌وآمد و بودجه خانواده احساس می‌کنند. این نقطه عطف ترکیبی از تأمل، خستگی و امید محتاطانه را برانگیخته است.',
                'days_ago' => 15,
            ],
            [
                'category' => 'media',
                'en' => 'Local journalists navigate restrictions while keeping Afghan stories visible',
                'fa' => 'خبرنگاران محلی در میان محدودیت‌ها تلاش می‌کنند داستان‌های افغانستان را زنده نگه دارند',
                'content_en' => 'Journalists say reporting has become more difficult as access, safety, and sources all require greater caution. Even so, newsroom teams continue to publish local stories they believe are essential for public accountability and community awareness.',
                'content_fa' => 'خبرنگاران می‌گویند گزارش‌دهی دشوارتر شده است، زیرا دسترسی، امنیت و منابع همگی به احتیاط بیشتری نیاز دارند. با این حال، تیم‌های تحریریه همچنان داستان‌های محلی را منتشر می‌کنند که به باور آنان برای پاسخگویی عمومی و آگاهی جامعه ضروری است.',
                'days_ago' => 16,
            ],
            [
                'category' => 'economy',
                'en' => 'Market prices climb in Kandahar as traders warn of thin margins',
                'fa' => 'قیمت‌ها در قندهار بالا می‌رود و تاجران از حاشیه سود اندک هشدار می‌دهند',
                'content_en' => 'Merchants in Kandahar say rising costs are pushing basic goods beyond what many households can afford. Traders warn that if supply remains tight, small businesses will continue to face shrinking margins and fewer customers.',
                'content_fa' => 'تاجران در قندهار می‌گویند افزایش هزینه‌ها کالاهای اساسی را از توان خرید بسیاری از خانواده‌ها فراتر برده است. آنان هشدار می‌دهند اگر عرضه همچنان محدود بماند، کسب‌وکارهای کوچک با حاشیه سود کمتر و مشتریان کمتری روبه‌رو خواهند شد.',
                'days_ago' => 17,
            ],
            [
                'category' => 'health',
                'en' => 'Hospitals report rise in malnutrition cases among children',
                'fa' => 'بیمارستان‌ها از افزایش موارد سوءتغذیه در میان کودکان خبر دادند',
                'content_en' => 'Doctors in several provinces say more families are arriving at clinics with children who need urgent nutritional support. Health workers are calling for sustained aid, better screening, and faster referral systems to prevent severe complications.',
                'content_fa' => 'دکتران در چندین ولایت می‌گویند خانواده‌های بیشتری با کودکان نیازمند حمایت فوری تغذیه‌ای به کلینیک‌ها مراجعه می‌کنند. کارکنان صحی خواهان تداوم کمک‌ها، غربالگری بهتر و نظام ارجاع سریع‌تر برای جلوگیری از عوارض شدید هستند.',
                'days_ago' => 18,
            ],
            [
                'category' => 'videos',
                'en' => 'Samim Sadat: The Taliban have not brought stability',
                'fa' => 'سمیع سادات: طالبان ثبات نیاورده‌اند؛ جبهه متحد به مبارزه ادامه می‌دهد',
                'content_en' => 'A Panah Press video interview discussing Afghanistan’s political situation, the Taliban, and the continuing opposition stance.',
                'content_fa' => 'یک گفت‌وگوی ویدیویی از پناه پریس درباره وضعیت سیاسی افغانستان، طالبان و تداوم موضع مخالفان.',
                'video_url' => 'https://www.youtube.com/watch?v=L1FGKggNk34',
                'days_ago' => 19,
            ],
            [
                'category' => 'videos',
                'en' => 'Ali Radmehr: Hazara culture has expanded beyond borders',
                'fa' => 'علی رادمهر: فرهنگ هزاره فراتر از مرزها گسترش یافته است',
                'content_en' => 'A Panah Press video feature on Hazara culture, identity, and cross-border cultural visibility.',
                'content_fa' => 'ویدیویی از پناه پریس درباره فرهنگ، هویت و دیده‌شدن فرامرزی جامعه هزاره.',
                'video_url' => 'https://www.youtube.com/watch?v=LDg4tMYYAQc',
                'days_ago' => 20,
            ],
            [
                'category' => 'videos',
                'en' => 'Hazara Culture Day celebrated with a special program',
                'fa' => 'روز فرهنگ هزاره، با برگزاری برنامه‌ای ویژه تجلیل کرد',
                'content_en' => 'Coverage of a special cultural event highlighting Hazara heritage and public celebration.',
                'content_fa' => 'پوشش یک رویداد فرهنگی ویژه که میراث هزاره و جشن عمومی آن را برجسته می‌کند.',
                'video_url' => 'https://www.youtube.com/watch?v=A2tBgDG9do4',
                'days_ago' => 21,
            ],
            [
                'category' => 'videos',
                'en' => 'Qari Isa Mohammadi criticizes former Republic officials',
                'fa' => 'قاری عیسی محمدی در ویدیوی تازه از چهره‌های حکومت پیشین جمهوریت انتقاد کرد',
                'content_en' => 'A Panah Press video post featuring criticism of former Republic figures and current political commentary.',
                'content_fa' => 'پست ویدیویی از پناه پریس درباره انتقاد از چهره‌های پیشین جمهوریت و تحلیل سیاسی روز.',
                'video_url' => 'https://www.youtube.com/watch?v=RMoQHz471a8',
                'days_ago' => 22,
            ],
            [
                'category' => 'videos',
                'en' => 'Izzatullah Qorbanzada: From apprenticeship to family income',
                'fa' => 'عزت‌الله قربان‌زاده: از شاگردی تا ایجاد منبع درآمد برای چند خانواده',
                'content_en' => 'A profile video about a local worker whose apprenticeship turned into support for several families.',
                'content_fa' => 'یک ویدیوی پرتره درباره کارگری محلی که شاگردی را به منبع درآمد برای چند خانواده تبدیل کرده است.',
                'video_url' => 'https://www.youtube.com/watch?v=o5RSt23u0E8',
                'days_ago' => 23,
            ],
            [
                'category' => 'videos',
                'en' => 'Mohammad Tahir Razmjo interview',
                'fa' => 'محمد طاهر رزمجو',
                'content_en' => 'A short interview segment from the Panah Press YouTube channel.',
                'content_fa' => 'بخشی از یک گفت‌وگوی کوتاه از کانال یوتیوب پناه پریس.',
                'video_url' => 'https://www.youtube.com/watch?v=CL3QbjUOEOA',
                'days_ago' => 24,
            ],
            [
                'category' => 'videos',
                'en' => 'A Tajik member of the Taliban speaks against discrimination',
                'fa' => 'یک عضو تاجیک‌تبار طالبان با لحنی تند از تبعیض و بی‌عدالتی در ساختار قدرت این گروه انتقاد کرده',
                'content_en' => 'A Panah Press video discussing criticism from within the Taliban structure and questions of discrimination.',
                'content_fa' => 'ویدیویی از پناه پریس درباره انتقاد از درون ساختار طالبان و مسئله تبعیض.',
                'video_url' => 'https://www.youtube.com/watch?v=ElUYQGzNH9Y',
                'days_ago' => 25,
            ],
            [
                'category' => 'videos',
                'en' => 'A copy of the Durand Line treaty in the British Library',
                'fa' => 'نسخه‌ای از معاهده دیورند در کتابخانه بریتانیا',
                'content_en' => 'A historical video post about the Durand Line treaty and archive research in the British Library.',
                'content_fa' => 'یک پست ویدیویی تاریخی درباره معاهده دیورند و پژوهش آرشیفی در کتابخانه بریتانیا.',
                'video_url' => 'https://www.youtube.com/watch?v=mEeJeMxzkgU',
                'days_ago' => 26,
            ],
        ];

        foreach ($posts as $index => $postData) {
            $category = $categoryMap->get($postData['category']) ?? $categories->first();
            $slug = Str::slug($postData['en']) ?: ('post-'.$index);

            Post::updateOrCreate(
                ['slug' => $slug],
                [
                    'user_id' => $admin->id,
                    'category_id' => $category?->id,
                    'title_en' => $postData['en'],
                    'title_fa' => $postData['fa'],
                    'content_en' => $postData['content_en'],
                    'content_fa' => $postData['content_fa'],
                    'image' => 'uploads/placeholder'.(($index % 8) + 1).'.svg',
                    'published_at' => now()->subDays($postData['days_ago']),
                ]
            );
        }
    }
}
