<?php

namespace App\Http\Controllers;

use App\Models\PrizeCode;
use App\Models\TicketBatch;
use App\Models\TicketOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TicketOrderController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'ticket_batch_id' => 'required|exists:ticket_batches,id',
            'quantity' => 'required|integer|min:1|max:10',
            'buyer_name' => 'required|string|max:255',
            'buyer_phone' => 'required|string|max:20',
        ]);

        $order = DB::transaction(function () use ($validated) {
            $batch = TicketBatch::lockForUpdate()->findOrFail($validated['ticket_batch_id']);

            if ($batch->seats_remaining < $validated['quantity']) {
                abort(422, 'Not enough seats remaining. Available: ' . $batch->seats_remaining);
            }

            $batch->decrement('seats_remaining', $validated['quantity']);

            $totalPrice = $batch->price * $validated['quantity'];

            $order = TicketOrder::create([
                'user_id' => auth()->id(),
                'ticket_batch_id' => $validated['ticket_batch_id'],
                'quantity' => $validated['quantity'],
                'buyer_name' => $validated['buyer_name'],
                'buyer_phone' => $validated['buyer_phone'],
                'total_price' => $totalPrice,
                'status' => 'pending',
            ]);

            PrizeCode::issue(auth()->id(), 'ticket', $order->id);

            return $order;
        });

        return redirect()->route('tickets.show', $order->batch->match_id)
            ->with('success', 'Ticket order placed successfully! Order #' . $order->id . ' — keep an eye on your Prize Code in the confirmation.');
    }
}
