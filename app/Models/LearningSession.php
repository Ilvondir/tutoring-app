<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class LearningSession extends Model
{
    /**
     * @var string
     */
    public $table = 'learning_sessions';

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
}
