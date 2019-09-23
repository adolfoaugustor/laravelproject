<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    protected $fillable = [
        'nome',
        'image',
        'cpf_cnpj'
    ];

    public function rules()
    {
        return [
            'nome' => 'required',
            'image'=> 'image',
            'cpf_cnpj' =>'required|unique:clientes'
        ];
    }
}
