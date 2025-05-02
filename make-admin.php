<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Find the first user and make them an admin
$user = App\Models\User::first();

if ($user) {
    $user->is_admin = true;
    $user->save();
    echo "User {$user->name} ({$user->email}) is now an admin.\n";
} else {
    echo "No users found.\n";
}
