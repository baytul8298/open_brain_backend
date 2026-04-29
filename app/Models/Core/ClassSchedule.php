<?php
namespace App\Models\Core;

use Illuminate\Database\Eloquent\Model;

class ClassSchedule extends Model
{
    protected $table = 'core.class_schedule';
    protected $keyType = 'string';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'class_id', 'day_of_week', 'start_time', 'end_time',
        'timezone', 'is_active', 'effective_from', 'effective_until',
    ];

    protected $casts = [
        'is_active'      => 'boolean',
        'effective_from' => 'date',
        'effective_until'=> 'date',
    ];

    public function classRoom() { return $this->belongsTo(ClassRoom::class, 'class_id', 'id'); }
}
