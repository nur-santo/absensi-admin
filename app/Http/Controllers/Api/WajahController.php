<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Wajah;

class WajahController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'data' => ['required', 'array'],
        ]);

        Wajah::updateOrCreate(
            ['user_id' => $request->user()->id],
            ['data' => array_values($validated['data'])]
        );

        return response()->json([
            'message' => 'Data wajah berhasil disimpan'
        ]);
    }

    public function check(Request $request)
    {
        $exists = Wajah::where('user_id', $request->user()->id)->exists();

        return response()->json([
            'has_wajah' => $exists
        ]);
    }
}
