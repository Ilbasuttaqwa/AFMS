<?php

require_once 'vendor/autoload.php';

// Load Laravel application
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

// Create a request for the attendance data
$request = Illuminate\Http\Request::create('/admin/absensi/data', 'GET', [
    'month' => 8,
    'year' => 2025,
    'cabang' => ''
]);

// Set up authentication (simulate logged in user)
$user = App\Models\User::where('role', 'manager')->first();
if (!$user) {
    $user = App\Models\User::first();
}

if ($user) {
    auth()->login($user);
    echo "=== DEBUGGING AJAX RESPONSE ===\n";
    echo "Authenticated as: {$user->nama_pegawai} (Role: {$user->role})\n";
    echo "Request parameters: month=8, year=2025, cabang=''\n\n";
    
    // Create controller instance
    $controller = new App\Http\Controllers\AbsensiController(
        new App\Services\FingerprintService()
    );
    
    try {
        $response = $controller->getAttendanceData($request);
        $data = $response->getData(true);
        
        echo "=== RESPONSE DATA ===\n";
        echo "Number of employees: " . count($data) . "\n\n";
        
        if (count($data) > 0) {
            $firstEmployee = $data[0];
            echo "First employee data:\n";
            echo "- ID: " . $firstEmployee['id'] . "\n";
            echo "- Name: " . $firstEmployee['nama'] . "\n";
            echo "- Branch: " . $firstEmployee['cabang'] . "\n";
            echo "- Position: " . $firstEmployee['golongan'] . "\n";
            echo "- Device User ID: " . ($firstEmployee['device_user_id'] ?? 'null') . "\n\n";
            
            echo "Attendance data for first few days:\n";
            for ($day = 8; $day <= 12; $day++) {
                if (isset($firstEmployee['attendance'][$day])) {
                    $att = $firstEmployee['attendance'][$day];
                    echo "Day $day ({$att['date']}):\n";
                    echo "  - jam_masuk: " . ($att['jam_masuk'] ?? 'null') . "\n";
                    echo "  - jam_masuk_sore: " . ($att['jam_masuk_sore'] ?? 'null') . "\n";
                    echo "  - status: " . ($att['status'] ?? 'null') . "\n";
                    echo "  - keterangan: " . ($att['keterangan'] ?? 'null') . "\n";
                    echo "  - is_weekend: " . ($att['is_weekend'] ? 'true' : 'false') . "\n";
                    echo "  - is_holiday: " . ($att['is_holiday'] ? 'true' : 'false') . "\n";
                    echo "  - source: " . ($att['source'] ?? 'null') . "\n\n";
                }
            }
        } else {
            echo "No employee data found!\n";
        }
        
        // Check raw attendance data
        echo "=== RAW ATTENDANCE DATA CHECK ===\n";
        $attendanceCount = App\Models\Absensi::whereBetween('tanggal_absen', ['2025-08-01', '2025-08-31'])->count();
        echo "Total attendance records for August 2025: $attendanceCount\n";
        
        $sampleAttendance = App\Models\Absensi::whereBetween('tanggal_absen', ['2025-08-08', '2025-08-15'])
            ->with('user')
            ->take(5)
            ->get();
            
        echo "Sample attendance records:\n";
        foreach ($sampleAttendance as $att) {
            echo "- User: {$att->user->nama_pegawai}, Date: {$att->tanggal_absen}, Time: {$att->jam_masuk}, Note: {$att->note}\n";
        }
        
    } catch (Exception $e) {
        echo "ERROR: " . $e->getMessage() . "\n";
        echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
    }
} else {
    echo "No users found in database!\n";
}