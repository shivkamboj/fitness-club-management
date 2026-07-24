<?php

namespace App\Http\Requests\Auth;

use App\Services\SocialAuthService;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class SocialProviderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Merge the route provider into the request for validation.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'provider' => $this->route('provider'),
        ]);
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'provider' => [
                'required',
                'string',
                Rule::in(SocialAuthService::SUPPORTED_PROVIDERS),
            ],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'provider.in' => 'Unsupported social login provider.',
            'provider.required' => 'A social login provider is required.',
        ];
    }

    /**
     * Redirect guests back to login with a friendly flash error.
     */
    protected function failedValidation(Validator $validator): void
    {
        throw new HttpResponseException(
            redirect()
                ->route('login')
                ->with('error', $validator->errors()->first('provider') ?: 'Unsupported social login provider.')
        );
    }
}
