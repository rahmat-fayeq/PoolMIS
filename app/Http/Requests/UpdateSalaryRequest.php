<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSalaryRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'full_name' => ['sometimes','required','string','min:1','max:255'],
            'father_name' => ['sometimes','required','string','min:1','max:255'],
            'job' => ['sometimes','required','string','min:1','max:255'],
            'phone' => ['sometimes','required','string','min:1','max:14'],
            'amount' => ['sometimes','required','integer'],
            'submit_date' => ['sometimes','required','date','date_format:Y-m-d'],
        ];
    }
}
