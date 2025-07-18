<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Candidates extends Model
{
    //
    protected $table = 'candidates';

    // Fillable fields for mass assignment
    protected $fillable = [
        'roll_no',
        'name',
        'dob',
        'indos_no',
        'passport_no',
        'cdc_no',
        'dgs_certificate_no',
        'course_detail_id',
        'photo_path',
        'signature_path',
        'passport_path',
    ];

    // If you're using date fields like 'dob', you can cast them
    protected $casts = [
        'dob' => 'date',
    ];

    public function marks()
{
    return $this->hasMany(Marks::class, 'candidate_id'); // NOT candidates_id
}

    public function courseDetail()
    {
        return $this->belongsTo(CourseDetail::class);
    }
}
