<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    // The table associated with the model (optional if it follows Laravel's naming conventions)
    protected $table = 'questions';

    // Specify the columns that are mass assignable
    protected $fillable = [
        'option1',
        'option2',
        'option3',
        'option4',
        'right_option'
    ];

    // Specify the columns that should not be mass assignable (optional)
    // protected $guarded = ['id'];
}
