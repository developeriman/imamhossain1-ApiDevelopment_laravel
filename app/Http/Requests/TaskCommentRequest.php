<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TaskCommentRequest extends FormRequest
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
            'task_id'=>'required',
            'user_id'=>'required',
            'comment'=>'nullable|string|max:255',
            'file' => 'nullable|file|sometimes| mimes:jpg,jpeg,bmp,png,doc,docx,csv,rtf,xlsx,xls,txt,pdf,zip,mpeg,ogg,mp4,webm,3gp,mov,flv,avi,wmv,ts|max:50000'
        ];
    }
}
