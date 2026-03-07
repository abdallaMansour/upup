<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('storage_connections', function (Blueprint $table) {
            $table->boolean('is_primary')->default(false)->after('is_active');
        });

        $userIds = DB::table('storage_connections')->distinct()->pluck('user_id');
        foreach ($userIds as $userId) {
            $firstId = DB::table('storage_connections')
                ->where('user_id', $userId)
                ->where('is_active', true)
                ->orderBy('created_at')
                ->value('id');
            if ($firstId) {
                DB::table('storage_connections')->where('user_id', $userId)->update(['is_primary' => false]);
                DB::table('storage_connections')->where('id', $firstId)->update(['is_primary' => true]);
            }
        }
    }

    public function down(): void
    {
        Schema::table('storage_connections', function (Blueprint $table) {
            $table->dropColumn('is_primary');
        });
    }
};
