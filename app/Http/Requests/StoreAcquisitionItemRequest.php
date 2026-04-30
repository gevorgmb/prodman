<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAcquisitionItemRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'productId' => 'nullable|integer|exists:products,id',
            'productName' => 'required|string|max:255',
            'description' => 'nullable|string',
            'expirationDate' => 'nullable|date|after_or_equal:today',
            'quantity' => 'required|numeric|min:0',
            'price' => 'required|numeric|min:0',
        ];
    }
}
