<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTopupOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'product_id' => 'required|exists:topup_products,id',
            'denomination_id' => 'required|exists:topup_denominations,id',
            'user_id' => 'required|string|max:255',
            'zone_id' => 'nullable|string|max:255',
        ];
    }
}
