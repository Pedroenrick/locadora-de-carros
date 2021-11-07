<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;
use App\Repositories\ClienteRepository;

class ClienteController extends Controller
{


    public function __construct(Cliente $cliente)
    {
        $this->cliente = $cliente;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        try {
            $clienteRepository = new ClienteRepository($this->cliente);

            if ($request->has('filtro')) {
                $clienteRepository->filtro($request->filtro);
            }

            if ($request->has('atributos')) {
                $clienteRepository->selectAtributos($request->atributos);
            }

            return response()->json($clienteRepository->getResultado(), 200);
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
        $request->validate($this->cliente->rules());
        try {
            $cliente = $this->cliente->create([
                'nome' => $request->nome,
            ]);
            return response()->json($cliente, 201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Cliente  $cliente
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $cliente = $this->cliente->find($id);

            if (!$cliente) {
                return response()->json(['error' => 'cliente não encontrado'], 404);
            }
            return response()->json($cliente, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Cliente  $cliente
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            $cliente = $this->cliente->find($id);

            if (!$cliente) {
                return response()->json(['error' => 'Impossivel realizar atualização. O recurso solicitado não existe'], 404);
            }

            if ($request->method() === 'PATCH') {
                $dynamicRules = array();
                //percorrendo array de regras definidas no model

                foreach ($cliente->rules() as $input => $rule) {
                    if (array_key_exists($input, $request->all())) {
                        $dynamicRules[$input] = $rule;
                    }
                }

                $request->validate($dynamicRules);
            } else {
                $request->validate($cliente->rules());
            }

            $cliente->fill($request->all());
            $cliente->save();

            return response()->json($cliente, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Cliente  $cliente
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $cliente = $this->cliente->find($id);
            if (!$cliente) {
                return response()->json(['error' => 'Impossivel realizar exclusão. O recurso solicitado não existe'], 404);
            }

            $cliente->delete();

            return response()->json(['msg' => 'cliente removido com sucesso'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
