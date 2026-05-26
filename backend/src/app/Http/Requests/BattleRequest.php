<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BattleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'pokemon_one' => ['required', 'string', 'max:100'],
            'pokemon_two' => ['required', 'string', 'max:100'],
        ];
    }

    public function messages(): array
    {
        return [
            'pokemon_one.required' => 'Informe o primeiro Pokémon.',
            'pokemon_two.required' => 'Informe o segundo Pokémon.',
            'pokemon_one.string' => 'O primeiro Pokémon deve ser um texto.',
            'pokemon_two.string' => 'O segundo Pokémon deve ser um texto.',
        ];
    }
}
