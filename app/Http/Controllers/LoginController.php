<?php

namespace App\Http\Controllers;

use App\Http\Documentation\DocAuth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Knuckles\Scribe\Attributes\Group;
use Symfony\Component\HttpFoundation\Response;

#[Group(DocAuth::GRP_TITTLE, DocAuth::GRP_DESC)]
class LoginController extends Controller
{
    //LOGIN USER
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
            return response(["message"=> __("Credenciales inválidas")],Response::HTTP_UNAUTHORIZED);
        }        
    }
}
