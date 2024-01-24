<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ComputingResource extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'port',
        'password',
        'host',
        'login',
        'public_key',
        'private_key',
        'template_type',
//        'calculations_folder'
    ];

}
