<?php

namespace App\Http\Controllers\Api;

use App\Models\Cliente;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Intervention\Image\ImageManagerStatic as Image;
use Illuminate\Support\Facades\Storage;

class ClienteApiController extends Controller
{
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
        
        if($request->hasFile('image') && $request->file('image')->isValid()){
            $extension = $request->image->extension();
            $name = uniqid(date('His'));
            $nameFile = "{$name}.{$extension}";
            $upload = Image::make($dataForm['image'])->resize(177, 236)->save(storage_path("app/public/clientes/$nameFile", 70));
            
            if(!$upload){
                return response()->json(['error'=>'Falha ao fazer o upload!'], 500);
            }else{
                $dataForm['image'] = $nameFile;
            }
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
        
        $this->validate($request, $cliente->rules());
        $dataForm = $request->all();
        
        if($request->hasFile('image') && $request->file('image')->isValid()){

            if($data->image){
                Storage::disk('clientes')->delete($data->image);
            }

            $extension = $request->image->extension();
            $name = uniqid(date('His'));
            $nameFile = "{$name}.{$extension}";
            
            $upload = Image::make($dataForm['image'])->resize(177, 236)->save(storage_path("app/public/clientes/$nameFile", 70));
            
            if(!$upload){
                return response()->json(['error'=>'Falha ao fazer o upload!'], 500);
            }else{
                $dataForm['image'] = $nameFile;
            }
        }
        $data->update($dataForm);
    
        return response()->json($data, 201);
    }

    public function destroy($id)
    {
        if(!$data = Cliente::find($id))
            return response()->json(['error' => 'Nenhum Cliente foi encontrado!'], 404);
        if($data->image){
            Storage::disk('clientes')->delete($data->image);
        }
        
        $data->delete();
        return response()->json(['success' => 'Deletado com sucesso!!']);
    }
    
}
