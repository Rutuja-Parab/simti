<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    //
    protected $fillable = ['name', 'start_date', 'end_date'];
    protected $table = 'courses';

    public function subjects()
    {
        return $this->hasMany(Subject::class);
    }

    // Remove or update candidates() relationship, as Candidates now relate to CourseDetail, not Course
}
