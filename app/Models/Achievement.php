<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Achievement extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'category',
        'award_level',
        'date_achieved',
        'certificate_file',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'date_achieved' => 'date',
        ];
    }

    /**
     * Get the student (user) who owns the achievement.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
