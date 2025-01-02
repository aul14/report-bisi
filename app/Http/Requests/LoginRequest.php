<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Factory as ValidationFactory;

class LoginRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'UserName' => 'required',
            'Password' => 'required'
        ];
    }

    public function getCredentials()
    {
        // The form field for providing UserName or Password
        // have name of "UserName", however, in order to support
        // logging users in with both (UserName and Email)
        // we have to check if user has entered one or another
        $UserName = $this->get('UserName');

        if ($this->isEmail($UserName)) {
            return [
                'Email' => $UserName,
                'Password' => $this->get('Password')
            ];
        }

        return $this->only('UserName', 'Password');
    }

    private function isEmail($param)
    {
        $factory = $this->container->make(ValidationFactory::class);

        return !$factory->make(
            ['UserName' => $param],
            ['UserName' => 'Email']
        )->fails();
    }
}
