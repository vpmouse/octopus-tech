<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BatchFile extends Model
{
    public const string STATUS_PENDING = 'pending';
    public const string STATUS_PROCESSING = 'processing';
    public const string STATUS_COMPLETED = 'completed';
    public const string STATUS_FAILED = 'failed';

    protected $fillable = [
        'batch_id',
        'original_path',
        'processed_path',
        'status',
        'operation_type',
        'operation_params',
        'error_message'
    ];

    protected $casts = [
        'operation_params' => 'array',
        'status' => 'string',
    ];

    public function batch(): BelongsTo
    {
        return $this->belongsTo(Batch::class);
    }
}
