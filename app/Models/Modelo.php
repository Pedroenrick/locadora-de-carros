<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Modelo extends Model
{
    use HasFactory;

    protected $fillable = [
        'marca_id',
        'nome',
        'imagem',
        'numero_portas',
        'lugares',
        'ano_fabricacao',
        'air_bag',
        'abs'
    ];

    public function rules()
    {
        return [
            'marca_id' => 'exists:marcas,id',
            'nome' => "required|max:100|unique:modelos|min:3",
            'imagem' => 'required|file|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'numero_portas' => 'required|integer|digits_between:1,5',
            'lugares' => 'required|integer|digits_between:1,20',
            'km_rodados' => 'required|integer|digits_between:0,150000',
            'ano_fabricacao' => 'required|integer|after:2015|before:2021',
            'air_bag' => 'required|boolean',
            'abs' => 'required|boolean'
        ];
    }

    public function feedback()
    {
        return [
            'required' => 'O campo :attribute é obrigatório',
            'nome.string' => 'O nome da marca deve ser uma string',
            'imagem.mimes' => 'A imagem pode ter as extensões: png,jpeg,jpg,gif,svg',
            'nome.unique' => 'Já existe uma marca com esse nome',
            'nome.min' => 'O nome deve ter no mínimo 3 caracteres',
            'nome.max' => 'O nome deve ter no menos de 100 caracteres'
        ];
    }

    public function marca()
    {
        return $this->belongsTo(Marca::class);
    }
}
