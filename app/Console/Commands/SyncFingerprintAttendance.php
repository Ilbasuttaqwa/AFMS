<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\FingerprintService;
use App\Models\FingerprintAttendance;
use App\Models\Cabang;

class SyncFingerprintAttendance extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fingerprint:sync {--cabang= : ID cabang tertentu untuk sync} {--all : Sync semua cabang aktif}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync data absensi dari mesin fingerprint ke database';

    protected $fingerprintService;

    public function __construct(FingerprintService $fingerprintService)
    {
        parent::__construct();
        $this->fingerprintService = $fingerprintService;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Memulai sync data fingerprint...');

        if ($this->option('all')) {
            return $this->syncAllBranches();
        }

        if ($cabangId = $this->option('cabang')) {
            return $this->syncSpecificBranch($cabangId);
        }

        // Process unprocessed fingerprint attendance records
        return $this->processUnprocessedRecords();
    }

    private function syncAllBranches()
    {
        $this->info('Sync semua cabang aktif...');
        
        $totalSynced = $this->fingerprintService->syncAllDevices();
        
        if ($totalSynced !== false) {
            $this->info("Berhasil sync {$totalSynced} record absensi dari semua cabang.");
            return 0;
        } else {
            $this->error('Gagal melakukan sync untuk beberapa cabang.');
            return 1;
        }
    }

    private function syncSpecificBranch($cabangId)
    {
        $cabang = Cabang::find($cabangId);
        
        if (!$cabang) {
            $this->error("Cabang dengan ID {$cabangId} tidak ditemukan.");
            return 1;
        }

        $this->info("Sync cabang: {$cabang->nama_cabang}");
        
        $syncedCount = $this->fingerprintService->syncAttendanceData($cabangId);
        
        if ($syncedCount !== false) {
            $this->info("Berhasil sync {$syncedCount} record absensi untuk cabang {$cabang->nama_cabang}.");
            return 0;
        } else {
            $this->error("Gagal sync data untuk cabang {$cabang->nama_cabang}.");
            return 1;
        }
    }

    private function processUnprocessedRecords()
    {
        $this->info('Memproses record fingerprint yang belum diproses...');
        
        $unprocessedRecords = FingerprintAttendance::where('is_processed', false)
                                                  ->whereNull('user_id')
                                                  ->get();

        if ($unprocessedRecords->isEmpty()) {
            $this->info('Tidak ada record yang perlu diproses.');
            return 0;
        }

        $processedCount = 0;
        $failedCount = 0;

        foreach ($unprocessedRecords as $record) {
            if ($this->fingerprintService->processAttendance($record)) {
                $processedCount++;
                $this->line("✓ Processed: Device User ID {$record->device_user_id} at {$record->attendance_time}");
            } else {
                $failedCount++;
                $this->line("✗ Failed: Device User ID {$record->device_user_id} at {$record->attendance_time}");
            }
        }

        $this->info("Selesai. Berhasil: {$processedCount}, Gagal: {$failedCount}");
        
        return $failedCount > 0 ? 1 : 0;
    }
}
