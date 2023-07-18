<?php

namespace App\Http\Controllers;

use App\Http\Requests\UsuarioRequest;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Knuckles\Scribe\Attributes\Authenticated;
use Knuckles\Scribe\Attributes\Group;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Storage;

#[Group("API REST USUARIOS", "APIS para la gestión de usuarios")]
class UsuarioController extends Controller
{
    #[Authenticated]
    public function index(){
        $usuarios = Usuario::paginate(2);
        if (Gate::allows('is-asesor')) {
            $usuarioActual = auth()->user();
            return response()->json(["Usuario registrado" => $usuarioActual],Response::HTTP_OK);
        }
        $usuarios->appends(request()->query());
        if($usuarios->isEmpty()){
            return response()->json(["message" => "No se encontraron usuarios registrados"],Response::HTTP_NOT_FOUND);
        }
        return response()->json(["Usuarios registrados" => $usuarios],Response::HTTP_OK);
    }

    #[Authenticated]
    public function show($id){
        if(Gate::denies('ejecutd_user')){
            return response()->json(["No tienes permiso para ejecutar esta acción"], Response::HTTP_UNAUTHORIZED);
        }
        $usuario = Usuario::find($id);
        if (!$usuario) {
            return response()->json(["message" => "Usuario inexistente"],Response::HTTP_NOT_FOUND);
        }
        return response()->json(["Usuario"=>$usuario],Response::HTTP_OK);
        }

        #[Authenticated]
    public function create(UsuarioRequest $request){
        if(Gate::denies('ejecutd_user')){
            return response()->json(["No tienes permiso para ejecutar esta acción"], Response::HTTP_UNAUTHORIZED);
        }
        $data = $request->validated();
        $data['password'] = bcrypt($data['password']);
        $usuario = Usuario::create($data);
        return response()->json(["Usuario registrado exitosamente" => $usuario],Response::HTTP_OK);
    }

    #[Authenticated]
    public function edit(UsuarioRequest $request, $id){
        if(Gate::denies('ejecutd_user')){
            return response()->json(["No tienes permiso para ejecutar esta acción"], Response::HTTP_UNAUTHORIZED);
        }
        $usuario = Usuario::find($id);
        if (!$usuario) {
            return response()->json(['message' => 'Usuario no encontrado'],Response::HTTP_NOT_FOUND);
        }
        $data = array_filter($request->validated());
        if (isset($data['password'])) {
            $data['password'] = bcrypt($data['password']);
            }
        $usuario->fill($data);
        $usuario->save();
        return response()->json(["Usuario actualizado" => $usuario],Response::HTTP_OK);
        }

        #[Authenticated]
    public function update(UsuarioRequest $request, $id){
        if(Gate::denies('ejecutd_user')){
            return response()->json(["No tienes permiso para ejecutar esta acción"], Response::HTTP_UNAUTHORIZED);
        }
        $usuario = Usuario::find($id);
        if (!$usuario) {
            return response()->json(['message' => 'Usuario no encontrado'],Response::HTTP_NOT_FOUND);
        }
        $data = $request->validated();
        if (isset($data['password'])) {
            $data['password'] = bcrypt($data['password']);
        }
        $usuario->update($request->validated());
        return response()->json(["Usuario actualizado" => $usuario],Response::HTTP_OK);
    }

    #[Authenticated]
    public function destroy($id){
        if(Gate::denies('ejecutd_user')){
            return response()->json(["No tienes permiso para ejecutar esta acción"], Response::HTTP_UNAUTHORIZED);
        }
        $usuario = Usuario::find($id);
        if (!$usuario) {
            return response()->json(['message' => 'Usuario no encontrado'],Response::HTTP_NOT_FOUND);
        }
        $usuario->delete();
        return response()->json(['message' => 'Usuario eliminado exitosamente', "Usuario eliminado"=>$usuario],Response::HTTP_OK);
    }

    // RUTA PARA IMAGENES DE USUARIOS
    #[Authenticated]
    #[Group("API REST PICTURES", "APIS para la gestión de imágenes")]
    public function getPicture($idusuarios){
        if(Gate::denies('ejecutd_user')){
            return response()->json(["No tienes permiso para ejecutar esta acción"], Response::HTTP_UNAUTHORIZED);
        }
        $usuario = Usuario::find($idusuarios);
        if (!$usuario) {
            return response()->json(['message' => 'Usuario no encontrado'],Response::HTTP_NOT_FOUND);
        }
        if (!$usuario->ruta_imagen) {
            return response()->json(['message' => 'El usuario no tiene una imagen de perfil'],Response::HTTP_NOT_FOUND);
        }
        return response()->download(storage_path('app/public/' . $usuario->ruta_imagen));
    }

    #[Group("API REST PICTURES", "APIS para la gestión de imágenes")]
    /**
     * @bodyParam picture file required The picture to upload. Supported formats: JPG, PNG.
     * @response {
     *     "message": "Imagen de perfil actualizada",
     *     "usuario": {
     *         "id": 1,
     *         "name": "John Doe",
     *         "ruta_imagen": "path/to/image.jpg"
    */
    #[Authenticated]
    public function uploadPicture(Request $request, $idusuarios){
        if(Gate::denies('ejecutd_user')){
            return response()->json(["No tienes permiso para ejecutar esta acción"], Response::HTTP_UNAUTHORIZED);
        }
        $usuario = Usuario::find($idusuarios);
        if (!$usuario) {
            return response()->json(['message' => 'Usuario no encontrado'],Response::HTTP_NOT_FOUND);
        }
        if ($request->hasFile('picture')) {
            if ($usuario->ruta_imagen) {
                Storage::disk('public')->delete($usuario->ruta_imagen);
            }
            $imagen = $request->file('picture');
            $ruta_imagen = $imagen->store('imagenes', 'public');
            $usuario->ruta_imagen = $ruta_imagen;
            $usuario->save();
            return response()->json(['message' => 'Imagen de perfil actualizada', 'usuario' => $usuario]);
        }
        return response()->json(['message' => 'No se proporcionó ninguna imagen'],status: 400);
    }

    #[Group("API REST PICTURES", "APIS para la gestión de imágenes")]
    #[Authenticated]
    public function deletePicture($idusuarios){
        if(Gate::denies('ejecutd_user')){
            return response()->json(["No tienes permiso para ejecutar esta acción"], Response::HTTP_UNAUTHORIZED);
        }
        $usuario = Usuario::find($idusuarios);
        if (!$usuario) {
            return response()->json(['message' => 'Usuario no encontrado'],Response::HTTP_NOT_FOUND);
        }
        if (!$usuario->ruta_imagen) {
            return response()->json(['message' => 'El usuario no tiene una imagen de perfil'],Response::HTTP_NOT_FOUND);
        }
        Storage::disk('public')->delete($usuario->ruta_imagen);
        $usuario->ruta_imagen = null;
        $usuario->save();
        return response()->json(['message' => 'Imagen de perfil eliminada', 'usuario' => $usuario],Response::HTTP_OK);
    }

    //LOGIN USER
    #[Group("API REST LOGIN", "APIS para la gestión de login")]
    public function login(Request $request) {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required']
        ]);
        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $token = $user->createToken('token')->plainTextToken;
            $cookie = cookie('cookie_token', $token, 60 * 24);
            return response(["token"=>$token], Response::HTTP_OK)->withoutCookie($cookie);
        } else {
            return response(["message"=> "Credenciales inválidas"],Response::HTTP_UNAUTHORIZED);
        }        
    }
}

