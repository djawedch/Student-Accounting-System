<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ScholarshipAward extends Model
{
    use HasFactory;

    protected $table = 'scholarship_awards';

    protected $fillable = [
        'student_id',
        'scholarship_id',
        'grant_date',
        'end_date',
        'status',
        'paid_at',
        'reference',
    ];

    protected $casts = [
        'grant_date' => 'date',
        'end_date' => 'date',
        'paid_at' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function scholarship(): BelongsTo
    {
        return $this->belongsTo(Scholarship::class);
    }
}
