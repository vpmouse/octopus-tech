<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateBatchRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'files' => 'required|array',
            'files.*.file' => 'required|file|mimes:jpg,jpeg,png',
            'files.*.operation' => 'required|string',
            'files.*.parameters' => 'required|json',
        ];
    }
}
