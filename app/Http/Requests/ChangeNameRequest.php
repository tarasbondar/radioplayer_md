<?php

namespace App\Http\Requests;

/**
 * @OA\Schema(title="ChangeNameRequest", type="object")
 */
class ChangeNameRequest extends FormRequestBase
{
    /**
     * @OA\Property(property="name", title="New Name", example="Demo account")
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:50',
        ];
    }
}
