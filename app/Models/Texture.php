<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Texture extends Model
{
    protected $fillable = ['user_id', 'name', 'file_path', 'original_name'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
