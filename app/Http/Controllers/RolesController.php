<?php

namespace App\Http\Controllers;

use App\Http\Requests\RolesRequest;
use App\Models\Roles;
use Illuminate\Support\Facades\Gate;
use Knuckles\Scribe\Attributes\Authenticated;
use Knuckles\Scribe\Attributes\Group;
use Symfony\Component\HttpFoundation\Response;

#[Group("API REST ROLES", "APIS para la gestión de roles")]
#[Authenticated]
class RolesController extends Controller
{
    public function index(){
        if(Gate::denies('ejecutd_user')){
            return response()->json(["No tienes permiso para ejecutar esta acción"], Response::HTTP_UNAUTHORIZED);
        }
        $rol = Roles::paginate(10);
        $rol->appends(request()->query());
        if($rol->isEmpty()){
            return response()->json(["message" => "No se encontraron roles registrados"],Response::HTTP_NOT_FOUND);
        }
        return response()->json(["Roles registrados" => $rol],Response::HTTP_OK);
    }

    public function show($id){
        if(Gate::denies('ejecutd_user')){
            return response()->json(["No tienes permiso para ejecutar esta acción"], Response::HTTP_UNAUTHORIZED);
        }
        $rol = Roles::find($id);
        if (!$rol) {
            return response()->json(["message" => "rol inexistente"],Response::HTTP_NOT_FOUND);
        }
        return response()->json(["Rol"=>$rol],Response::HTTP_OK);
    }

    public function create(RolesRequest $request){
        if(Gate::denies('ejecutd_user')){
            return response()->json(["No tienes permiso para ejecutar esta acción"], Response::HTTP_UNAUTHORIZED);
        }
        $rol = Roles::create($request->validated());
        return response()->json(["Rol registrado exitosamente" => $rol],Response::HTTP_OK);
    }

    public function edit(RolesRequest $request, $id){
        if(Gate::denies('ejecutd_user')){
            return response()->json(["No tienes permiso para ejecutar esta acción"], Response::HTTP_UNAUTHORIZED);
        }
        $rol = Roles::find($id);
        if (!$rol) {
            return response()->json(['message' => 'Rol no encontrado'],Response::HTTP_NOT_FOUND);
        }
        $rol->fill($request->validated());
        $rol->save();
        return response()->json(["Rol actualizado" => $rol],Response::HTTP_OK);
    }

    public function update(RolesRequest $request, $id){
        if(Gate::denies('ejecutd_user')){
            return response()->json(["No tienes permiso para ejecutar esta acción"], Response::HTTP_UNAUTHORIZED);
        }
        $rol = Roles::find($id);
        if (!$rol) {
            return response()->json(['message' => 'Rol no encontrado'],Response::HTTP_NOT_FOUND);
        }
        $rol->update($request->validated());
        return response()->json(["Rol actualizado" => $rol],Response::HTTP_OK);
    }

    public function destroy($id){
        if(Gate::denies('ejecutd_user')){
            return response()->json(["No tienes permiso para ejecutar esta acción"], Response::HTTP_UNAUTHORIZED);
        }
        $rol = Roles::find($id);
        if (!$rol) {
            return response()->json(['message' => 'Rol no encontrado'],Response::HTTP_NOT_FOUND);
        }
        $rol->delete();
        return response()->json(['message' => 'Rol eliminado exitosamente', "Rol eliminado"=>$rol],Response::HTTP_OK);
    }
}