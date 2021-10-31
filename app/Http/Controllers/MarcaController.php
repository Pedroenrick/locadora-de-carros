<?php

namespace App\Http\Controllers;

use App\Models\Marca;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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
        //método de validação//
        $request->validate($this->marca->rules(), $this->marca->feedback());
        try {
            $image = $request->file('imagem');
            $image_urn = $image->store('images', 'public');

            $marca = $this->marca->create([
                'nome' => $request->nome,
                'imagem' => $image_urn,
            ]);
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

            if ($request->method() === 'PATCH') {
                $dynamicRules = array();
                //percorrendo array de regras definidas no model

                foreach ($marca->rules() as $input => $rule) {
                    if (array_key_exists($input, $request->all())) {
                        $dynamicRules[$input] = $rule;
                    }
                }

                $request->validate($dynamicRules, $marca->feedback());
            } else {
                $request->validate($marca->rules(), $marca->feedback());
            }

            //remove o arquivo antigo
            if ($request->file('imagem')) {
                Storage::disk('public')->delete($marca->imagem);
            }

            $image = $request->file('imagem');
            $image_urn = $image->store('images', 'public');

            $marca = $this->marca->update([
                'nome' => $request->nome,
                'imagem' => $image_urn,
            ]);

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


            Storage::disk('public')->delete($marca->imagem);

            $marca->delete();

            return response()->json(['msg' => 'Marca removida com sucesso'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
