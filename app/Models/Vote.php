<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Vote extends Model
{
    protected $table = 'votes';

    // Enable created_at but disable updated_at
    const UPDATED_AT = null;

    protected $fillable = [
        'candidate_id',
    ];

    /**
     * Get the candidate that this vote belongs to.
     */
    public function candidate(): BelongsTo
    {
        return $this->belongsTo(Candidate::class);
    }
}
