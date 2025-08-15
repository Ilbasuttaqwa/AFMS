<?php
require_once 'vendor/autoload.php';

// Load Laravel environment
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;

echo "=== MEMPERBAIKI NAMA USER ===\n";

$users = User::all();
echo "Total users: " . $users->count() . "\n\n";

foreach ($users as $user) {
    echo "User ID {$user->id}: ";
    
    if (empty($user->nama_pegawai)) {
        // Generate nama berdasarkan role dan ID
        $newName = match($user->role) {
            'admin' => "Admin " . $user->id,
            'manager' => "Manager " . $user->id,
            'karyawan' => "Karyawan " . $user->id,
            default => "User " . $user->id
        };
        
        $user->update(['nama_pegawai' => $newName]);
        echo "Nama kosong, diupdate menjadi: {$newName}\n";
    } else {
        echo "Nama sudah ada: {$user->nama_pegawai}\n";
    }
}

echo "\n=== HASIL SETELAH UPDATE ===\n";
$updatedUsers = User::all();
foreach ($updatedUsers as $user) {
    echo "ID: {$user->id} | Nama: {$user->nama_pegawai} | Name Accessor: {$user->name} | Role: {$user->role}\n";
}

echo "\n✅ Semua user sudah memiliki nama!\n";
?>