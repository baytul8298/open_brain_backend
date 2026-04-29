<?php

namespace App\Models\Core;

use App\Models\Learn\Course;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model {
    protected $table = 'core.subjects';
    public $timestamps = false;

    protected $fillable = [
        'name','slug','name_bn','icon','color','parent_id','sort_order','is_active'
    ];

    protected $casts = ['is_active' => 'boolean'];

    public function parent() {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function children() {
        return $this->hasMany(self::class, 'parent_id');
    }

    public function courses() {
        return $this->hasMany(Course::class, 'subject_id');
    }
}
