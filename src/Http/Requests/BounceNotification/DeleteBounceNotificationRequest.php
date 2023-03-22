<?php

namespace Luchavez\AwsSesBounce\Http\Requests\BounceNotification;

use Luchavez\StarterKit\Requests\FormRequest;

/**
 * Class DeleteBounceNotificationRequest
 *
 * @author James Carlo Luchavez <jamescarloluchavez@gmail.com>
 */
class DeleteBounceNotificationRequest extends FormRequest
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
            //
        ];
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            //
        ]);
    }
}
