<?php

declare(strict_types=1);

namespace Laniakea\Tests\Workbench\Exceptions;

use Illuminate\Foundation\Http\FormRequest;

class FakeValidationExceptionRequest extends FormRequest
{
    public function authorize(): true
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'message' => 'required|string',
        ];
    }
}
