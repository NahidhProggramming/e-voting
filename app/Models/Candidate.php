<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Candidate extends Model
{
    protected $table = 'candidates';

    protected $fillable = [
        'candidate_number',
        'chairman_name',
        'vice_chairman_name',
        'vision',
        'mission',
        'photo',
    ];

    /**
     * Get the votes for the candidate.
     */
    public function votes(): HasMany
    {
        return $this->hasMany(Vote::class);
    }
}
