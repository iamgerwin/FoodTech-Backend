<?php

namespace App\Jobs;

use App\Models\Driver;
use Filament\Notifications\Notification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class ImportDriversJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected string $filePath;
    protected $user;
    protected int $batchSize = 500;

    /**
     * Create a new job instance.
     */
    public function __construct(string $filePath, $user = null)
    {
        $this->filePath = $filePath;
        $this->user = $user;
    }

    /**
     * Handle job failure.
     */
    public function failed(\Throwable $exception)
    {
        Log::error('[Driver Import] Job failed', ['exception' => $exception]);
        if ($this->user) {
            Notification::make()
                ->title('Driver Import Failed')
                ->body('Import failed: ' . $exception->getMessage())
                ->danger()
                ->sendToDatabase($this->user);
        }
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        $importedCount = 0;
        $path = storage_path('app/public/import/uploads/' . basename($this->filePath));
        $extension = strtolower(pathinfo($path, PATHINFO_EXTENSION));
        try {
            if ($extension === 'csv') {
                $importedCount = $this->importFromCsv($path);
            } elseif (in_array($extension, ['xlsx', 'xls'])) {
                $importedCount = $this->importFromExcel($path);
            } else {
                throw new \Exception('Unsupported file type for import: ' . $extension);
            }
            $skippedCount = property_exists($this, 'skippedCount') ? $this->skippedCount : 0;
            if ($this->user) {
                $body = "Successfully imported {$importedCount} drivers.";
                if ($skippedCount > 0) {
                    $body .= " {$skippedCount} duplicate records were skipped.";
                }
                // Send Filament notification
                Notification::make()
                    ->title('Driver Import Complete')
                    ->body($body)
                    ->success()
                    ->sendToDatabase($this->user);

                // Also create a custom notification record for real-time popups
                $notificationData = [
                    'actions' => [],
                    'body' => $body,
                    'color' => null,
                    'duration' => 'persistent',
                    'icon' => 'heroicon-o-check-circle',
                    'iconColor' => 'success',
                    'status' => 'success',
                    'title' => 'Driver Import Complete',
                    'view' => 'filament-notifications::notification',
                    'viewData' => [],
                    'format' => 'filament',
                ];
                
                // Queue the custom notification creation
                \App\Jobs\SendCustomNotificationJob::dispatch($this->user, $notificationData);
            }
            Log::info("[Driver Import] Success: {$importedCount} drivers imported, {$skippedCount} skipped");
        } catch (\Exception $e) {
            Log::error('[Driver Import] Error: ' . $e->getMessage(), [
                'file' => $this->filePath,
                'exception' => $e
            ]);
            if ($this->user) {
                Notification::make()
                    ->title('Driver Import Failed')
                    ->body('Error importing drivers: ' . $e->getMessage())
                    ->danger()
                    ->sendToDatabase($this->user);
            }
            throw $e; // re-throw for failed() method
        } finally {
            if (Storage::disk('public')->exists($this->filePath)) {
                Storage::disk('public')->delete($this->filePath);
            }
        }
    }

    protected function importFromCsv($path): int
    {
        if (!file_exists($path)) {
            throw new \Exception('CSV file not found: ' . $path);
        }
        $handle = fopen($path, 'r');
        if ($handle === false) {
            throw new \Exception('Could not open CSV file: ' . $path);
        }
        $header = fgetcsv($handle);
        if (!$header) {
            fclose($handle);
            throw new \Exception('CSV header missing or invalid.');
        }
        $rows = [];
        $importedCount = 0;
        $skippedCount = 0;
        try {
            while (($row = fgetcsv($handle)) !== false) {
                $data = array_combine($header, $row);
                if ($data) {
                    $data['id'] = (string) Str::uuid();
                    $rows[] = $data;
                }
                if (count($rows) >= $this->batchSize) {
                    $inserted = Driver::insertOrIgnore($rows);
                    $importedCount += $inserted;
                    $skippedCount += count($rows) - $inserted;
                    $rows = [];
                }
            }
            if ($rows) {
                $inserted = Driver::insertOrIgnore($rows);
                $importedCount += $inserted;
                $skippedCount += count($rows) - $inserted;
            }
            // Save skipped count for notification
            $this->skippedCount = $skippedCount;
            return $importedCount;
        } finally {
            fclose($handle);
        }
    }

    protected function importFromExcel($path): int
    {
        try {
            $collections = Excel::toCollection(null, $path);
            if ($collections->isEmpty()) {
                throw new \Exception('Excel file is empty or invalid.');
            }
            $rows = [];
            $header = [];
            $importedCount = 0;
            $skippedCount = 0;
            foreach ($collections as $sheet) {
                foreach ($sheet as $i => $row) {
                    if ($i === 0) {
                        $header = $row->toArray();
                        continue;
                    }
                    $data = array_combine($header, $row->toArray());
                    if ($data) {
                        $data['id'] = (string) Str::uuid();
                        $rows[] = $data;
                    }
                    if (count($rows) >= $this->batchSize) {
                        $inserted = Driver::insertOrIgnore($rows);
                        $importedCount += $inserted;
                        $skippedCount += count($rows) - $inserted;
                        $rows = [];
                    }
                }
            }
            if ($rows) {
                $inserted = Driver::insertOrIgnore($rows);
                $importedCount += $inserted;
                $skippedCount += count($rows) - $inserted;
            }
            $this->skippedCount = $skippedCount;
            return $importedCount;
        } catch (\Exception $e) {
            throw new \Exception('Error processing Excel file: ' . $e->getMessage());
        }
    }
}
