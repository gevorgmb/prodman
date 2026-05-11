<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreStockProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'itemId' => 'required|integer|exists:acquisition_items,id',
            'productName' => 'required|string|max:255',
            'quantityAvailable' => 'required|numeric|min:0',
            'expirationDate' => 'required|date',
            'min' => 'sometimes|numeric|min:0',
            'unit' => 'sometimes|string|max:50',
        ];
    }
}
