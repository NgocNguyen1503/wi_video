<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    use HasFactory;

    protected $table = "videos";

    protected $fillable = [
        'video_url',
        'caption',
        'author_id',
    ];

    public function likes()
    {
        return $this->hasMany(Like::class, 'video_id', 'id');
    }

    public function author()
    {
        return $this->hasOne(User::class, 'id', 'author_id');
    }
}
