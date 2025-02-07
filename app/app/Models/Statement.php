<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @OA\Schema(
 *     schema="Statement",
 *     type="object",
 *     required={"id", "user_id", "title"},
 *     @OA\Property(property="id", type="integer"),
 *     @OA\Property(property="user_id", type="integer"),
 *     @OA\Property(property="number", type="number", format="float"),
 *     @OA\Property(property="title", type="string"),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time"),
 * )
 */
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
