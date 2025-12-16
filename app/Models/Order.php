<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $fillable = [
        'email',
        'age',
        'full_address',
        'first_name',
        'last_name',
        'user_email',
        'user_id',
        'reports',
        'total',
        'has_social_media_report',
        'report_links',
        'is_mob',
        'is_social_media_report_sent',
    ];
    
    protected $casts = [
        'reports' => 'array',
        'report_links' => 'array',
    ];
    
}