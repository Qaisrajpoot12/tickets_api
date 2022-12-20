<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class eventic_organizer extends Model
{
    use HasFactory;





    protected $table = 'eventic_organizer';


   protected $fillable = [
    "country_id",
    "name",
    "slug",
    "description",
    "website",
    "email",
    "phone",
    "facebook",
    "twitter",
    "instagram",
    "googleplus",
    "linkedin",
    "youtubeurl",
    "logo_name",
    "logo_size",
    "logo_mime_type",
    "logo_original_name",
    "logo_dimensions",
    "cover_name",
    "cover_size",
    "cover_mime_type",
    "cover_original_name",
    "cover_dimensions",
    "allow_tap_to_check_in_on_scanner_app",
    "show_event_date_stats_on_scanner_app",
    "created_at",
   ];

}
