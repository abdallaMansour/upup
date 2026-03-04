<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\StoragePlatform;
use Illuminate\Http\Request;

class StoragePlatformController extends Controller
{
    public function index()
    {
        $platforms = StoragePlatform::withCount(['storageConnections'])
            ->orderBy('provider')
            ->get();

        return view('dashboard.storage-platforms.index', compact('platforms'));
    }

    public function update(Request $request, StoragePlatform $storagePlatform)
    {
        $request->validate([
            'is_active' => ['required', 'boolean'],
        ]);

        $isActive = (bool) $request->is_active;

        if (! $isActive) {
            $connectionsCount = $storagePlatform->storageConnections()->count();
            if ($connectionsCount > 0) {
                return redirect()->route('dashboard.storage-platforms.index')
                    ->with('error', "لا يمكن تعطيل المنصة لأن {$connectionsCount} مستخدم مرتبط بها. يجب إلغاء ربط جميع المستخدمين أولاً.");
            }
        }

        $storagePlatform->update(['is_active' => $isActive]);

        $message = $isActive ? 'تم تفعيل المنصة بنجاح.' : 'تم تعطيل المنصة بنجاح.';

        return redirect()->route('dashboard.storage-platforms.index')
            ->with('success', $message);
    }
}
