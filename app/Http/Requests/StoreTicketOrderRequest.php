<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTicketOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'ticket_batch_id' => 'required|exists:ticket_batches,id',
            'quantity' => 'required|integer|min:1|max:10',
            'buyer_name' => 'required|string|max:255',
            'buyer_phone' => 'required|string|max:20',
        ];
    }
}
