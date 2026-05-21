<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProgramsInsightsSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('programs')->insert([
            [
                'title'      => 'Bapi Na Uwat',
                'tag'        => 'Dantewada · Nutrition & Health',
                'short_desc' => 'Bapi Na Uwat is an innovative community-led SBC initiative launched in Dantewada by the district administration and UNICEF to reduce malnutrition and improve health behaviours in tribal communities.',
                'full_desc'  => "The initiative uses trusted elderly women, known as \"Bapis,\" to spread awareness on nutrition, breastfeeding, maternal care, and child health through local traditions and conversations. Implemented across 143 gram panchayats, the programme combines traditional wisdom with behaviour change communication tools such as local language videos, chaupals, and community engagement activities.\n\nSupported by village volunteers and frontline workers, the campaign has helped strengthen trust, awareness, and community participation around health and nutrition practices in remote areas of Bastar.",
                'card_style' => 'featured',
                'sort_order' => 1,
                'status'     => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title'      => 'Yuvoday',
                'tag'        => 'Youth movement',
                'short_desc' => 'Yuvoday is a youth-led volunteer movement launched in Chhattisgarh with support from district administrations and UNICEF to strengthen community participation and behaviour change. Meaning "Rise of the Youth," the initiative has built a network of over 12,000 volunteers who work across villages, urban wards, and tribal communities.',
                'full_desc'  => 'Yuvoday volunteers support campaigns related to health, nutrition, sanitation, education, mental health, social protection, and COVID-19 awareness. By connecting youth with communities and frontline workers, the programme promotes local leadership, trust-building, and people-centered development.',
                'card_style' => 'default',
                'sort_order' => 2,
                'status'     => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title'      => 'BijaDuteer',
                'tag'        => 'Bijapur · Youth messengers',
                'short_desc' => 'BijaDuteer is a youth volunteer initiative in Bijapur supported by the District Administration, UNICEF, and Chhattisgarh Agricon Samiti. In the local dialect, "Bija" refers to Bijapur and "Duteer" means messenger, representing a youth brigade working as community messengers for positive change.',
                'full_desc'  => 'In this naxal-affected and remote district, BijaDuteer volunteers help bridge the gap between communities and governance by promoting awareness on health, nutrition, education, child rights, mental wellbeing, and positive parenting.',
                'card_style' => 'teal',
                'sort_order' => 3,
                'status'     => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title'      => 'JAY HO!',
                'tag'        => 'Jashpur · Youth leadership',
                'short_desc' => 'JAY HO, the Jashpur Alliance of Youth for Hope and Opportunity, is a youth empowerment initiative in Jashpur launched by the District Administration and UNICEF to support adolescent wellbeing and positive behaviour change.',
                'full_desc'  => 'The initiative focuses on adolescent health, life skills, online safety, safe migration, mental wellbeing, anaemia, child marriage, and substance abuse through youth-led engagement, peer learning, and community participation.',
                'card_style' => 'ochre',
                'sort_order' => 4,
                'status'     => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title'      => 'Learning Corners',
                'tag'        => 'Knowledge sharing',
                'short_desc' => 'The Learning Corner is a shared resource space under Alliance for Behaviour Change where IEC materials, training modules, toolkits, campaign resources, and communication materials are made accessible for learning, knowledge sharing, community mobilization, and awareness generation.',
                'full_desc'  => 'The platform encourages individuals and organizations to freely explore, download, and use these resources to strengthen Social and Behaviour Change Communication efforts across communities.',
                'card_style' => 'leaf',
                'sort_order' => 5,
                'status'     => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        DB::table('insights')->insert([
            [
                'title'      => '12,000+ Youth Volunteers Mobilised',
                'tag'        => 'Community Impact',
                'description' => 'Yuvoday has mobilised over 12,000 youth volunteers across Chhattisgarh who actively drive behaviour change in health, nutrition, sanitation, and education across villages, urban wards, and tribal communities.',
                'image'      => '',
                'link_text'  => 'Learn about Yuvoday',
                'link_url'   => '',
                'sort_order' => 1,
                'status'     => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title'      => '143 Gram Panchayats Covered in Dantewada',
                'tag'        => 'Reach & Coverage',
                'description' => 'Bapi Na Uwat has reached 143 gram panchayats in Dantewada, using trusted elderly women ("Bapis") to spread awareness on nutrition, breastfeeding, and maternal care in remote tribal communities.',
                'image'      => '',
                'link_text'  => 'Read the programme story',
                'link_url'   => '',
                'sort_order' => 2,
                'status'     => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title'      => 'SBC Resources for Frontline Workers',
                'tag'        => 'Knowledge Hub',
                'description' => 'The ABC Learning Corner provides IEC materials, training modules, toolkits, and campaign resources to frontline health workers and community volunteers across all districts of Chhattisgarh.',
                'image'      => '',
                'link_text'  => 'Explore Learning Corner',
                'link_url'   => '/learning-corner',
                'sort_order' => 3,
                'status'     => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title'      => 'Community-led Health in Bastar Region',
                'tag'        => 'Tribal Outreach',
                'description' => 'Focused SBC initiatives in the Bastar division are helping bridge the gap between remote tribal communities and essential health services through local language communication, traditional knowledge, and community trust.',
                'image'      => '',
                'link_text'  => 'View programmes',
                'link_url'   => '',
                'sort_order' => 4,
                'status'     => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
