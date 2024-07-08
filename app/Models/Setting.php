<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $dateFormat = 'U';
    public static $snakeAttributes = false;

    protected $fillable = [
        "privacy_policy",
        "terms_and_conditions",
        "contact_us",
        "mission_and_vision",
        "about_us",
        "user_data_delete",
        "address",
        "latitude",
        "longitude",
        "email_header",
        "company_name",
        "contact_number",
        "date-format",
        "email",
        "facebook_url",
        "instagram_url",
        "twitter_url",
        "android_app",
        "ios_app",
        "date-format",
        "value_added_tax",
        "aed_to_usd",
        "commission",
        "revenue_stream",
    ];

    protected $casts=[
        'footer_text'=>'array'
    ];
}
