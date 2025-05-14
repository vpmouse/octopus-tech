<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Batch extends Model
{
    public const string STATUS_PENDING = 'pending';
    public const string STATUS_PROCESSING = 'processing';
    public const string STATUS_COMPLETED = 'completed';
    public const string STATUS_FAILED = 'failed';
    public const string STATUS_PARTIAL = 'partial';

    protected $fillable = [
        'status'
    ];

    public function files(): HasMany
    {
        return $this->hasMany(BatchFile::class);
    }
}
