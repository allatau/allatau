<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CalculationCase extends Model
{
    use HasFactory;
    use \App\Http\Traits\UsesUuid;

    protected $fillable = [
        'name',
    ];

    public function file(): BelongsTo
    {
        return $this->belongsTo(File::class);
    }
}
