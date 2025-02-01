<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreTeacherRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255', // Ensures that the name is required, a string, and does not exceed 255 characters
            'email' => 'required|email|unique:teachers,email', // Ensures that the email is required, is a valid email, and is unique in the teachers table
            'phone' => 'required|string|max:15', // Ensures that the phone is required and has a maximum of 15 characters
            'academy_id' => 'required|exists:academies,id', // Ensures that the academy_id is required and must exist in the academies table

            // Course fields
            'course_title' => 'required|string|max:255',
            'course_description' => 'nullable|string',
        ];
    }
}
