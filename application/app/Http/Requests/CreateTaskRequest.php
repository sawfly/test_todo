<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class CreateTaskRequest extends Request
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
        return [
            'name' => 'required|regex:/^[A-Za-z0-9-_ ]{3,256}$/',
            'category_id' => 'exists:categories,id'
        ];
    }

    /**
     * @param array $errors
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function response(array $errors)
    {
        return response(['errors' => $errors], 422);
    }
}
