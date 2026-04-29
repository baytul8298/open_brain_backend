<?php

namespace App\Models\Core;

use Illuminate\Database\Eloquent\Model;

class TeacherVerification extends Model
{
    protected $table = 'core.teacher_verifications';

    public $timestamps = false;

    protected $fillable = [
        'user_id', 'doc_type', 'doc_url', 'status',
        'reviewed_by', 'reviewer_notes', 'reviewed_at',
    ];

    protected $casts = [
        'reviewed_at'  => 'datetime',
        'submitted_at' => 'datetime',
    ];
}
