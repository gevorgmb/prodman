<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAcquisitionItemRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'productId' => 'nullable|integer|exists:products,id',
            'productName' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'expirationDate' => 'nullable|date',
            'quantity' => 'sometimes|required|numeric|min:0',
            'price' => 'sometimes|required|numeric|min:0',
        ];
    }
}
