<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Session extends Model
{
    use HasFactory;
    protected $fillable = [
        'UserProject_id',
        'StartTime',
        'EndTime',
        'SessionTime',
        'ActiveTime',
        'SuccessfulRuns',
        'ErrorRuns',
        'MeanTimeToFixError',
        'CodingPeriod'
    ];
    
    protected $casts = [
        'StartTime' => 'datetime',
        'EndTime' => 'datetime'
    ];


    public function  userproject()
    {
        return $this->belongsTo( UserProject::class,'UserProject_id');
    }

}
