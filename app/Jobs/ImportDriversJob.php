<?php

namespace App\Jobs;

use App\Models\Driver;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Collection;

class ImportDriversJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected string $filePath;

    /**
     * Create a new job instance.
     */
    public function __construct(string $filePath)
    {
        $this->filePath = $filePath;
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        $path = storage_path('app/' . $this->filePath);
        $extension = pathinfo($path, PATHINFO_EXTENSION);
        if ($extension === 'csv') {
            $this->importFromCsv($path);
        } elseif (in_array($extension, ['xlsx', 'xls'])) {
            $this->importFromExcel($path);
        } else {
            Log::error('Unsupported file type for import: ' . $extension);
        }
        // Optionally delete file
        Storage::delete($this->filePath);
    }

    protected function importFromCsv($path)
    {
        if (!file_exists($path)) {
            Log::error('CSV file not found: ' . $path);
            return;
        }
        $handle = fopen($path, 'r');
        if ($handle === false) {
            Log::error('Could not open CSV file: ' . $path);
            return;
        }
        $header = fgetcsv($handle);
        if (!$header) {
            Log::error('CSV header missing or invalid.');
            fclose($handle);
            return;
        }
        $rows = [];
        while (($row = fgetcsv($handle)) !== false) {
            $data = array_combine($header, $row);
            if ($data) {
                $rows[] = $data;
            }
            if (count($rows) >= 500) {
                Driver::insert($rows);
                $rows = [];
            }
        }
        if ($rows) {
            Driver::insert($rows);
        }
        fclose($handle);
    }

    protected function importFromExcel($path)
    {
        try {
            $collections = Excel::toCollection(null, $path);
            if ($collections->isEmpty()) {
                Log::error('Excel file is empty or invalid.');
                return;
            }
            $rows = [];
            $header = [];
            foreach ($collections as $sheet) {
                foreach ($sheet as $i => $row) {
                    if ($i === 0) {
                        $header = $row->toArray();
                        continue;
                    }
                    $data = array_combine($header, $row->toArray());
                    if ($data) {
                        $rows[] = $data;
                    }
                    if (count($rows) >= 500) {
                        Driver::insert($rows);
                        $rows = [];
                    }
                }
            }
            if ($rows) {
                Driver::insert($rows);
            }
        } catch (\Throwable $e) {
            Log::error('Excel import failed: ' . $e->getMessage());
        }
    }
}

