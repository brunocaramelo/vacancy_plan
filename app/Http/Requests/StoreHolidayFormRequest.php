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
            'date' => 'required|date',
            'description' => 'required|string',
            'location' => 'required|string',

            'participants' => 'required',
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
            'date.string' => 'Date must be valid Date',
            'participants.*.name.required' => 'Participant name is required',
            'participants.*.name.string' => 'Participant name must be string',
            'participants.*.last_name.required' => 'Participant name is required',
            'participants.*.last_name.string' => 'Participant name must be string',
            'participants.*.email.required' => 'Participant name is required',
            'participants.*.email.email' => 'Participant name must be valid email',


        ];
    }


    public function withValidator($validator)
    {
        $validator->after(function ($validator) {

            if(count($validator->errors()) > 0) {
                return $validator;
            }

            $sortedCollection = collect($validator->safe()->horarios)->map(function ($item) {
                $item['dsHorarioInicioTimezone'] = Carbon::createFromFormat('H:i', $item['dsHorarioInicio']);
                return $item;
            })->sortBy(function ($item) {
                return $item['dsHorarioInicioTimezone'];
            })->toArray();

            $prevItens = collect([]);

            foreach ($sortedCollection as $indiceReg => $registro) {

                $dataInicio = Carbon::parse($registro['dsHorarioInicio']);
                $dataFim = Carbon::parse($registro['dsHorarioFinal']);

                if($indiceReg > 0) {
                    $prevItens->add($sortedCollection[$indiceReg -1]);
                }

                if($this->verificaColisao($prevItens, $dataInicio, $dataFim)) {
                    $validator->errors()->add('horarios.'.$indiceReg.'.conflito', 'Conflito de range '.$registro['dsHorarioInicio'].' ate '.$registro['dsHorarioFinal'].' na lista');
                }

            }

            return $validator;
        });

    }

    private function verificaColisao(Collection $horarios, Carbon $inicioNovo, Carbon $fimNovo)
    {
            foreach ($horarios as $horario) {

                $dataInicioCarbon = Carbon::parse($horario['dsHorarioInicio']);
                $dataFimCarbon = Carbon::parse($horario['dsHorarioFinal']);

                if (
                    $inicioNovo->between($dataInicioCarbon, $dataFimCarbon) ||
                    $fimNovo->between($dataInicioCarbon, $dataFimCarbon) ||
                    $dataInicioCarbon->between($inicioNovo, $fimNovo) ||
                    $dataFimCarbon->between($inicioNovo, $fimNovo)
                ) {
                    return true;
                }
            }

            return false;
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'errors'      => $validator->errors()
        ], 422));
    }

}
