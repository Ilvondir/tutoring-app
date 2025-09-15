<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Attempt extends Model
{
    /** @use HasFactory<\Database\Factories\AttemptFactory> */
    use HasFactory;

    /**
     * @var string
     */
    public $table = 'attempts';

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
     * @return BelongsTo
     */
    public function exercise(): BelongsTo
    {
        return $this->belongsTo(Exercise::class);
    }
}
