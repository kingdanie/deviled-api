<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'uri',
        'title',
        'content',
        'user_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

     /**
     * Generate a unique URI from the title
     */
    public static function generateUniqueUri(string $title): string
    {
        $baseUri = Str::slug($title);
        $uri = $baseUri;
        $counter = 1;

        // Keep checking until we find a unique URI
        while (static::where('uri', $uri)->exists()) {
            $uri = $baseUri . '-' . $counter;
            $counter++;
        }

        return $uri;
    }
}
