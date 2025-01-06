<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Enums\ActorGender;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Actor extends Model implements HasMedia
{
    /** @use HasFactory<\Database\Factories\ActorFactory> */
    use HasFactory, InteractsWithMedia;

    protected $fillable = ['name', 'gender', 'description'];

    protected $casts = [
        'gender' => ActorGender::class,
    ];

    public function movies() : BelongsToMany
    {
        return $this->belongsToMany(Movie::class);
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('image')
            ->singleFile();
    }
}
