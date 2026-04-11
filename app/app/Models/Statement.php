<?php

declare(strict_types=1);

namespace App\Models;

use app\Enums\StatementStatus;
use App\Traits\HasObservers;
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
    use HasObservers;

    protected $fillable = [
        'title',
        'user_id',
        'number',
        'date',
        'status',
        'resource_id',
        'approved_by',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'status' => StatementStatus::class,
    ];

    public function users(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function approve(int $resourceId, int $actorId): void
    {
        $this->resource_id = $resourceId;
        $this->approved_by = $actorId;
        $this->status = StatementStatus::APPROVED->value;
        $this->save();
        $this->fireEvent('approved');
    }

    public function submit(): void
    {
        $this->status = StatementStatus::SUBMITTED->value;
        $this->save();
        $this->fireEvent('submitted');
    }

    public function reject(): void
    {
        $this->status = StatementStatus::REJECTED->value;
        $this->save();
        $this->fireEvent('rejected');
    }
}
