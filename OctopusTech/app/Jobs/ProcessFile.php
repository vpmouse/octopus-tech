<?php

namespace App\Jobs;

use App\Models\BatchFile;
use Exception;
use Illuminate\Bus\Batchable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class ProcessFile implements ShouldQueue
{
    use Queueable, Batchable;

    protected BatchFile $file;

    public function __construct(BatchFile $file)
    {
        $this->file = $file;
    }

    public function handle(): void
    {
        try {
            $this->file->update(['status' => BatchFile::STATUS_PROCESSING]);

            sleep(2);

            $this->file->update([
                'status' => BatchFile::STATUS_COMPLETED,
                'processed_path' => $this->file->original_path
            ]);
        } catch (Exception $e) {
            $this->file->update([
                'status' => BatchFile::STATUS_FAILED,
                'error_message' => $e->getMessage()
            ]);
        }
    }
}
