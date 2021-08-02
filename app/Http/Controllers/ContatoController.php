<?php

namespace App\Http\Controllers;

use App\Models\Contato;
use Illuminate\Http\Request;
use App\Http\Controllers\BaseController as BaseController;
use App\Http\Resources\Contato as ContatoResource;
use Validator;

class ContatoController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $contatos = Contato::all();    
        return $this->sendResponse(ContatoResource::collection($contatos), 'Contatos retornados com sucesso.');
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
   
        $contato = Contato::create($input);
   
        return $this->sendResponse(new ContatoResource($contato), 'Contato criado com sucesso.');

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Contato  $contato
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $contato = Contato::find($id);
  
        if (is_null($contato)) {
            return $this->sendError('Contato não encontrado.');
        }
   
        return $this->sendResponse(new ContatoResource($contato), 'Contato retornado com sucesso.');
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
    
        $contato->nome = $input['nome'];
        $contato->telefone = $input['telefone'];
        $contato->email = $input['email'];
        $contato->save();
    
        return $this->sendResponse(new ContatoResource($contato), 'Contato atualizado com sucesso.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Contato  $contato
     * @return \Illuminate\Http\Response
     */
    public function destroy(Contato $contato)
    {
        $contato->delete();
   
        return $this->sendResponse([], 'Contato deletado com sucesso.');
    }
}
