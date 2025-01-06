<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Showtime extends Model
{
    /** @use HasFactory<\Database\Factories\ShowtimeFactory> */
    use HasFactory;

    protected $fillable = [
        'movie_id',
        'room_id',
        'date',
        'start_time',
        'end_time',
    ];

    protected $casts = [
        'date' => 'date:Y-m-d',
        'start_time' => 'date:H:i:s',
        'end_time' => 'date:H:i:s',
    ];

    public function movie(): BelongsTo
    {
        return $this->belongsTo(Movie::class);
    }

    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }
}
