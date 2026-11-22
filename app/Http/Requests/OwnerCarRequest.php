<?php

namespace App\Http\Requests;

use Illuminate\Support\Arr;

/** Same car rules as the broker, minus the fields only the broker controls */
class OwnerCarRequest extends CarRequest
{
    public function authorize(): bool
    {
        $user = $this->user();

        return $user && ! $user->is_admin;
    }

    public function rules(): array
    {
        return Arr::except(parent::rules(), ['status', 'is_featured', 'seller_name', 'seller_phone']);
    }
}
