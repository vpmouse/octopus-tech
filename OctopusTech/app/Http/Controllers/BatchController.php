<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateBatchRequest;
use App\Jobs\ProcessFile;
use App\Models\Batch;
use App\Models\BatchFile;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class BatchController extends Controller
{
    public function store(CreateBatchRequest $request): JsonResponse
    {
        $batch = Batch::query()->create([
            'status' => Batch::STATUS_PENDING,
        ]);

        $jobs = [];
        foreach ($request->get('files') as $index => $params) {
            $file = $request->file('files.' . $index)['file'];
            $parameters = json_decode($params['parameters'], true);

            $path = $file->store('uploads');
            $fileModel = $batch->files()->create([
                'original_path' => $path,
                'status' => BatchFile::STATUS_PENDING,
                'operation_type' => $params['operation'],
                'operation_params' => $parameters,
            ]);

            $jobs[] = new ProcessFile($fileModel);
        }

        if (empty($jobs)) {
            $batch->update(['status' => Batch::STATUS_FAILED]);
            throw new NotFoundHttpException('Files to proceed not found');
        }

        $batch->update(['status' => Batch::STATUS_PROCESSING]);

        Bus::batch($jobs)
            ->name("Batch {$batch->id}")
            ->finally(function () use ($batch) {
                $total = $batch->files()->count();
                $failed = $batch->files()->where('status', BatchFile::STATUS_FAILED)->count();
                $completed = $batch->files()->where('status', BatchFile::STATUS_COMPLETED)->count();

                if ($failed === $total) {
                    $batch->update(['status' => Batch::STATUS_FAILED]);
                } elseif ($completed === $total) {
                    $batch->update(['status' => Batch::STATUS_COMPLETED]);
                } else {
                    $batch->update(['status' => Batch::STATUS_PARTIAL]);
                }
            })
            ->dispatch();

        return response()->json([
            'batch_id' => $batch->id,
            'status' => $batch->status
        ]);
    }

    public function show(Batch $batch): JsonResponse
    {
        $batch->load('files');

        $totalFiles = $batch->files()->count();
        $completedFiles = $batch->files()->where('status', BatchFile::STATUS_COMPLETED)->count();
        $failedFiles = $batch->files()->where('status', BatchFile::STATUS_FAILED)->count();
        $processingFiles = $batch->files()->where('status', BatchFile::STATUS_PROCESSING)->count();
        $pendingFiles = $batch->files()->where('status', BatchFile::STATUS_PENDING)->count();

        return response()->json([
            'batch' => [
                'id' => $batch->id,
                'status' => $batch->status,
                'total_files' => $totalFiles,
                'completed_files' => $completedFiles,
                'failed_files' => $failedFiles,
                'processing_files' => $processingFiles,
                'pending_files' => $pendingFiles,
                'files' => $batch->files->map(function ($file) {
                    return [
                        'id' => $file->id,
                        'status' => $file->status,
                        'original_path' => Storage::url($file->original_path),
                        'processed_path' => $file->processed_path ? Storage::url($file->processed_path) : null,
                        'error_message' => $file->error_message,
                        'operation_type' => $file->operation_type,
                        'operation_params' => $file->operation_params
                    ];
                })->all(),
            ]
        ]);
    }
}
