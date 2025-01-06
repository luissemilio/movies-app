<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Enums\RatingMovie;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\{HasMany, BelongsToMany, BelongsTo};
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Movie extends Model implements HasMedia
{
    /** @use HasFactory<\Database\Factories\MovieFactory> */
    use HasFactory, InteractsWithMedia;

    protected $fillable = [
        'title',
        'description',
        'category_id',
        'release_date',
        'expiration_date',
        'duration',
        'rating',
    ];

    protected $casts = [
        'release_date' => 'date:Y-m-d',
        'expiration_date' => 'date:Y-m-d',
        'duration' => 'integer',
        'rating' => RatingMovie::class,
    ];

    public function showtimes(): HasMany
    {
        return $this->hasMany(Showtime::class);
    }

    public function actors() : BelongsToMany
    {
        return $this->belongsToMany(Actor::class, 'movie_actor');
    }

    public function category() : BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('cover')
            ->singleFile();

        $this->addMediaCollection('images');
    }

    protected function cover(): Attribute
    {
        return Attribute::get(function () {
            return $this->getFirstMediaUrl('cover');
        });
    }
}
