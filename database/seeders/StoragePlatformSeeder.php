<?php

namespace Database\Seeders;

use App\Models\StoragePlatform;
use Illuminate\Database\Seeder;

class StoragePlatformSeeder extends Seeder
{
    public function run(): void
    {
        $providers = [
            'google_drive' => 'Google Drive',
            'wasabi' => 'Wasabi',
            'dropbox' => 'Dropbox',
            'one_drive' => 'OneDrive',
        ];

        foreach ($providers as $provider => $name) {
            StoragePlatform::firstOrCreate(
                ['provider' => $provider],
                ['name' => $name, 'is_active' => true]
            );
        }
    }
}
