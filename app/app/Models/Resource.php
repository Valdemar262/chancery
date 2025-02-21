<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Resource extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'description',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    /**
     * @return HasMany<Booking, Resource>
     */
    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }
}
