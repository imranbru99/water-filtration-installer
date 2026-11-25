<?php

namespace Database\Seeders;

use App\Models\Appointment;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\KnowledgeArticle;
use App\Models\Payment;
use App\Models\Post;
use App\Models\Product;
use App\Models\Project;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        foreach (['admin', 'manager', 'staff'] as $role) {
            Role::findOrCreate($role);
        }

        $admin = User::query()->updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'phone' => '(303) 555-0100',
            ]
        );
        $admin->syncRoles(['admin']);

        User::query()->updateOrCreate(
            ['email' => 'manager@crestpurewater.com'],
            [
                'name' => 'Elena Vargas',
                'password' => Hash::make('password'),
                'role' => 'manager',
                'phone' => '(303) 555-0101',
            ]
        )->syncRoles(['manager']);

        User::query()->updateOrCreate(
            ['email' => 'tech@crestpurewater.com'],
            [
                'name' => 'Marcus Quinn',
                'password' => Hash::make('password'),
                'role' => 'staff',
                'phone' => '(303) 555-0102',
            ]
        )->syncRoles(['staff']);

        $settings = [
            'company_name' => 'Crestpure Water Systems',
            'company_phone' => '(303) 555-0148',
            'company_email' => 'hello@crestpurewater.com',
            'whatsapp' => '13035550148',
            'service_area' => 'Denver, Boulder, Aurora, Highlands Ranch & Colorado Front Range',
            'tax_rate' => '8.81',
        ];
        foreach ($settings as $key => $value) {
            Setting::setValue($key, $value);
        }

        $products = [
            ['name' => 'Sediment Pre-Filter 5 Micron', 'sku' => 'WFI-SED-5', 'category' => 'sediment', 'unit_cost' => 12, 'unit_price' => 28, 'stock_qty' => 80],
            ['name' => 'Catalytic Carbon Media Bag (1 cu ft)', 'sku' => 'WFI-CAR-1', 'category' => 'carbon', 'unit_cost' => 95, 'unit_price' => 185, 'stock_qty' => 24],
            ['name' => 'Whole-Home POE Housing 20"', 'sku' => 'WFI-POE-20', 'category' => 'housings', 'unit_cost' => 68, 'unit_price' => 135, 'stock_qty' => 32],
            ['name' => 'RO Membrane 75 GPD', 'sku' => 'WFI-RO-75', 'category' => 'ro', 'unit_cost' => 32, 'unit_price' => 69, 'stock_qty' => 45],
            ['name' => 'Under-Sink RO System 5-Stage', 'sku' => 'WFI-RO-5S', 'category' => 'ro', 'unit_cost' => 185, 'unit_price' => 420, 'stock_qty' => 16],
            ['name' => 'Softener Resin Tank 48k Grain', 'sku' => 'WFI-SFT-48', 'category' => 'softeners', 'unit_cost' => 420, 'unit_price' => 890, 'stock_qty' => 10],
            ['name' => 'UV Chamber 12 GPM + Lamp', 'sku' => 'WFI-UV-12', 'category' => 'uv', 'unit_cost' => 310, 'unit_price' => 650, 'stock_qty' => 12],
            ['name' => 'Bypass Valve Kit 1"', 'sku' => 'WFI-BP-1', 'category' => 'fittings', 'unit_cost' => 38, 'unit_price' => 75, 'stock_qty' => 40],
        ];
        foreach ($products as $product) {
            Product::query()->updateOrCreate(['sku' => $product['sku']], $product + ['unit' => 'ea', 'is_active' => true]);
        }

        $customers = collect([
            ['name' => 'Jordan Hale', 'email' => 'jordan@example.com', 'phone' => '(303) 555-4401', 'company' => null, 'city' => 'Denver', 'state' => 'CO', 'zip' => '80206', 'address' => '1420 Cherry Creek Dr'],
            ['name' => 'Aisha Rahman', 'email' => 'aisha@peakhoa.com', 'phone' => '(303) 555-4410', 'company' => 'Peakview HOA', 'city' => 'Aurora', 'state' => 'CO', 'zip' => '80014', 'address' => '8900 E Mississippi Ave'],
            ['name' => 'Ben Ortiz', 'email' => 'ops@boulderbrewcafe.com', 'phone' => '(720) 555-4499', 'company' => 'Boulder Brew Cafe', 'city' => 'Boulder', 'state' => 'CO', 'zip' => '80302', 'address' => '1120 Pearl St'],
            ['name' => 'Nina Patel', 'email' => 'nina.patel@example.com', 'phone' => '(720) 555-4188', 'company' => null, 'city' => 'Highlands Ranch', 'state' => 'CO', 'zip' => '80126', 'address' => '34 Wildcat Ridge'],
            ['name' => 'Chris Novak', 'email' => 'chris@frontlineclinic.com', 'phone' => '(303) 555-4177', 'company' => 'Frontline Dental Clinic', 'city' => 'Denver', 'state' => 'CO', 'zip' => '80218', 'address' => '455 Colfax Ave'],
        ])->map(fn ($c) => Customer::query()->updateOrCreate(['email' => $c['email']], $c));

        $projects = [
            ['customer' => 0, 'title' => 'Whole-home carbon + sediment POE', 'service_type' => 'whole_home', 'status' => 'scheduled', 'sq_ft' => 2400, 'quoted_amount' => 2850],
            ['customer' => 1, 'title' => 'HOA clubhouse softener upgrade', 'service_type' => 'softener', 'status' => 'in_progress', 'sq_ft' => 6800, 'quoted_amount' => 9400],
            ['customer' => 2, 'title' => 'Cafe dual RO + remineralization', 'service_type' => 'ro', 'status' => 'quoted', 'sq_ft' => 1800, 'quoted_amount' => 6200],
            ['customer' => 3, 'title' => 'Softener + under-sink RO combo', 'service_type' => 'softener', 'status' => 'completed', 'sq_ft' => 3100, 'quoted_amount' => 4100, 'final_amount' => 4280],
            ['customer' => 4, 'title' => 'Clinic UV + sediment stack', 'service_type' => 'uv', 'status' => 'invoiced', 'sq_ft' => 4200, 'quoted_amount' => 5600, 'final_amount' => 5550],
        ];

        $projectModels = [];
        foreach ($projects as $i => $p) {
            $customer = $customers[$p['customer']];
            $projectModels[] = Project::query()->updateOrCreate(
                ['job_number' => 'JOB-26'.str_pad((string) ($i + 1), 3, '0', STR_PAD_LEFT)],
                [
                    'customer_id' => $customer->id,
                    'title' => $p['title'],
                    'service_type' => $p['service_type'],
                    'status' => $p['status'],
                    'sq_ft' => $p['sq_ft'],
                    'quoted_amount' => $p['quoted_amount'],
                    'final_amount' => $p['final_amount'] ?? null,
                    'start_date' => now()->subDays(10 - $i)->toDateString(),
                    'site_address' => trim(($customer->address ?? '').', '.$customer->city.', '.$customer->state),
                    'notes' => 'Seeded demo job for Crestpure ERP.',
                ]
            );
        }

        foreach ([0, 1, 3, 4] as $idx) {
            $project = $projectModels[$idx];
            $invoice = Invoice::query()->updateOrCreate(
                ['invoice_number' => 'INV-26'.str_pad((string) ($idx + 1), 3, '0', STR_PAD_LEFT)],
                [
                    'customer_id' => $project->customer_id,
                    'project_id' => $project->id,
                    'issue_date' => now()->subDays(8 - $idx)->toDateString(),
                    'due_date' => now()->addDays(14)->toDateString(),
                    'subtotal' => $project->final_amount ?? $project->quoted_amount,
                    'tax' => round(($project->final_amount ?? $project->quoted_amount) * 0.0881, 2),
                    'total' => round(($project->final_amount ?? $project->quoted_amount) * 1.0881, 2),
                    'amount_paid' => in_array($project->status, ['completed', 'invoiced'], true) ? round(($project->final_amount ?? $project->quoted_amount) * 1.0881, 2) : 0,
                    'status' => in_array($project->status, ['completed', 'invoiced'], true) ? 'paid' : 'sent',
                ]
            );

            if ($invoice->status === 'paid') {
                Payment::query()->updateOrCreate(
                    ['reference' => 'PMT-'.$invoice->invoice_number],
                    [
                        'invoice_id' => $invoice->id,
                        'amount' => $invoice->total,
                        'paid_at' => now()->subDays(3)->toDateString(),
                        'method' => 'card',
                        'notes' => 'Demo payment',
                    ]
                );
            }
        }

        Appointment::query()->updateOrCreate(
            ['title' => 'Water test — Hale'],
            [
                'customer_id' => $customers[0]->id,
                'project_id' => $projectModels[0]->id,
                'type' => 'water_test',
                'starts_at' => now()->addDays(2)->setTime(9, 0),
                'ends_at' => now()->addDays(2)->setTime(10, 0),
                'status' => 'scheduled',
                'location' => $customers[0]->address,
            ]
        );
        Appointment::query()->updateOrCreate(
            ['title' => 'HOA softener install day'],
            [
                'customer_id' => $customers[1]->id,
                'project_id' => $projectModels[1]->id,
                'type' => 'install',
                'starts_at' => now()->addDays(9)->setTime(7, 0),
                'ends_at' => now()->addDays(9)->setTime(17, 0),
                'status' => 'confirmed',
                'location' => $customers[1]->address,
            ]
        );

        $posts = [
            [
                'title' => 'Why Denver Homes Still Taste Like Chlorine',
                'excerpt' => 'Municipal disinfection protects public health — but carbon at the right stage makes tap water pleasant again.',
                'cover' => 'https://images.unsplash.com/photo-1548832337-c6683fc531b2?auto=format&fit=crop&w=1400&q=80',
                'body' => "Chlorine and chloramine keep city water safe, yet they leave a pool-like taste that drives people to bottled water. Front Range households often assume they need reverse osmosis first — when catalytic carbon at the point of entry already transforms showers and cooking water.\n\nCrestpure tests free chlorine, sizes media beds for your peak flow, and installs bypass valves so outdoor hose bibs stay untreated when you want them that way. If your kitchen smells like a public pool, stop guessing and book a free water test.",
            ],
            [
                'title' => 'Softener vs Filter: What Front Range Hardness Really Needs',
                'excerpt' => 'Scale is a hardness problem. Taste is often a chlorine or sediment problem. Mixing them up wastes money.',
                'cover' => 'https://images.unsplash.com/photo-1564419320461-6870880221ad?auto=format&fit=crop&w=1400&q=80',
                'body' => "Ion-exchange softeners remove calcium and magnesium that etch glassware and coat water heaters. Carbon filters target taste, odor, and organic chemicals. Sediment filters catch grit that shortens appliance life.\n\nFor Highlands Ranch and Aurora homes with 12–20+ grains hardness, we often pair a softener with a sediment pre-filter — then add under-sink RO only if drinking TDS or fluoride is a concern. Match the technology to the lab result, not the marketing flyer.",
            ],
            [
                'title' => 'When Well Owners Should Add UV Disinfection',
                'excerpt' => 'Private wells skip municipal treatment — UV is the quiet layer that keeps microbial risk in check.',
                'cover' => 'https://images.unsplash.com/photo-1611270629569-8b357cb88da9?auto=format&fit=crop&w=1400&q=80',
                'body' => "A clear glass of well water can still fail a coliform test. UV chambers destroy bacteria and viruses without chemicals, but they only work when water is pre-filtered and lamps are changed on schedule.\n\nCrestpure sizes UV to peak GPM, installs sediment/carbon upstream, and logs lamp replacements in the ERP so you are not flying blind a year later. Ask for a well package when your latest lab report shows any microbial flag.",
            ],
        ];
        foreach ($posts as $post) {
            Post::query()->updateOrCreate(
                ['slug' => Str::slug($post['title'])],
                [
                    'title' => $post['title'],
                    'excerpt' => $post['excerpt'],
                    'body' => $post['body'],
                    'cover_image' => $post['cover'],
                    'is_published' => true,
                    'published_at' => now()->subDays(random_int(2, 20)),
                ]
            );
        }

        $articles = [
            ['category' => 'issues', 'title' => 'Low pressure after filter install', 'body' => "1. Confirm bypass valve is fully in service position.\n2. Check sediment cartridge for clogging — Front Range grit clogs 5-micron fast.\n3. Verify POE housing O-ring is seated and not pinching flow.\n4. Measure pressure before and after media tanks.\n5. Upsize housing or add dual banks if peak GPM exceeds design."],
            ['category' => 'issues', 'title' => 'RO faucet dripping or slow fill', 'body' => "Slow tank fills usually mean a fouled membrane, low feed pressure, or a stuck automatic shutoff valve. Check pre-filters, measure feed PSI, and replace the membrane if TDS rejection has dropped. Drips at the faucet often need a new ceramic disc or air-gap drain cleanout."],
            ['category' => 'guides', 'title' => 'Whole-home install day timeline', 'body' => "Morning: Shut main, drain lines, set bypass and housings.\nMidday: Charge media, plumb to code, flush carbon fines.\nAfternoon: Pressure-test, label change dates, walk homeowner through salt/cartridge schedule.\nHandoff: Warranty packet, ERP job notes, and next service reminder."],
            ['category' => 'guides', 'title' => 'How to prepare for a free water test', 'body' => "Know where the main shutoff and water heater are. Note scale, taste, and staining. Have recent well or municipal reports if available. Clear access under sinks for RO quotes. Crestpure will measure hardness, TDS, chlorine, and recommend POE vs POU."],
            ['category' => 'admin', 'title' => 'Admin ERP quick start', 'body' => "Login at /admin with admin@example.com / password.\n1. Add customers in CRM.\n2. Create a Job/Project with system type and quote.\n3. Schedule water tests and installs.\n4. Convert completed jobs to invoices and record payments.\n5. Use AI Studio for blog drafts and troubleshooting suggestions.\n6. Update company Settings for phone, WhatsApp, and tax rate."],
            ['category' => 'admin', 'title' => 'Roles: Admin, Manager, Staff', 'body' => "Admin: full ERP + settings + users.\nManager: jobs, inventory, invoices, scheduling.\nStaff: field-facing records and appointment updates.\nAssign roles on the Users resource."],
            ['category' => 'troubleshoot', 'title' => 'Job over media estimate', 'body' => "Check actual GPM vs quote. Confirm extra fittings for tight mechanical rooms. Log additional cartridges on job notes, create a change-order invoice line, and update inventory stock quantities."],
            ['category' => 'troubleshoot', 'title' => 'Chatbot giving demo answers only', 'body' => "Demo mode is expected without API keys. Add OPENAI_API_KEY or GEMINI_API_KEY to .env, then clear config cache. The public chatbot and AI Studio will prefer live models when keys are present."],
        ];
        foreach ($articles as $article) {
            KnowledgeArticle::query()->updateOrCreate(
                ['slug' => Str::slug($article['title'])],
                [
                    'title' => $article['title'],
                    'category' => $article['category'],
                    'body' => $article['body'],
                    'is_published' => true,
                ]
            );
        }
    }
}
