<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Statement extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'user_id',
        'number',
        'date',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    /**
     * @return BelongsTo<User, Statement>
     */
    public function users(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
