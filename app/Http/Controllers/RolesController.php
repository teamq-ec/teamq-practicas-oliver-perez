<?php

namespace App\Http\Controllers;

use App\Http\Documentation\DocRol;
use App\Http\Requests\RolesRequest;
use App\Models\Roles;
use Illuminate\Support\Facades\Gate;
use Knuckles\Scribe\Attributes\Authenticated;
use Knuckles\Scribe\Attributes\Group;
use Symfony\Component\HttpFoundation\Response;

#[Group(DocRol::GRP_TITTLE, DocRol::GRP_DESC)]
#[Authenticated]
class RolesController extends Controller
{
    public function index(){
        if(Gate::denies('ejecutd_user')) {
            return response()->json(["message" => __('unauthorized')], Response::HTTP_UNAUTHORIZED);
        }
        $rol = Roles::paginate(10);
        $rol->appends(request()->query());
        if($rol->isEmpty()) {
            return response()->json(["message" => __('roles_not_found')], Response::HTTP_NOT_FOUND);
        }
        return response()->json(["Roles" => $rol], Response::HTTP_OK);
    }

    public function show($id){
        if(Gate::denies('ejecutd_user')) {
            return response()->json(["message" => __('unauthorized')], Response::HTTP_UNAUTHORIZED);
        }
        $rol = Roles::find($id);
        if(!$rol) {
            return response()->json(["message" => __('role_not_found')], Response::HTTP_NOT_FOUND);
        }
        return response()->json(["Rol" => $rol], Response::HTTP_OK);
    }

    public function store(RolesRequest $request){
        if(Gate::denies('ejecutd_user')) {
            return response()->json(["message" => __('unauthorized')], Response::HTTP_UNAUTHORIZED);
        }
        $rol = Roles::create($request->validated());
        return response()->json(["Rol" => $rol], Response::HTTP_OK);
    }

    public function edit(RolesRequest $request, $id){
        if(Gate::denies('ejecutd_user')) {
            return response()->json(["message" => __('unauthorized')], Response::HTTP_UNAUTHORIZED);
        }
        $rol = Roles::find($id);
        if(!$rol) {
            return response()->json(['message' => __('role_not_found')], Response::HTTP_NOT_FOUND);
        }
        $rol->fill($request->validated());
        $rol->save();
        return response()->json(["message" => __('role_updated'), "Rol" => $rol], Response::HTTP_OK);
    }

    public function update(RolesRequest $request, $id){
        if(Gate::denies('ejecutd_user')) {
            return response()->json(["message" => __('unauthorized')], Response::HTTP_UNAUTHORIZED);
        }
        $rol = Roles::find($id);
        if(!$rol) {
            return response()->json(['message' => __('role_not_found')], Response::HTTP_NOT_FOUND);
        }
        $rol->update($request->validated());
        return response()->json(["message" => __('role_updated'), "Rol" => $rol], Response::HTTP_OK);
    }

    public function destroy($id){
        if(Gate::denies('ejecutd_user')) {
            return response()->json(["message" => __('unauthorized')], Response::HTTP_UNAUTHORIZED);
        }
        $rol = Roles::find($id);
        if(!$rol) {
            return response()->json(['message' => __('role_not_found')], Response::HTTP_NOT_FOUND);
        }
        $rol->delete();
        return response()->json(['message' => __('role_deleted'), "Rol" => $rol], Response::HTTP_OK);
    }
}