<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('settings')->insert([
                "privacy_policy" => "privacy-policy",
                "terms_and_conditions" => "terms-and-conditions",
                "contact_us" => "contact-us",
                "mission_and_vision" => "mission-and-vision",
                "about_us" => "about-us",
                "address" => "Office street, office, KSA",
                "latitude" => "24.7135517",
                "longitude" => "46.6752957",
                "email_header" => "",
                "company_name" => "Seven",
                "contact_number" => "(+966) 123 456 789",
                "email" => "info@development.com",
                "facebook_url" => "https://www.facebook.com/",
                "instagram_url" => "https://www.instagram.com/",
                "twitter_url" => "https://www.twitter.com/",
                "ios_app" => "https://www.apple.com/app-store/",
                "android_app" => "https://play.google.com/store/apps",
                "value_added_tax" => "3",
                "aed_to_usd" => "0.2666267793",
                "commission" => "10",
              ]
        );
    }
}
