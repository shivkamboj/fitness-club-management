<?php

namespace App\Http\Requests\GymOwner;

use App\Models\Trainer;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreTrainerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isGymOwner() ?? false;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'first_name' => ['required', 'string', 'min:2', 'max:100'],
            'last_name' => ['required', 'string', 'min:2', 'max:100'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'phone' => [
                'required',
                'string',
                'max:30',
                Rule::unique('users', 'phone'),
            ],
            'gender' => ['nullable', Rule::in(['male', 'female', 'other'])],
            'dob' => ['nullable', 'date', 'before:today'],
            'joining_date' => ['nullable', 'date'],
            'specialization' => ['nullable', 'string', 'max:1000'],
            'experience' => ['nullable', 'integer', 'min:0', 'max:60'],
            'certifications' => ['nullable', 'string', 'max:5000'],
            'skills' => ['nullable', 'string', 'max:5000'],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'password_confirmation' => ['nullable', 'string'],
            'status' => ['nullable', Rule::in([Trainer::STATUS_ACTIVE, Trainer::STATUS_INACTIVE])],
            'profile_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'background_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'email.unique' => 'This email is already registered.',
            'phone.unique' => 'This phone number is already registered.',
            'profile_image.max' => 'Profile image must not exceed 2MB.',
            'background_image.max' => 'Background image must not exceed 5MB.',
        ];
    }

    protected function prepareForValidation(): void
    {
        if ($this->input('password') === '') {
            $this->merge([
                'password' => null,
                'password_confirmation' => null,
            ]);
        }
    }
}
