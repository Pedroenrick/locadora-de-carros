<?php

namespace App\Http\Controllers;

use App\Models\Modelo;
use App\Repositories\ModeloRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ModeloController extends Controller
{
    public function __construct(Modelo $modelo)
    {
        $this->modelo = $modelo;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
            $modeloRepository = new ModeloRepository($this->modelo);

            if ($request->has('atributos_marca')) {
                $atributos_marca = "marca:id," . $request->atributos_marca;
                $modeloRepository->selectAtributosRegistrosRelacionados($atributos_marca);
            } else {
                $modeloRepository->selectAtributosRegistrosRelacionados('marca');
            }

            if ($request->has('filtro')) {
                $modeloRepository->filtro($request->filtro);
            }

            if ($request->has('atributos')) {
                $modeloRepository->selectAtributos($request->atributos);
            }

            return response()->json($modeloRepository->getResultado(), 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //método de validação//
        $request->validate($this->modelo->rules());
        try {
            $image = $request->file('imagem');
            $image_urn = $image->store('images/modelos', 'public');

            $modelo = $this->modelo->create([
                'marca_id' => $request->marca_id,
                'nome' => $request->nome,
                'imagem' => $image_urn,
                'numero_portas' => $request->numero_portas,
                'lugares' => $request->lugares,
                'km_rodados' => $request->km_rodados,
                'ano_fabricacao' => $request->ano_fabricacao,
                'air_bag' => $request->air_bag,
                'abs' => $request->abs,
            ]);
            return response()->json($modelo, 201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  Integer  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $modelo = $this->modelo->with('marca')->find($id);

            if (!$modelo) {
                return response()->json(['error' => 'modelo não encontrada'], 404);
            }
            return response()->json($modelo, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Integer  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        try {
            $modelo = $this->modelo->find($id);

            if (!$modelo) {
                return response()->json(['error' => 'Impossivel realizar atualização. O recurso solicitado não existe'], 404);
            }

            if ($request->method() === 'PATCH') {
                $dynamicRules = array();
                //percorrendo array de regras definidas no model

                foreach ($modelo->rules() as $input => $rule) {
                    if (array_key_exists($input, $request->all())) {
                        $dynamicRules[$input] = $rule;
                    }
                }

                $request->validate($dynamicRules);
            } else {
                $request->validate($modelo->rules());
            }

            //remove o arquivo antigo
            if ($request->file('imagem')) {
                Storage::disk('public')->delete($modelo->imagem);
            }

            $image = $request->file('imagem');
            $image_urn = $image->store('images/modelos', 'public');

            $modelo->fill($request->all());
            $modelo->imagem = $image_urn;

            $modelo->save();

            return response()->json($modelo, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Integer  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $modelo = $this->modelo->find($id);
            if (!$modelo) {
                return response()->json(['error' => 'Impossivel realizar exclusão. O recurso solicitado não existe'], 404);
            }


            Storage::disk('public')->delete($modelo->imagem);

            $modelo->delete();

            return response()->json(['msg' => 'modelo removida com sucesso'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
