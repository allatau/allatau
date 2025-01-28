<?php

namespace App\Models;

use App\Enums\TaskStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Dyrynda\Database\Support\GeneratesUuid;
use Nette\Utils\ArrayList;

class Task extends Model
{
    use HasFactory;
    use \App\Http\Traits\UsesUuid;

    protected $fillable = [
        'name',
        'script',
        'files',
        'status',
        'computational_model_resource',
        'type',
        'extra',
        'numerical_model',
        'converter_service',
        'jobs',
        'calculation_case_id',
        'meta_values'
    ];

    /**
     * Write code on Method
     *
     * @return response()
     */
//    protected $casts = [
//        'status' => TaskStatus::class
//    ];

    protected $casts = [
        'jobs' => 'json',
//        'converter_service' => 'json',
    ];

    public function computing_resource(): BelongsTo
    {
        return $this->belongsTo(ComputingResource::class);
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function calculation_case(): BelongsTo
    {
        return $this->belongsTo(CalculationCase::class, 'calculation_case_id');
    }
}
