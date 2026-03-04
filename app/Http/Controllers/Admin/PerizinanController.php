<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Perizinan;
use Illuminate\Http\Request;

class PerizinanController extends Controller
{
    /**
     * Tampilkan daftar perizinan
     */
    public function index()
    {
        // 🔔 Tandai notifikasi sebagai sudah dibaca
        if (auth('admin')->check()) {
            auth('admin')->user()->unreadNotifications->markAsRead();
        }

        $perizinan = Perizinan::with('user')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.perizinan.index', compact('perizinan'));
    }

    /**
     * Detail perizinan
     */
    public function show($id)
    {
        $perizinan = Perizinan::with('user')->findOrFail($id);

        return view('admin.perizinan.show', compact('perizinan'));
    }

    /**
     * Approve perizinan
     */
    public function approve($id)
    {
        $perizinan = Perizinan::findOrFail($id);

        $perizinan->update([
            'status' => 'APPROVED',
            'approved_by' => auth('admin')->id(),
            'approved_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Perizinan disetujui');
    }

    /**
     * Reject perizinan
     */
    public function reject($id)
    {
        $perizinan = Perizinan::findOrFail($id);

        $perizinan->update([
            'status' => 'REJECTED',
            'approved_by' => auth('admin')->id(),
            'approved_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Perizinan ditolak');
    }
}