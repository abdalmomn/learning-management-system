<?php

namespace App\Http\Requests\Course;

use App\Http\Responses\Response;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class UpdateCourseRequest extends FormRequest
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
    //make validation for updating course for special subject
    public function rules(): array
    {
        return [
            'subject_id' =>[],
            'name' =>[],
            'content' =>[],
            'poster' =>['image'],
            'hour' =>[],
            'requirements' =>[],
            'price' =>[],
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new ValidationException($validator, Response::Validation([],$validator->errors()));
    }
}
