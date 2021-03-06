<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Marca extends Model
{
    use HasFactory;

    protected $fillable = [
        'nome',
        'imagem'
    ];

    public function rules()
    {
        return [
            'nome' => "required|max:100|unique:marcas|min:3",
            'imagem' => 'required|file|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ];
        //image|mimes:jpeg,png,jpg,gif,svg|max:2048
    }

    public function feedback(){
        return [
            'required' => 'O campo :attribute é obrigatório',
            'nome.string' => 'O nome da marca deve ser uma string',
            'imagem.mimes' => 'A imagem pode ter as extensões: png,jpeg,jpg,gif,svg',
            'nome.unique' => 'Já existe uma marca com esse nome',
            'nome.min' => 'O nome deve ter no mínimo 3 caracteres',
            'nome.max' => 'O nome deve ter no menos de 100 caracteres'
        ];
    }

    public function modelos(){
        return $this->hasMany(Modelo::class);
    }

}
