<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AiConversation extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'title'];

    public function messages()
    {
        return $this->hasMany(AiMessage::class, 'conversation_id');
    }
}
