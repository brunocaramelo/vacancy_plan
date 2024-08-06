<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

use Carbon\Carbon;

use Illuminate\Support\Collection;
class StoreHolidayFormRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'title' => 'required|string',
            'date' => 'required|date|unique:holiday_plans,date',
            'description' => 'required|string',
            'location' => 'required|string',

            'participants.*.name' => 'required|string',
            'participants.*.last_name' => 'required|string',
            'participants.*.email' => 'required|email',
        ];
    }

    public function messages()
    {
        return [
            'title.required' => 'Title is required',
            'title.string' => 'Title must be string',
            'description.required' => 'Description is required',
            'description.string' => 'Description must be string',
            'location.required' => 'Location is required',
            'location.string' => 'Location must be string',
            'date.required' => 'Date is required',
            'date.date' => 'Date must be valid Date',
            'date.unique' => 'Date must be Unique',
            'participants.*.name.required' => 'Participant name is required',
            'participants.*.name.string' => 'Participant name must be string',
            'participants.*.last_name.required' => 'Participant name is required',
            'participants.*.last_name.string' => 'Participant name must be string',
            'participants.*.email.required' => 'Participant name is required',
            'participants.*.email.email' => 'Participant name must be valid email',


        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'errors'      => $validator->errors()
        ], 422));
    }

}
