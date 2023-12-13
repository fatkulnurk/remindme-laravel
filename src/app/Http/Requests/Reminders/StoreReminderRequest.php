<?php

namespace App\Http\Requests\Reminders;

use Illuminate\Foundation\Http\FormRequest;

class StoreReminderRequest extends FormRequest
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
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:40000'],
            'remind_at' => ['required', 'numeric', 'min:1'],
            'event_at' => ['required', 'numeric', 'min:1', 'gte:remind_at'],
        ];
    }

    public function messages()
    {
        return [
            'event_at.gte' => 'The event at must be greater than or equal to the remind at.',
        ];
    }
}
