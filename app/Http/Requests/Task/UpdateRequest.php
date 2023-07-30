<?php

namespace App\Http\Requests\Task;

use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string' ,
            'content' => 'required|string',
            'tags' => 'required|array',
            'list' => 'required|string',
            'due_date' => 'required|date|after_or_equal:' . Carbon::now()->addSeconds(10)->format('Y-m-d H:i:s'),
        ];
    }
}
