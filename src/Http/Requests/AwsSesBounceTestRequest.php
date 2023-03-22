<?php

namespace Luchavez\AwsSesBounce\Http\Requests;

use Luchavez\StarterKit\Requests\FormRequest;

/**
 * Class AwsSesBounceTestRequest
 *
 * @author James Carlo Luchavez <jamescarloluchavez@gmail.com>
 */
class AwsSesBounceTestRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'to' => 'required',
            'cc' => 'nullable',
            'bcc' => 'nullable',
            'message' => 'nullable',
        ];
    }
}
