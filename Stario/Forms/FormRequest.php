<?php

namespace Star\Forms;

use App\Http\Requests\Request;
use Illuminate\Http\JsonResponse;

abstract class FormRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return false;
    }


    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    abstract public function rules();

    public function response(array $errors)
    {
        return new JsonResponse($errors, 422);
    }
}
