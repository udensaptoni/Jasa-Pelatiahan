<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WebhookLog extends Model
{
    use HasFactory;

    protected $fillable = ['provider', 'external_id', 'payload', 'status'];

    protected $casts = [
        'payload' => 'array',
    ];
}
