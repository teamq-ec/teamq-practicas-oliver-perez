<?php

namespace App\Http\Controllers;

use App\Models\User;
use Knuckles\Scribe\Attributes\Authenticated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Knuckles\Scribe\Attributes\Group;
use \Symfony\Component\HttpFoundation\Response;
use App\Http\Documentation\DocPicture;

#[Group(DocPicture::GRP_TITTLE, DocPicture::GRP_DESC)]
#[Authenticated]
class PictureController extends Controller
{
    // RUTA PARA IMAGENES DE USUARIOS
    public function getPicture($idusuarios){
        if (Gate::denies('ejecutd_user')) {
            return response()->json(["message" => __('unauthorized')], Response::HTTP_UNAUTHORIZED);
        }
        $usuario = User::find($idusuarios);
        if (!$usuario) {
            return response()->json(['message' => __('user_not_found')], Response::HTTP_NOT_FOUND);
        }
        if (!$usuario->ruta_imagen) {
            return response()->json(['message' => __('no_profile_image')], Response::HTTP_NOT_FOUND);
        }
        return response()->download(storage_path('app/public/' . $usuario->ruta_imagen));
    }

    /**
     * @bodyParam picture file required The picture to upload. Supported formats: JPG, PNG.
     * @response {
     *     "message": "Imagen de perfil actualizada",
     *     "usuario": {
     *         "id": 1,
     *         "name": "John Doe",
     *         "ruta_imagen": "path/to/image.jpg"
     *     }
     * }
     */
    public function uploadPicture(Request $request, $idusuarios)
    {
        if(Gate::denies('ejecutd_user')) {
            return response()->json(["message" => __('unauthorized')], Response::HTTP_UNAUTHORIZED);
        }
        $usuario = User::find($idusuarios);
        if(!$usuario) {
            return response()->json(['message' => __('user_not_found')], Response::HTTP_NOT_FOUND);
        }
        if($request->hasFile('picture')) {
            if ($usuario->ruta_imagen) {
                Storage::disk('public')->delete($usuario->ruta_imagen);
            }
            $imagen = $request->file('picture');
            $ruta_imagen = $imagen->store('imagenes', 'public');
            $usuario->ruta_imagen = $ruta_imagen;
            $usuario->save();
            return response()->json(['message' => __('profile_image_updated'), 'usuario' => $usuario]);
        }
        return response()->json(['message' => __('no_image_provided')], Response::HTTP_BAD_REQUEST);
    }

    public function deletePicture($idusuarios){
        if(Gate::denies('ejecutd_user')) {
            return response()->json(["message" => __('unauthorized')], Response::HTTP_UNAUTHORIZED);
        }
        $usuario = User::find($idusuarios);
        if(!$usuario){
            return response()->json(['message' => __('user_not_found')], Response::HTTP_NOT_FOUND);
        }
        if(!$usuario->ruta_imagen){
            return response()->json(['message' => __('no_profile_image')], Response::HTTP_NOT_FOUND);
        }
        Storage::disk('public')->delete($usuario->ruta_imagen);
        $usuario->ruta_imagen = null;
        $usuario->save();
        return response()->json(['message' => __('profile_image_deleted'), 'usuario' => $usuario], Response::HTTP_OK);
    }
}