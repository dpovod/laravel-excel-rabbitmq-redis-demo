<?php
declare(strict_types=1);

namespace App\Http\Requests\Base;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class ApiRequest
 * @package App\Http\Requests\Base
 */
class ApiRequest extends FormRequest
{
    /**
     * @param Validator $validator
     * @throws HttpResponseException
     */
    public function failedValidation(Validator $validator)
    {
        $content = json_encode(['success' => false, 'message' => $validator->errors()->first()]);
        throw new HttpResponseException(new Response($content, Response::HTTP_BAD_REQUEST));
    }
}
