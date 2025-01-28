<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WebWidget extends Model
{
    use HasFactory;
    use \App\Http\Traits\UsesUuid;

    protected $fillable = [
        'name',
        'resource',
//        'type',
    ];
}
