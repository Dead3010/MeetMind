<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Meeting extends Model
{
    protected $fillable = [
        'title', 'summary', 'topic', 'priority', 'priority_reason',
        'action_items', 'key_decisions', 'sentiment', 'duration_estimate',
        'transcript', 'date', 'preview_html',
        'conclusion', 'conversation_flow', 'speaker_sentiments', 'mom',
    ];

    protected $casts = [
        'action_items'      => 'array',
        'key_decisions'     => 'array',
        'conversation_flow' => 'array',
        'speaker_sentiments'=> 'array',
        'mom'               => 'array',
        'date'              => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
