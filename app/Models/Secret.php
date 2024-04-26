<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Secret extends Model
{
    use HasFactory;

    protected $attributes = [
        'ttl' => 0,
        'passphrase' => null,
    ];

    protected $fillable = [
        'ttl',
        'secret',
        'passphrase',
    ];
}
