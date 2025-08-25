<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    protected $table = 'questions';

    protected $fillable = [
        'text',
        'image',
        'option1',
        'option2',
        'option3',
        'option4',
        'right_option',
        'quiz_id',
        'duration',
    ];

    public function quiz()
    {
        return $this->belongsTo(Quiz::class);
    }

    // Accessor to get options as an array
    public function getOptionsAttribute()
    {
        // Only return non-null options
        return array_filter([
            $this->option1,
            $this->option2,
            $this->option3,
            $this->option4
        ]);
    }
}
