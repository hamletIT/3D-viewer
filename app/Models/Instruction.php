<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Instruction extends Model
{
    protected $fillable = ['title', 'content', 'image_path', 'sort_order', 'active'];
}
