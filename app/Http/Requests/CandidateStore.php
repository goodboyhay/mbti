<?php

namespace App\Http\Requests;

use App\Http\Requests\ApiRequestRespone;
use Illuminate\Support\Carbon;
class CandidateStore extends ApiRequestRespone
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $olderThan = Carbon::now()->subYears(18)->format('Y-m-d');
        $youngerThan = Carbon::now()->subYears(55)->format('Y-m-d');
        return [
            'name' => 'required|max:40|regex:/^([\p{L}\s]+)$/u',
            'email'=>'required|email',
            'dob'=>"required|date_format:Y-m-d|before_or_equal:$olderThan|after:$youngerThan",
            'position' => 'required|max:50',
        ];
    }
}
