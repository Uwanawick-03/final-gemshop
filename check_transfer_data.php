<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "Checking item transfers data:\n";
$transfers = App\Models\ItemTransfer::with('item')->take(5)->get();

foreach($transfers as $t) {
    echo "ID: " . $t->id . 
         ", Status: " . ($t->status ?? 'NULL') . 
         ", Reason: " . ($t->reason ?? 'NULL') . 
         ", Item: " . ($t->item->name ?? 'No item') . "\n";
}

echo "\nTotal transfers: " . App\Models\ItemTransfer::count() . "\n";
