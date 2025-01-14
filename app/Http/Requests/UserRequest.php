<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class UserRequest extends FormRequest
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
            'name' => ['required','min:3','max:50','regex:/^[a-zA-Z0-9_]+$/ui','unique:users,name'],
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $errors = $validator->errors();
        
        if ($errors->has('name') && $errors->first('name') === 'The name has already been taken.') {
            throw new HttpResponseException(response()->json([
                'error' => 'Пользователь с таким именем уже существует.'
            ], 409));
        }

        throw new HttpResponseException(response()->json([
            'error' => 'Некорректные параметры запроса.',
        ], 400));
    }
}
