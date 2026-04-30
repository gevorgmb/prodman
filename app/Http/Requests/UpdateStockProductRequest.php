<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateStockProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'itemId' => 'sometimes|required|integer|exists:acquisition_items,id',
            'productName' => 'sometimes|required|string|max:255',
            'quantity' => 'sometimes|required|numeric|min:0',
            'quantityUsed' => 'sometimes|required|numeric|min:0',
            'expirationDate' => 'sometimes|required|date',
        ];
    }
}
