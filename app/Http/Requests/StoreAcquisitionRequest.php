<?php

declare(strict_types=1);

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
            'currencyId' => 'nullable|integer|exists:currencies,id',
            'items' => 'required_if:status,complete|array',
            'items.*.productId' => 'nullable|integer|exists:products,id',
            'items.*.productName' => 'required|string|max:255',
            'items.*.description' => 'nullable|string',
            'items.*.expirationDate' => 'nullable|date|after_or_equal:today',
            'items.*.quantity' => 'required|numeric|min:0',
            'items.*.price' => 'required|numeric|min:0',
            'items.*.unit' => 'sometimes|string|max:50',
        ];
    }

    public function messages(): array
    {
        return [
            'items.required_if' => 'Acquisition must have at least one item so it can be completed.',
        ];
    }
}
