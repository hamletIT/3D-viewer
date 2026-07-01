<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FileType extends Model
{
    protected $fillable = ['user_id', 'original_name', 'file_path', 'mime_type', 'file_size', 'model_name', 'session_id'];

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
