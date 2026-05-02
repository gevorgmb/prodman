<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAcquisitionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'storeName' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'sometimes|required|string|in:draft,complete,cancelled',
            'items' => 'required|array',
            'items.*.action' => 'nullable|string|in:create,update,delete',
            'items.*.itemId' => [
                'required_if:items.*.action,update,delete',
                'nullable',
                'integer',
                'exists:acquisition_items,id',
            ],
            'items.*.productId' => 'sometimes|nullable|integer|exists:products,id',
            'items.*.productName' => [
                'required_if:items.*.action,create',
                'required_without:items.*.action',
                'sometimes',
                'string',
                'max:255',
            ],
            'items.*.description' => 'sometimes|nullable|string',
            'items.*.expirationDate' => 'sometimes|nullable|date|after_or_equal:today',
            'items.*.quantity' => [
                'required_if:items.*.action,create',
                'required_without:items.*.action',
                'sometimes',
                'numeric',
                'min:0',
            ],
            'items.*.price' => [
                'required_if:items.*.action,create',
                'required_without:items.*.action',
                'sometimes',
                'numeric',
                'min:0',
            ],
        ];
    }

    /**
     * Custom validation output to strip unwanted fields based on action.
     */
    public function validated($key = null, $default = null)
    {
        $validated = parent::validated($key, $default);

        if ($key !== null || !isset($validated['items'])) {
            return $validated;
        }

        foreach ($validated['items'] as $index => $item) {
            $action = $item['action'] ?? 'create';

            if ($action === 'delete') {
                $validated['items'][$index] = array_intersect_key($item, array_flip(['action', 'itemId']));
            } elseif ($action === 'create') {
                unset($validated['items'][$index]['itemId']);
            }
        }

        return $validated;
    }
}
