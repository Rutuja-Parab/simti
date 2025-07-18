<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Marks extends Model
{
    //
    protected $fillable = [
        'candidate_id',
        'subject_id',
        'marks_obtained',
        'status',
        'approved_by',
        'approved_at',
        'rejection_reason',
        'last_edited_by',
        'term', // Added for term-wise marks
    ];

    public function candidate()
    {
        return $this->belongsTo(Candidates::class, 'candidate_id');
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function lastEditor()
    {
        return $this->belongsTo(User::class, 'last_edited_by');
    }
}
