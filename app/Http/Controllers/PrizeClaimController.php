<?php

namespace App\Http\Controllers;

use App\Models\PrizeCode;
use Illuminate\Http\Request;

class PrizeClaimController extends Controller
{
    public function claim(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|min:4|max:16',
        ]);

        $code = PrizeCode::where('code', strtoupper(trim($validated['code'])))->first();

        if (! $code) {
            return response()->json([
                'status' => 'error',
                'heading' => 'Invalid Code',
                'message' => 'The code you entered was not found. Double-check and try again.',
            ]);
        }

        if ($code->status === 'claimed') {
            return response()->json([
                'status' => 'claimed',
                'heading' => 'Already Claimed',
                'message' => 'This code has already been claimed. Please contact our support team if this is a mistake.',
            ]);
        }

        if ($code->status === 'won') {
            $code->update([
                'status' => 'claimed',
                'claimed_at' => now(),
            ]);

            return response()->json([
                'status' => 'won',
                'heading' => 'Congratulations!',
                'message' => 'Your prize has been claimed successfully.',
                'prize' => $code->prize,
            ]);
        }

        return response()->json([
            'status' => 'pending',
            'heading' => 'Not a Winner Yet',
            'message' => 'This code is still active. The prize draw has not been conducted yet — stay tuned!',
        ]);
    }
}