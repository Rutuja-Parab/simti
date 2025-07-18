<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CourseDetail extends Model
{
    protected $fillable = [
        'course_id', 'batch_no', 'start_date', 'end_date'
    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function candidates()
    {
        return $this->hasMany(\App\Models\Candidates::class, 'course_detail_id');
    }
} 