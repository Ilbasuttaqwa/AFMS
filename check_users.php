<?php
require_once 'vendor/autoload.php';

// Load Laravel environment
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;

echo "=== DAFTAR SEMUA USER DAN ROLE ===\n\n";

$users = User::all(['id', 'nama_pegawai', 'email', 'role']);

foreach ($users as $user) {
    echo "ID: {$user->id}\n";
    echo "Nama: {$user->nama_pegawai}\n";
    echo "Email: {$user->email}\n";
    echo "Role: {$user->role}\n";
    echo "-------------------\n";
}

echo "\nTotal users: " . $users->count() . "\n";