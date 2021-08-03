<?php

namespace App\Http\Controllers;

use App\Models\Contato;
use Illuminate\Http\Request;
use App\Http\Controllers\BaseController as BaseController;
use App\Http\Resources\Contato as ContatoResource;
use Validator;
use Log;

class ContatoController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
    
            $contatos = Contato::all();  
            return $this->sendResponse(ContatoResource::collection($contatos), 'Contatos retornados com sucesso.');
    
        } catch (\Exception $e) {

            Log::error($e);
            return $response()->json(['message' => 'Não foi possível listar contatos.'],400);

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
        $input = $request->all();
   
        $validator = Validator::make($input, [
            'nome' => 'required',
            'telefone' => 'required',
            'email' => 'required',
        ]);
   
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }

        try {

            $contato = Contato::create($input);   
            return $this->sendResponse(new ContatoResource($contato), 'Contato criado com sucesso.');

        } catch (\Exception $e) {
            Log::error($e);
            return $response()->json(['message' => 'Não foi possível criar contato.'],400);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Contato  $contato
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            
            $contato = Contato::find($id);
  
            if (is_null($contato)) {
                return $this->sendError('Contato não encontrado.');
            }
    
            return $this->sendResponse(new ContatoResource($contato), 'Contato retornado com sucesso.');

        } catch (\Exception $e) {
            Log::error($e);
            return $response()->json(['message' => 'Não foi possível listar contato.'],400);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Contato  $contato
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Contato $contato)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'nome' => 'required',
            'telefone' => 'required',
            'email' => 'required',
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }
    
        try {
            
            $contato->nome = $input['nome'];
            $contato->telefone = $input['telefone'];
            $contato->email = $input['email'];
            $contato->save();
        
            return $this->sendResponse(new ContatoResource($contato), 'Contato atualizado com sucesso.');

        } catch (\Exception $e) {
            Log::error($e);
            return $response()->json(['message' => 'Não foi possível atualizar contato.'],400);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Contato  $contato
     * @return \Illuminate\Http\Response
     */
    public function destroy(Contato $contato)
    {
        try {
            
            $contato->delete();
   
            return $this->sendResponse([], 'Contato deletado com sucesso.');

        } catch (\Exception $e) {
            Log::error($e);
            return $response()->json(['message' => 'Não foi possível remover contato.'],400);
        }
    }
}
