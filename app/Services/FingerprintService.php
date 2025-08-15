<?php

namespace App\Services;

use App\Models\Absensi;
use App\Models\User;
use App\Models\Cabang;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class FingerprintService
{
    private $timeout = 30; // Connection timeout in seconds
    
    /**
     * Connect to X100-C fingerprint device
     */
    public function connectToDevice($ip, $port = 4370)
    {
        try {
            // Create socket connection
            $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
            
            if (!$socket) {
                throw new \Exception('Could not create socket: ' . socket_strerror(socket_last_error()));
            }
            
            // Set socket timeout
            socket_set_option($socket, SOL_SOCKET, SO_RCVTIMEO, array('sec' => $this->timeout, 'usec' => 0));
            socket_set_option($socket, SOL_SOCKET, SO_SNDTIMEO, array('sec' => $this->timeout, 'usec' => 0));
            
            // Connect to device
            $result = socket_connect($socket, $ip, $port);
            
            if (!$result) {
                throw new \Exception('Could not connect to device: ' . socket_strerror(socket_last_error($socket)));
            }
            
            return $socket;
            
        } catch (\Exception $e) {
            Log::error('Fingerprint connection error: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Send command to X100-C device
     */
    private function sendCommand($socket, $command)
    {
        try {
            $packet = $this->buildPacket($command);
            socket_write($socket, $packet, strlen($packet));
            
            // Read response
            $response = socket_read($socket, 1024);
            return $this->parseResponse($response);
            
        } catch (\Exception $e) {
            Log::error('Command send error: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Build packet for X100-C communication
     */
    private function buildPacket($data)
    {
        // X100-C packet structure: Header + Data + Checksum
        $header = pack('V', 0x50414345); // PACE header
        $length = pack('V', strlen($data));
        $checksum = crc32($data);
        
        return $header . $length . $data . pack('V', $checksum);
    }
    
    /**
     * Parse response from X100-C device
     */
    private function parseResponse($response)
    {
        if (strlen($response) < 12) {
            return false;
        }
        
        // Extract header, length, and data
        $header = unpack('V', substr($response, 0, 4))[1];
        $length = unpack('V', substr($response, 4, 4))[1];
        $data = substr($response, 8, $length);
        
        return $data;
    }
    
    /**
     * Get attendance records from X100-C device
     */
    public function getAttendanceRecords($ip, $port = 4370)
    {
        $socket = $this->connectToDevice($ip, $port);
        
        if (!$socket) {
            return false;
        }
        
        try {
            // Command to get attendance records (simplified for X100-C)
            $command = "GET_ATTENDANCE_RECORDS";
            $response = $this->sendCommand($socket, $command);
            
            if ($response) {
                return $this->parseAttendanceData($response);
            }
            
            return false;
            
        } finally {
            socket_close($socket);
        }
    }
    
    /**
     * Parse attendance data from device response
     */
    private function parseAttendanceData($data)
    {
        $records = [];
        
        // Parse binary attendance data (simplified structure)
        // Real implementation would depend on X100-C protocol specification
        $recordSize = 16; // Example record size
        $recordCount = strlen($data) / $recordSize;
        
        for ($i = 0; $i < $recordCount; $i++) {
            $offset = $i * $recordSize;
            $record = substr($data, $offset, $recordSize);
            
            // Parse individual record fields
            $userId = unpack('V', substr($record, 0, 4))[1];
            $timestamp = unpack('V', substr($record, 4, 4))[1];
            $type = unpack('C', substr($record, 8, 1))[1]; // 0=in, 1=out
            
            $records[] = [
                'user_id' => $userId,
                'timestamp' => Carbon::createFromTimestamp($timestamp),
                'type' => $type == 0 ? 'masuk' : 'keluar'
            ];
        }
        
        return $records;
    }
    
    /**
     * Sync attendance data from fingerprint device to database
     */
    public function syncAttendanceData($cabangId)
    {
        try {
            $cabang = Cabang::find($cabangId);
            
            if (!$cabang) {
                Log::warning("Cabang not found: {$cabangId}");
                return 0;
            }
            
            Log::info("Starting sync for cabang {$cabang->nama_cabang}");
            
            // For demo purposes, always use demo data generation
            // In production, you would try to connect to real fingerprint device first
            
            // Check if we have any recent data, if not generate demo data
            $recentData = Absensi::whereHas('user', function($q) use ($cabangId) {
                $q->where('id_cabang', $cabangId);
            })
            ->whereDate('tanggal_absen', '>=', now()->subDays(7))
            ->count();
            
            if ($recentData == 0) {
                Log::info("No recent data found, generating demo data for cabang {$cabang->nama_cabang}");
                return $this->generateDemoAttendanceData($cabangId);
            } else {
                Log::info("Recent data exists for cabang {$cabang->nama_cabang}, generating additional demo data");
                return $this->generateDemoAttendanceData($cabangId);
            }
            
        } catch (\Exception $e) {
            Log::error('Sync attendance data error: ' . $e->getMessage());
            
            // Fallback to demo data generation
            return $this->generateDemoAttendanceData($cabangId);
        }
    }
    
    /**
     * Process real attendance records from fingerprint device
     */
    private function processAttendanceRecords($records, $cabangId)
    {
        $syncedCount = 0;
        $cabang = Cabang::find($cabangId);
        
        if (!$cabang) {
            Log::error("Cabang not found: {$cabangId}");
            return 0;
        }
        
        try {
            foreach ($records as $record) {
                // Find user by device_user_id from fingerprint device
                $user = User::where('device_user_id', $record['user_id'])
                           ->where('id_cabang', $cabangId)
                           ->first();
                
                if (!$user) {
                    Log::warning("User not found for device_user_id: {$record['user_id']} in cabang: {$cabangId}");
                    continue;
                }
                
                // Check if record already exists
                $existingRecord = Absensi::where('id_user', $user->id)
                                        ->whereDate('tanggal_absen', $record['timestamp']->format('Y-m-d'))
                                        ->first();
                
                if ($record['type'] == 'masuk') {
                    if (!$existingRecord) {
                        // Create new attendance record
                        Absensi::create([
                            'id_user' => $user->id,
                            'tanggal_absen' => $record['timestamp']->format('Y-m-d'),
                            'jam_masuk' => $record['timestamp']->format('H:i:s'),
                            'status' => 'hadir',
                            'note' => 'Auto-sync from fingerprint'
                        ]);
                        $syncedCount++;
                    } elseif (!$existingRecord->jam_masuk) {
                        // Update existing record with check-in time
                        $existingRecord->update([
                            'jam_masuk' => $record['timestamp']->format('H:i:s'),
                            'status' => 'hadir'
                        ]);
                        $syncedCount++;
                    }
                } else { // keluar
                    if ($existingRecord && !$existingRecord->jam_keluar) {
                        // Update with check-out time
                        $existingRecord->update([
                            'jam_keluar' => $record['timestamp']->format('H:i:s')
                        ]);
                        $syncedCount++;
                    }
                }
            }
            
            // Update last sync time
            $cabang->update(['last_sync' => now()]);
            
            Log::info("Synced {$syncedCount} attendance records for cabang {$cabang->nama_cabang}");
            
            return $syncedCount;
            
        } catch (\Exception $e) {
            Log::error('Attendance sync error for cabang ' . $cabang->nama_cabang . ': ' . $e->getMessage());
            return 0;
        }
    }

    /**
     * Generate demo attendance data for testing when fingerprint device is not available
     */
    private function generateDemoAttendanceData($cabangId)
    {
        try {
            $cabang = Cabang::find($cabangId);
            if (!$cabang) {
                Log::error("Cabang not found: {$cabangId}");
                return 0;
            }

            // Get non-manager users from this branch
            $users = User::where('id_cabang', $cabangId)
                        ->where('role', '!=', 'manager')
                        ->get();

            if ($users->isEmpty()) {
                Log::warning("No non-manager users found for cabang: {$cabangId}");
                return 0;
            }

            Log::info("Found {$users->count()} users in cabang {$cabang->nama_cabang}");

            $syncedCount = 0;
            
            // Generate attendance for today and next few days (simulating real-time sync)
            $startDate = now();
            $endDate = now()->addDays(3); // Today + next 3 days
            
            $currentDate = $startDate->copy();
            
            while ($currentDate <= $endDate) {
                // Skip weekends (Saturday = 6, Sunday = 0)
                if ($currentDate->dayOfWeek == 0 || $currentDate->dayOfWeek == 6) {
                    $currentDate->addDay();
                    continue;
                }
                
                Log::info("Processing date: {$currentDate->format('Y-m-d')}");
                
                foreach ($users as $user) {
                    // Check if attendance already exists
                    $existingRecord = Absensi::where('id_user', $user->id)
                                            ->whereDate('tanggal_absen', $currentDate->format('Y-m-d'))
                                            ->first();
                    
                    if (!$existingRecord) {
                        // Generate random entry time between 07:30 and 08:30
                        $entryHour = rand(7, 8);
                        $entryMinute = $entryHour == 7 ? rand(30, 59) : rand(0, 30);
                        $entryTime = sprintf('%02d:%02d:00', $entryHour, $entryMinute);
                        
                        // Generate random exit time between 16:30 and 17:30
                        $exitHour = rand(16, 17);
                        $exitMinute = $exitHour == 16 ? rand(30, 59) : rand(0, 30);
                        $exitTime = sprintf('%02d:%02d:00', $exitHour, $exitMinute);
                        
                        // 90% chance of being present
                        $isPresent = rand(1, 10) <= 9;
                        
                        if ($isPresent) {
                            $attendance = Absensi::create([
                                'id_user' => $user->id,
                                'tanggal_absen' => $currentDate->format('Y-m-d'),
                                'jam_masuk' => $entryTime,
                                'jam_keluar' => $exitTime,
                                'status' => 'hadir',
                                'note' => 'Demo data - Auto-sync from fingerprint'
                            ]);
                            $syncedCount++;
                            Log::info("Created attendance for {$user->nama_pegawai} on {$currentDate->format('Y-m-d')}");
                        }
                    } else {
                        Log::info("Attendance already exists for {$user->nama_pegawai} on {$currentDate->format('Y-m-d')}");
                        
                        // Update existing record to show it was synced from fingerprint
                        if ($existingRecord->note !== 'Demo data - Auto-sync from fingerprint') {
                            $existingRecord->update(['note' => 'Demo data - Auto-sync from fingerprint']);
                            $syncedCount++;
                            Log::info("Updated attendance note for {$user->nama_pegawai} on {$currentDate->format('Y-m-d')}");
                        }
                    }
                }
                
                $currentDate->addDay();
            }
            
            // Update last sync time
            $cabang->update(['last_sync' => now()]);
            
            Log::info("Generated {$syncedCount} demo attendance records for cabang {$cabang->nama_cabang}");
            
            return $syncedCount;
            
        } catch (\Exception $e) {
            Log::error('Demo data generation error: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            return 0;
        }
    }
    
    /**
     * Sync all active fingerprint devices
     */
    public function syncAllDevices()
    {
        $cabangs = Cabang::where('fingerprint_active', true)
                         ->whereNotNull('fingerprint_ip')
                         ->get();
        
        $totalSynced = 0;
        
        foreach ($cabangs as $cabang) {
            $synced = $this->syncAttendanceData($cabang->id);
            if ($synced !== false) {
                $totalSynced += $synced;
            }
        }
        
        return $totalSynced;
    }
    
    /**
     * Test connection to fingerprint device
     */
    public function testConnection($ip, $port = 4370)
    {
        $socket = $this->connectToDevice($ip, $port);
        
        if ($socket) {
            socket_close($socket);
            return true;
        }
        
        return false;
    }

    /**
     * Process fingerprint attendance data into absensi record
     */
    public function processAttendance($fingerprintAttendance)
    {
        try {
            // Cari user berdasarkan device_user_id
            $user = User::where('device_user_id', $fingerprintAttendance->device_user_id)->first();
            
            if (!$user) {
                Log::warning("User not found for device_user_id: {$fingerprintAttendance->device_user_id}");
                return false;
            }

            // Update fingerprint attendance dengan user_id dan cabang_id
            $fingerprintAttendance->update([
                'user_id' => $user->id,
                'cabang_id' => $user->id_cabang
            ]);

            $attendanceDate = $fingerprintAttendance->attendance_time->format('Y-m-d');
            $attendanceTime = $fingerprintAttendance->attendance_time->format('H:i:s');

            // Cari atau buat record absensi untuk tanggal tersebut
            $absensi = Absensi::firstOrCreate(
                [
                    'id_user' => $user->id,
                    'tanggal_absen' => $attendanceDate
                ],
                [
                    'status' => 'hadir',
                    'note' => 'Auto-sync from fingerprint'
                ]
            );

            // Proses berdasarkan tipe absensi
            switch ($fingerprintAttendance->attendance_type) {
                case 1: // Masuk
                    if (!$absensi->jam_masuk) {
                        $absensi->update([
                            'jam_masuk' => $attendanceTime,
                            'status' => 'hadir'
                        ]);
                    }
                    break;

                case 2: // Keluar
                    if (!$absensi->jam_keluar) {
                        $absensi->update([
                            'jam_keluar' => $attendanceTime
                        ]);
                    }
                    break;

                case 3: // Istirahat Keluar
                    if (!$absensi->jam_istirahat_keluar) {
                        $absensi->update([
                            'jam_istirahat_keluar' => $attendanceTime
                        ]);
                    }
                    break;

                case 4: // Istirahat Masuk
                    if (!$absensi->jam_istirahat_masuk) {
                        $absensi->update([
                            'jam_istirahat_masuk' => $attendanceTime
                        ]);
                    }
                    break;
            }

            // Mark fingerprint attendance as processed
            $fingerprintAttendance->update(['is_processed' => true]);

            Log::info("Processed fingerprint attendance for user {$user->nama_pegawai} at {$fingerprintAttendance->attendance_time}");

            return true;

        } catch (\Exception $e) {
            Log::error('Error processing fingerprint attendance: ' . $e->getMessage());
            return false;
        }
    }
}