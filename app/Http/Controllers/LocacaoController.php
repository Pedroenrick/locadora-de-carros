<?php

namespace App\Http\Controllers;

use App\Models\Locacao;
use Illuminate\Http\Request;
use App\Repositories\LocacaoRepository;

class LocacaoController extends Controller
{

    public function __construct(Locacao $locacao)
    {
        $this->locacao = $locacao;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        try {
            $locacaoRepository = new LocacaoRepository($this->locacao);

            if ($request->has('filtro')) {
                $locacaoRepository->filtro($request->filtro);
            }

            if ($request->has('atributos')) {
                $locacaoRepository->selectAtributos($request->atributos);
            }

            return response()->json($locacaoRepository->getResultado(), 200);
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
        $request->validate($this->locacao->rules());
        try {
            $locacao = $this->locacao->create([
                'cliente_id'=> $request->cliente_id,
                'carro_id'=> $request->carro_id,
                'data_inicio_periodo'=> $request->data_inicio_periodo,
                'data_final_previsto_periodo'=> $request->data_final_previsto_periodo,
                'data_final_realizado_periodo'=> $request->data_final_realizado_periodo,
                'valor_diaria'=> $request->valor_diaria,
                'km_inicial'=> $request->km_inicial,
                'km_final' => $request->km_final,
            ]);
            return response()->json($locacao, 201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Locacao  $locacao
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $locacao = $this->locacao->find($id);

            if (!$locacao) {
                return response()->json(['error' => 'locacao não encontrado'], 404);
            }
            return response()->json($locacao, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Locacao  $locacao
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            $locacao = $this->locacao->find($id);

            if (!$locacao) {
                return response()->json(['error' => 'Impossivel realizar atualização. O recurso solicitado não existe'], 404);
            }

            if ($request->method() === 'PATCH') {
                $dynamicRules = array();
                //percorrendo array de regras definidas no model

                foreach ($locacao->rules() as $input => $rule) {
                    if (array_key_exists($input, $request->all())) {
                        $dynamicRules[$input] = $rule;
                    }
                }

                $request->validate($dynamicRules);
            } else {
                $request->validate($locacao->rules());
            }

            $locacao->fill($request->all());
            $locacao->save();

            return response()->json($locacao, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Locacao  $locacao
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $locacao = $this->locacao->find($id);
            if (!$locacao) {
                return response()->json(['error' => 'Impossivel realizar exclusão. O recurso solicitado não existe'], 404);
            }

            $locacao->delete();

            return response()->json(['msg' => 'locacao removida com sucesso'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
