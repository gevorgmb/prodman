<?php

declare(strict_types=1);

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
            'quantityAvailable' => 'sometimes|required|numeric|min:0',
            'expirationDate' => 'sometimes|required|date',
            'min' => 'sometimes|numeric|min:0',
            'unit' => 'sometimes|string|max:50',
        ];
    }
}
