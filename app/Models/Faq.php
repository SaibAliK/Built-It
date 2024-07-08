<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Faq extends Model
{
    protected $dateFormat = 'U';
    protected $hidden = [
        'updated_at'
    ];

    protected $casts = [
        'created_at' => 'int',
        'updated_at' => 'int',
        'question' => 'array',
        'answer' => 'array',
    ];

    protected $fillable = ['id',
        'question->en', 'question->ar', 'answer->en', 'answer->ar'
    ];

    public function getQuestionAttribute()
    {
        if (isset($this->attributes['question'])) {
            if (is_array($this->attributes['question'])) {
                return $this->attributes['question'];
            } else {
                return json_decode($this->attributes['question'], true);
            }
        } else {
            return null;
        }
    }

    public function getAnswerAttribute()
    {
        if (isset($this->attributes['answer'])) {
            if (is_array($this->attributes['answer'])) {
                return $this->attributes['answer'];
            } else {
                return json_decode($this->attributes['answer'], true);
            }
        } else {
            return null;
        }
    }
}
