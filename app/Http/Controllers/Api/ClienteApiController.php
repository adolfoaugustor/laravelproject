<?php

namespace App\Http\Controllers\Api;

use App\Models\Cliente;
use Illuminate\Http\Request;
use App\Http\Controllers\MasterApiController;
use Intervention\Image\ImageManagerStatic as Image;
use Illuminate\Support\Facades\Storage;

class ClienteApiController extends MasterApiController
{
    // protected $model;
    // protected $path = 'clientes';

    // protected $model;
    // protected $path = 'clientes';

    // public function __construc(Cliente $clientes, Request $request)
    // {
    //     this->model = $clientes;
    //     this->request = $request;
    // }
    public function index()
    {
        $cliente = Cliente::all();
        return response()->json($cliente);
    }

    public function store(Request $request)
    {
        $cliente = new Cliente();
        $this->validate($request, $cliente->rules());
        $dataForm = $request->all();
        
        if($request->hasFile('image')){
            $dataForm['image'] = $request->image->store('clients', 'public');
        }

        $data = $cliente->create($dataForm);
    
        return response()->json($data, 201);
    }

    public function show($id)
    {
        if(!$data = Cliente::find($id)){
            return response()->json(['error' => 'Nenhum Cliente foi encontrado!'], 404);
        }else{
            return response()->json($data, 201);
        }
    }

    public function update(Request $request, $id)
    {
        if(!$data = Cliente::find($id))
            return response()->json(['error' => 'Nenhum Cliente foi encontrado!'], 404);
        
        $this->validate($request, $data->rules());
        $dataForm = $request->all();
        
        if($request->hasFile('image')){
            if($request->image){
                if(Storage::disk('public')->exists($dataForm->image)){
                    Storage::disk('public')->delete($dataForm->image);
                }
            }
            $dataForm['image'] = $request->image->store('clients', 'public');
        }

        $data->update($dataForm);  
        return response()->json($data, 201);
    }

    public function destroy($id)
    {
        if(!$data = Cliente::find($id))
            return response()->json(['error' => 'Nenhum Cliente foi encontrado!'], 404);
        if($data->image){
            if(Storage::disk('public')->exists($data->image)){
                Storage::disk('public')->delete($data->image);
            }
        }
        
        $data->delete();
        return response()->json(['success' => 'Deletado com sucesso!!']);
    }
    
}
