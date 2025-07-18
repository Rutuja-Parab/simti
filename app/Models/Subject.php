<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    protected $fillable = ['course_id', 'subject_code', 'name', 'max_marks', 'passing_marks'];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}
