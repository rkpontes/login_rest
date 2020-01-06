<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;
use Hash;

class UsersController extends Controller
{
    
    // index, store, update, show, destroy, login


    public function index(){
        $u = User::all();
        
        if($u)
            return response()->json($u);
        else
            return response()->json(['message' => 'Nenhum usuário encontrado.', 'status' => 'ERROR 404'], 404);

    }

    public function store(Request $resquest){
        $u = new User();
        $u->email = $resquest->email;
        $u->password = bcrypt($resquest->password); // 12345 -> cadeia de hash1 >> hash2
        $u->save();

        return response()->json(['message' => 'Usuário criado com sucesso.', 'user' => $u]);
    }

    public function update(Request $request, $user){
        $u = User::find($user);
        $u->email = $request->email;
        $request->password != '' ? $u->password = $request->password : null;
        $u->save();

        $return = 'Usuário atualizado com sucesso.';

        if($request->password)
            $return = 'Usuário e senha atualizado com sucesso.';

        return response()->json(['message' => $return, 'user' => $u]);
    }

    public function show($user){
        $u = User::find($user);

        if($u)
            return response()->json($u);
        else
            return response()->json(['message' => 'Usuário não encontrado.', 'status' => 'ERROR 404'], 404);
    }

    public function destroy($user){
        $u = User::find($user);

        if($u){
            $u->delete();
            return response()->json(['message' => 'Usuário removido com sucesso.', 'user' => $u]);
        }else{
            return response()->json(['message' => 'Usuário não encontrado.', 'status' => 'ERROR 404'], 404);
        }
    }

    public function login(Request $request){

        $u = User::where('email', $request->email)->first();

        $validHash = Hash::check( $request->password , $u->password );

        if($validHash)
            return response()->json($u);
        else
            return response()->json(['message' => 'Usuario invalido ou nao existente.', 'status' => 'ERROR 404'], 404);

    }

}
