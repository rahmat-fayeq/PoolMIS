<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSalaryRequest extends FormRequest
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
            'full_name' => ['required','string','min:1','max:255'],
            'father_name' => ['required','string','min:1','max:255'],
            'job' => ['required','string','min:1','max:255'],
            'phone' => ['required','string','min:1','max:14'],
            'amount' => ['required','integer'],
            'submit_date' => ['required','date','date_format:Y-m-d'],
        ];
    }
}
