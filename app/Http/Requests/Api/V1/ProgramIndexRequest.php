<?php

namespace App\Http\Requests\Api\V1;

use App\Enums\ProgramLevel;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProgramIndexRequest extends FormRequest
{
    /**
     * @return array<string, array<int, mixed>>
     */
    public function rules(): array
    {
        return [
            'level' => [
                'nullable',
                'string',
                Rule::in(array_map(fn (ProgramLevel $l) => $l->value, ProgramLevel::cases())),
            ],
            'faculty_id' => [
                'nullable',
                'integer',
                'exists:faculties,id',
            ],
        ];
    }
}
