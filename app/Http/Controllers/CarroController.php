<?php

namespace App\Http\Controllers;

use App\Models\Carro;
use Illuminate\Http\Request;
use App\Repositories\CarroRepository;

class CarroController extends Controller
{

    public function __construct(Carro $carro)
    {
        $this->carro = $carro;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
            $carroRepository = new CarroRepository($this->carro);

            if ($request->has('atributos_modelo')) {
                $atributos_modelo = "modelo:id," . $request->atributos_modelo;
                $carroRepository->selectAtributosRegistrosRelacionados($atributos_modelo);
            } else {
                $carroRepository->selectAtributosRegistrosRelacionados('modelo');
            }

            if ($request->has('filtro')) {
                $carroRepository->filtro($request->filtro);
            }

            if ($request->has('atributos')) {
                $carroRepository->selectAtributos($request->atributos);
            }

            return response()->json($carroRepository->getResultado(), 200);
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
        $request->validate($this->carro->rules());
        try {
            $carro = $this->carro->create([
                'modelo_id' => $request->modelo_id,
                'placa' => $request->placa,
                'disponivel' => $request->disponivel,
                'km' => $request->km,
            ]);
            return response()->json($carro, 201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Carro  $carro
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $carro = $this->carro->with('modelo')->find($id);

            if (!$carro) {
                return response()->json(['error' => 'Carro não encontrado'], 404);
            }
            return response()->json($carro, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Carro  $carro
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            $carro = $this->carro->find($id);

            if (!$carro) {
                return response()->json(['error' => 'Impossivel realizar atualização. O recurso solicitado não existe'], 404);
            }

            if ($request->method() === 'PATCH') {
                $dynamicRules = array();
                //percorrendo array de regras definidas no model

                foreach ($carro->rules() as $input => $rule) {
                    if (array_key_exists($input, $request->all())) {
                        $dynamicRules[$input] = $rule;
                    }
                }

                $request->validate($dynamicRules);
            } else {
                $request->validate($carro->rules());
            }

            $carro->fill($request->all());
            $carro->save();

            return response()->json($carro, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Carro  $carro
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $carro = $this->carro->find($id);
            if (!$carro) {
                return response()->json(['error' => 'Impossivel realizar exclusão. O recurso solicitado não existe'], 404);
            }

            $carro->delete();

            return response()->json(['msg' => 'carro removido com sucesso'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
