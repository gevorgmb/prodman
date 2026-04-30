<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreArchivedAcquisitionItemRequest extends FormRequest
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
            'quantity' => 'required|numeric|min:0',
            'quantityUsed' => 'required|numeric|min:0',
            'expirationDate' => 'required|date',
            'archiveDate' => 'required|date',
        ];
    }
}
