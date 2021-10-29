<?php

namespace App\Http\Controllers;

use App\Models\Marca;
use Illuminate\Http\Request;

use function PHPUnit\Framework\isEmpty;

class MarcaController extends Controller
{

    public function __construct(Marca $marca)
    {
        $this->marca = $marca;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $marcas = $this->marca->all();
            return response()->json($marcas, 200);
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
        try {
            $marca = $this->marca->create($request->all());
            return response()->json($marca, 201);
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
            $marca = $this->marca->find($id);

            if (!$marca) {
                return response()->json(['error' => 'Marca não encontrada'], 404);
            }
            return response()->json($marca, 200);
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
            $marca = $this->marca->find($id);
            if (!$marca) {
                return response()->json(['error' => 'Impossivel realizar atualização. O recurso solicitado não existe'], 404);
            }
            $marca->update($request->all());
            return response()->json($marca, 200);
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
            $marca = $this->marca->find($id);
            if (!$marca) {
                return response()->json(['error' => 'Impossivel realizar exclusão. O recurso solicitado não existe'], 404);
            }
            $marca->delete();

            return response()->json(['msg' => 'Marca removida com sucesso'], 200);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
