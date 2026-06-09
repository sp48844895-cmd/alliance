<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        DB::table('users')->insert([
            [
                'fname' => 'Suraj', 'lname' => 'Pandey',
                'username' => 'admin',
                'email' => 'admin@abc.in',
                'password' => Hash::make('abc@1234'),
                'image' => '',
                'bio' => 'Platform administrator',
                'role' => 1,
                'type' => 'admin',
                'date' => $now,
                'created_at' => $now, 'updated_at' => $now,
            ],
            [
                'fname' => 'Asha', 'lname' => 'Guest',
                'username' => 'asha.g',
                'email' => 'guest@abc.in',
                'password' => Hash::make('abc@1234'),
                'image' => '',
                'bio' => 'Guest contributor, Raipur',
                'role' => 2,
                'type' => 'guest',
                'date' => $now,
                'created_at' => $now, 'updated_at' => $now,
            ],
            [
                'fname' => 'Riya', 'lname' => 'Intern',
                'username' => 'riya.i',
                'email' => 'intern@abc.in',
                'password' => Hash::make('abc@1234'),
                'image' => '',
                'bio' => 'Intern, communications',
                'role' => 2,
                'type' => 'intern',
                'date' => $now,
                'created_at' => $now, 'updated_at' => $now,
            ],
            [
                'fname' => 'Prerna', 'lname' => 'NGO',
                'username' => 'prerna.ngo',
                'email' => 'ngo@abc.in',
                'password' => Hash::make('abc@1234'),
                'image' => '',
                'bio' => 'Partner NGO contact',
                'role' => 2,
                'type' => 'ngo',
                'date' => $now,
                'created_at' => $now, 'updated_at' => $now,
            ],
            [
                'fname' => 'Neha', 'lname' => 'Fellow',
                'username' => 'neha.f',
                'email' => 'fellow@abc.in',
                'password' => Hash::make('abc@1234'),
                'image' => '',
                'bio' => 'Fellowship participant',
                'role' => 2,
                'type' => 'fellow',
                'date' => $now,
                'created_at' => $now, 'updated_at' => $now,
            ],
        ]);

        DB::table('user')->insert([
            ['name' => 'Legacy Admin', 'username' => 'legacy.admin', 'password' => Hash::make('abc@1234'), 'type' => 1],
        ]);

        $districts = [
            'Raipur','Bilaspur','Durg','Rajnandgaon','Korba','Bhilai-Charoda','Janjgir-Champa',
            'Mahasamund','Dhamtari','Kanker','Bastar','Dantewada','Bijapur','Sukma','Narayanpur',
            'Kondagaon','Surguja','Jashpur','Korea','Surajpur','Balrampur','Mungeli','Baloda Bazar',
            'Bemetara','Balod','Gariyaband','Kabirdham','Gaurela-Pendra-Marwahi','Sakti',
            'Manendragarh-Chirmiri-Bharatpur','Sarangarh-Bilaigarh','Mohla-Manpur-Ambagarh Chowki',
            'Khairagarh-Chhuikhadan-Gandai',
        ];
        foreach ($districts as $name) {
            DB::table('district')->insert(['district_name' => $name, 'status' => 1]);
        }

        $blocksByDistrict = [
            'Raipur' => ['Abhanpur','Arang','Dharsiwa','Tilda'],
            'Bilaspur' => ['Bilha','Kota','Masturi','Takhatpur'],
            'Durg' => ['Patan','Dhamdha','Durg'],
            'Rajnandgaon' => ['Chhuria','Dongargaon','Dongargarh','Rajnandgaon'],
            'Korba' => ['Korba','Kartala','Pali','Poundi-Uproda'],
            'Bastar' => ['Bastar','Bastanar','Bakawand','Darbha','Jagdalpur','Lohandiguda','Tokapal'],
            'Surguja' => ['Lundra','Mainpat','Sitapur','Lakhanpur','Batauli','Udaipur'],
            'Dhamtari' => ['Dhamtari','Kurud','Magarlod','Nagri'],
            'Mahasamund' => ['Mahasamund','Bagbahara','Basna','Pithora','Saraipali'],
        ];
        foreach ($blocksByDistrict as $district => $blocks) {
            $row = DB::table('district')->where('district_name', $district)->first();
            if (!$row) continue;
            foreach ($blocks as $block) {
                DB::table('block')->insert([
                    'district_id' => $row->id,
                    'block_name'  => $block,
                    'status'      => 1,
                ]);
            }
        }

        $cats = [
            'Life skills and Youth',
            'Nudge and Behavioral Economics',
            'Social change and Community Champions',
            'Institutional Transformation and Innovation',
            '#Technology4Change',
            '#Culture4Change',
            'Volunteerism',
            'Mental Health & Wellbeing',
            'Social Welfare',
        ];
        foreach ($cats as $c) {
            DB::table('categories')->insert([
                'category_name' => $c,
                'status' => 1,
                'create_time' => $now,
                'admin_name' => 'Suraj Pandey',
            ]);
        }

        $learningCats = [
            ['Nutrition', 'icon-salad'],
            ['ODF+ and Hygiene', 'icon-soap-dispenser-droplet'],
            ['Child Health and Nutrition', 'icon-baby'],
            ['Maternal Health and Nutrition', 'icon-heart-pulse'],
            ['Education and Parental engagement', 'icon-book-open-text'],
            ['Life skills', 'icon-target'],
            ['Mission Life', 'icon-sprout'],
            ['Adolescent health and Nutrition', 'icon-users'],
            ['Mental Health', 'icon-brain'],
            ['SBC', 'icon-megaphone'],
        ];
        foreach ($learningCats as [$name, $icon]) {
            DB::table('learning_cat')->insert([
                'cat_name' => $name,
                'cat_icon' => $icon,
                'status' => 1,
                'created_at' => $now,
                'admin_name' => 'Suraj Pandey',
            ]);
        }

        DB::table('site')->insert([
            'logo' => '',
            'title' => 'Alliance for Behavior Change',
            'footer' => '© ' . date('Y') . ' Alliance for Behavior Change · Chhattisgarh',
            'postdisplay' => 10,
        ]);

        DB::table('about')->insert([
            'abouttext' => '<p>The Alliance for Behavior Change (ABC) is a Chhattisgarh-wide network of CSOs, NGOs, professionals and volunteers committed to social and behavior change communication.</p>',
            'aboutvideo' => '',
        ]);

        DB::table('social_links')->insert([
            'facebook'   => 'https://facebook.com/abcchhattisgarh',
            'twitter'    => 'https://twitter.com/abcchhattisgarh',
            'instagram'  => 'https://instagram.com/abcchhattisgarh',
            'linkedin'   => 'https://linkedin.com/company/abcchhattisgarh',
            'github'     => '',
            'footerlink' => 'https://abcchhattisgarh.in',
            'footertxt'  => 'ABC Chhattisgarh',
        ]);

        $rootFolders = [
            'KNOWLEDGE MANAGEMENT', 'MENTAL HEALTH', 'LIFE SKILL', 'MATERNAL HEALTH',
            'EDUCATION', 'LIVELIHOOD', 'CHILD CARE', 'NUTRITION', 'ADOLESCENT',
            'COMMUNICATION', 'NUDGE', '#YOUTH4CHANGE',
        ];
        foreach ($rootFolders as $f) {
            DB::table('folders')->insert(['user_id' => 1, 'name' => $f, 'parent_id' => 0]);
        }

        $admin = DB::table('users')->where('email', 'admin@abc.in')->first();
        $author = DB::table('users')->where('email', 'author@abc.in')->first();
        $catRow = DB::table('categories')->first();

        if ($admin && $catRow) {
            for ($i = 1; $i <= 5; $i++) {
                DB::table('blog')->insert([
                    'cat_id' => $catRow->id,
                    'title' => "Sample blog post #$i — life skills in Chhattisgarh",
                    'content' => "<p>Sample content for blog post number $i. This is a seeded entry for development.</p>",
                    'tag' => 'sample,seed,life-skills',
                    'admin' => 'Suraj Pandey',
                    'user_id' => $admin->id,
                    'status' => $i % 2,
                    'rate' => 0,
                    'image' => '',
                    'date_created' => now()->subDays($i),
                    'views' => (string) (10 * $i),
                ]);
            }
        }

        DB::table('event')->insert([
            'date'         => now()->addDays(7)->format('d-m-y'),
            'time'         => '11:00 AM',
            'event_name'   => 'Talk show: Mental health for young Chhattisgarh',
            'start_date'   => now()->addDays(7)->format('Y-m-d'),
            'end_date'     => '13:00:00',
            'description'  => '<p>A panel discussion with public health professionals and community champions.</p>',
            'location'     => 'Raipur',
            'admin'        => 'Suraj Pandey',
            'event_status' => 1,
            'event_image'  => '',
            'googlemap'    => 'https://maps.google.com/?q=Raipur,Chhattisgarh',
        ]);

        DB::table('mails')->insert([
            ['name' => 'Sita Sahu',  'email' => 'sita@example.com',  'subject' => 'Volunteering in Bilaspur', 'phone' => '9876543210', 'message' => 'How do I volunteer for upcoming talk shows?', 'status' => 0, 'date' => now()],
            ['name' => 'Mukesh Verma', 'email' => 'mukesh@example.com', 'subject' => 'Partnership enquiry', 'phone' => '9123456780', 'message' => 'Our NGO would like to partner.', 'status' => 1, 'date' => now()->subDay()],
        ]);

        DB::table('membership')->insert([
            [
                'name' => 'Sita Sahu', 'mobile' => '9876543210', 'email' => 'sita@example.com',
                'area' => 'Mental Health,Adolescent', 'address' => 'Civil Lines, Raipur',
                'block' => '1', 'district' => '1',
                'fb' => '', 'insta' => '', 'twitter' => '', 'youtube' => '', 'website' => '',
                'ngo_organization' => '', 'org_intro' => '', 'img' => '',
                'type' => 'Volunteer', 'code' => 'ABC-' . rand(1000, 9999), 'date' => now(),
            ],
            [
                'name' => 'Prerna Foundation', 'mobile' => '9988776655', 'email' => 'contact@prerna.org',
                'area' => 'Education,Nutrition', 'address' => 'Sector 4, Bilaspur',
                'block' => '5', 'district' => '2',
                'fb' => 'https://facebook.com/prerna', 'insta' => '', 'twitter' => '', 'youtube' => '', 'website' => 'https://prerna.org',
                'ngo_organization' => 'Prerna Foundation', 'org_intro' => 'Working on education and nutrition for last-mile children.', 'img' => '',
                'type' => 'CSO/NGO', 'code' => 'ABC-' . rand(1000, 9999), 'date' => now(),
            ],
        ]);

        $learningCatRow = DB::table('learning_cat')->first();
        if ($learningCatRow && $admin) {
            DB::table('learning_corner')->insert([
                'cat_id'  => $learningCatRow->id,
                'title'   => 'Pocket guide to nutrition',
                'content' => 'A printable pocket guide for ASHA workers covering nutrition basics.',
                'admin'   => 'Suraj Pandey',
                'user_id' => $admin->id,
                'image'   => '',
                'm_type'  => 'book',
                'link'    => 'https://drive.google.com',
                'date'    => now(),
            ]);
        }

        $this->call(PageContentSeeder::class);
        $this->call(ReportsSeeder::class);
        $this->call(StoryArchiveSeeder::class);
        $this->call(SbcPoolMemberSeeder::class);
    }
}
