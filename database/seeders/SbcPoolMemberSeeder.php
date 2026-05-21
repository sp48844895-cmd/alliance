<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SbcPoolMemberSeeder extends Seeder
{
    public function run(): void
    {
        if (DB::table('sbc_pool_members')->count() > 0) {
            return;
        }

        $rows = [
            ['name' => 'Dilendra Chandraker', 'email' => 'dilendra88@gmail.com', 'photo' => 'Dilendra-Chandraker.jpeg'],
            ['name' => 'Mrinalini Bhamra', 'email' => 'mrinalinibhamra@gmail.com', 'photo' => 'Mrinalini-Bhamra.jpeg'],
            ['name' => 'Bhawna Pandey', 'email' => 'bhawnapandey640@gmail.com', 'photo' => 'Bhawna Pandey.jpeg'],
            ['name' => 'Vipin Thakur', 'email' => 'vviippiinn@gmail.com', 'photo' => 'Vipin-Thakur.jpeg'],
            ['name' => 'Shashank Sharma', 'email' => 'shashanksharmass02@gmail.com', 'photo' => 'Shashank Sharma.jpeg'],
            ['name' => 'Daulat Ram Kashyap', 'email' => 'ashthakawardha@gmail.com', 'photo' => 'Daulat Ram Kashyap.jpeg'],
            ['name' => 'Rajesh Baghel', 'email' => 'rajeshbaghel656@gmail.com', 'photo' => 'rajesh-baghel.jpeg'],
            ['name' => 'Rumana Khan', 'email' => 'khanrumana68@gmail.com', 'photo' => 'Rumana Khan.jpg'],
            ['name' => 'Dr. Savita Mishra', 'email' => 'smsavitamishra14@gmail.com', 'photo' => 'Savita Mishra.jpeg'],
            ['name' => 'Sharad Shrivastava', 'email' => 'srijanssrjn@gmail.com', 'photo' => 'Sharad Shrivastava.jpeg'],
            ['name' => 'Shubhi Singh', 'email' => 'shubhisingh4195@gmail.com', 'photo' => 'Shubhi Singh .jpeg'],
            ['name' => 'Danish K Hussain', 'email' => 'danish.k.2306@gmail.com', 'photo' => 'Danish K Hussain.jpeg'],
            ['name' => 'Priti Giri', 'email' => 'giripriti1@gmail.com', 'photo' => 'Priti Giri.jpeg'],
            ['name' => 'Dr. Manjiri Bakshi', 'email' => 'manjiribakshi74@yahoo.in', 'photo' => 'Dr.-Manjiri-Bakshi.jpg'],
            ['name' => 'Dr. Jagmohan Pandey', 'email' => 'dr.j.m.pandey@gmail.com', 'photo' => 'Jagmohan Pandey.jpeg'],
            ['name' => 'Rehana Tabassum', 'email' => 'cgsmnet.kanker@gmail.com', 'photo' => 'Rehana Tabassum.jpeg'],
            ['name' => 'Sudari Kashyap', 'email' => 'sudarikashyap@gmail.com', 'photo' => 'sudari-kashyap.jpeg'],
            ['name' => 'Heena Sahu', 'email' => 'heenasahu23oct@gmail.com', 'photo' => 'Heena-Sahu.jpeg'],
            ['name' => 'Animesh Roy', 'email' => 'aniimeshroy.1983@gmail.com', 'photo' => 'Animesh-Roy.jpeg'],
            ['name' => 'Khushboo Soni', 'email' => 'khushboo90981@gmail.com', 'photo' => 'khushboo soni.jpeg'],
            ['name' => 'Neelima Yadav', 'email' => 'yneeli05@gmail.com', 'photo' => 'Neelima Yadav.jpeg'],
        ];

        $insertRows = [];
        $now = now();

        foreach ($rows as $index => $row) {
            $insertRows[] = [
                'name' => $row['name'],
                'email' => $row['email'],
                'photo' => $row['photo'],
                'facebook' => null,
                'twitter' => null,
                'linkedin' => null,
                'instagram' => null,
                'sort_order' => $index + 1,
                'status' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        DB::table('sbc_pool_members')->insert($insertRows);
    }
}
