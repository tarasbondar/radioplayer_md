<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

/**
 * @OA\Schema(title="ChangePasswordRequest", type="object")
 */
class ChangePasswordRequest extends FormRequestBase
{
    /**
     * @OA\Property(property="password_old", title="Old password", example="12345678")
     * @OA\Property(property="password_new", title="New password", example="12345")
     * @OA\Property(property="password_new_repeat", title="New password repeat", example="12345")
     */
    public function rules(): array
    {
        return [
            'password_old' => ['required',
                function ($attribute, $value, $fail) {
                    if (!Hash::check($value, Auth::user()->password)) {
                        $fail('Incorrect password');
                    }
                },
            ],
            'password_new' => 'required|min:6|confirmed',
            'password_new_confirmation' => 'required',
        ];
    }

}
