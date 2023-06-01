<?php

namespace App\Http\Requests;

use App\Models\Bid;
use Illuminate\Validation\Rule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;

class UserBidRequest extends BaseApiRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'user_id' => 'required|integer|exists:users,id',
            'price' => [
                'required',
                'regex:/^\d+(\.\d{2})?$/',
                function ($attribute, $value, $fail) {
                    $currentHighestPrice = Bid::max('price');
                    if ($value <= $currentHighestPrice) {
                        $currentHighestPrice + 1;
                        $fail("The bid price cannot lower than {$currentHighestPrice}");
                    }
                },
            ],
        ];
    }

    public function messages()
    {
        return [
            'user_id.required' => 'User id is required',
            'user_id.integer' => 'User id must be integer',
            'user_id.exists' => 'User id does not exists',
            'price.required' => 'The bid price is required!',
            'price.regex' => 'The price format is invalid.',
        ];
    }

    
}
