<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Exercise extends Model
{
    /** @use HasFactory<\Database\Factories\ExerciseFactory> */
    use HasFactory, SoftDeletes;

    /**
     * @var string
     */
    public $table = 'exercises';

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
}
