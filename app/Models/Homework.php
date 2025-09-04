<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Homework extends Model
{
    /** @use HasFactory<\Database\Factories\HomeworkFactory> */
    use HasFactory, SoftDeletes;

    /**
     * @var string
     */
    public $table = 'homeworks';

    /**
     * @var bool
     */
    public $timestamps = true;

    /**
     * @var array
     */
    public $guarded = [];

    /**
     * @param $date
     * @return string
     */
    public function getCreatedAtAttribute($date): string
    {
        return Carbon::parse($date)->timezone('Europe/Warsaw')->format('Y-m-d H:i:s');
    }


    /**
     * @param $date
     * @return string
     */
    public function getUpdatedAtAttribute($date): string
    {
        return Carbon::parse($date)->timezone('Europe/Warsaw')->format('Y-m-d H:i:s');
    }

    /**
     * @param $date
     * @return string|null
     */
    public function getDeletedAtAttribute($date): string|null
    {
        if ($date)
            return Carbon::parse($date)->timezone('Europe/Warsaw')->format('Y-m-d H:i:s');
        else
            return null;
    }

    /**
     * @param $date
     * @return string|null
     */
    public function getCompleteDateAttribute($date): string|null
    {
        if ($date)
            return Carbon::parse($date)->timezone('Europe/Warsaw')->format('Y-m-d H:i:s');
        else
            return null;
    }

    /**
     * @return BelongsTo
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    /**
     * @return BelongsTo
     */
    public function teacher(): BelongsTo
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    /**
     * @return HasMany
     */
    public function exercises(): HasMany
    {
        return $this->hasMany(Exercise::class)->orderBy('order');
    }
}
