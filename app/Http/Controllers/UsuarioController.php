<?php

namespace App\Http\Controllers;

use App\Http\Documentation\DocUsuario;
use App\Http\Requests\UsuarioRequest;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Knuckles\Scribe\Attributes\Authenticated;
use Knuckles\Scribe\Attributes\Group;
use Symfony\Component\HttpFoundation\Response;

#[Group(DocUsuario::GRP_TITTLE, DocUsuario::GRP_DESC)]
#[Authenticated]
class UsuarioController extends Controller
{
    public function index(){
        $usuarios = User::paginate(10);
        if (Gate::allows('is-asesor')) {
            $usuarioActual = auth()->user();
            return response()->json(["User" => $usuarioActual],Response::HTTP_OK);
        }
        $usuarios->appends(request()->query());
        if($usuarios->isEmpty()){
            return response()->json(["message" => __('users_not_found')],Response::HTTP_NOT_FOUND);
        }
        return response()->json(["Users" => $usuarios],Response::HTTP_OK);
    }

    public function show($id){
        if(Gate::denies('ejecutd_user')){
            return response()->json(["message" => __('unauthorized')], Response::HTTP_UNAUTHORIZED);
        }
        $usuario = User::find($id);
        if (!$usuario) {
            return response()->json(["message" => __('user_not_found')],Response::HTTP_NOT_FOUND);
        }
        return response()->json(["User"=>$usuario],Response::HTTP_OK);
        }

    public function store(UsuarioRequest $request){
        if(Gate::denies('ejecutd_user')){
            return response()->json(["No tienes permiso para ejecutar esta acción"], Response::HTTP_UNAUTHORIZED);
        }
        $data = $request->validated();
        $usuario = User::create($data);
        return response()->json(["message" => __('user_registered'),"User" => $usuario],Response::HTTP_OK);
    }

    public function edit(UsuarioRequest $request, $id){
        if(Gate::denies('ejecutd_user')){
            return response()->json(["message" => __('unauthorized')], Response::HTTP_UNAUTHORIZED);
        }
        $usuario = User::find($id);
        if (!$usuario) {
            return response()->json(['message' => __('user_not_found')],Response::HTTP_NOT_FOUND);
        }
        $data = array_filter($request->validated());
        if (isset($data['password'])) {
            $data['password'] = bcrypt($data['password']);
            }
        $usuario->fill($data);
        $usuario->save();
        return response()->json(["message" => __('user_updated'),"Usuario actualizado" => $usuario],Response::HTTP_OK);
        }

    public function update(UsuarioRequest $request, $id){
        if(Gate::denies('ejecutd_user')){
            return response()->json(["message" => __('unauthorized')], Response::HTTP_UNAUTHORIZED);
        }
        $usuario = User::find($id);
        if (!$usuario) {
            return response()->json(['message' => __('user_not_found')],Response::HTTP_NOT_FOUND);
        }
        $data = $request->validated();
        if (isset($data['password'])) {
            $data['password'] = bcrypt($data['password']);
        }
        $usuario->update($request->validated());
        return response()->json(["message" => __('user_updated'),"User" => $usuario],Response::HTTP_OK);
    }

    public function destroy($id){
        if(Gate::denies('ejecutd_user')){
            return response()->json(["message" => __('unauthorized')], Response::HTTP_UNAUTHORIZED);
        }
        $usuario = User::find($id);
        if (!$usuario) {
            return response()->json(['message' => __('user_not_found')],Response::HTTP_NOT_FOUND);
        }
        $usuario->delete();
        return response()->json(['message' => __('user_deleted'), "User"=>$usuario],Response::HTTP_OK);
    }
}

