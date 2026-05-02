<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAcquisitionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'storeName' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'nullable|string|in:draft,complete',
            'items' => 'required|array',
            'items.*.productId' => 'nullable|integer|exists:products,id',
            'items.*.productName' => 'required|string|max:255',
            'items.*.description' => 'nullable|string',
            'items.*.expirationDate' => 'nullable|date|after_or_equal:today',
            'items.*.quantity' => 'required|numeric|min:0',
            'items.*.price' => 'required|numeric|min:0',
        ];
    }
}
