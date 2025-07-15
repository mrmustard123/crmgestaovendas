<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
//use App\Models\Doctrine\User; // Importa tu entidad User de Doctrine

class LoginController extends Controller
{
    /**
     * Handle a login request to the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $credentials = $request->only('username', 'password');
              
        try {
            // Intenta autenticar al usuario usando JWTAuth
            if (!$token = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => 'Invalid credentials'], 401);
            }
        } catch (JWTException $e) {
            return response()->json(['error' => 'Could not create token'], 500);
        }
        
/***/        
        
    // 1. Obtener el usuario autenticado por JWT (es tu entidad App\Models\Doctrine\User)    
    $user = JWTAuth::setToken($token)->authenticate();
    
    // Verificación adicional (aunque si attempt fue OK, esto no debería ser null)
    if (!$user) {
        return response()->json(['error' => 'User not found after token generation'], 500);
    }    
    
    // 2. Iniciar sesión al usuario en el guard 'web' (basado en sesiones)
    // Esto creará una sesión para el usuario en Laravel
    Auth::guard('web')->login($user);    
    
/****/          
        
        // Si la autenticación es exitosa, devuelve el token
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => Auth::guard('api')->factory()->getTTL() * 60, // Duración en segundos
            'redirect_to' => url('/dashboard')
        ]);
    }

    /**
     * Log the user out (invalidate the token and clear web session).
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function logout(Request $request) 
    {
        try {
            // 1. Invalidar el token JWT (si se está usando para API)
            // Esto solo es relevante si los usuarios también están usando el token JWT directamente.
            // Si el logout es solo para la interfaz web, podria incluso omitir esta parte
            // o manejarla de forma condicional.
            if (JWTAuth::getToken()) { // Verifica si hay un token para invalidar
                JWTAuth::invalidate(JWTAuth::getToken()); 
            }
        } catch (JWTException $e) {
            // Puedo loggear el error, pero no debería impedir el logout de la sesión web
            // \Log::error("JWT Logout Error: " . $e->getMessage());
        }

        // 2. Cerrar la sesión de Laravel (guard 'web')
        Auth::guard('web')->logout();

        // 3. Invalidar la sesión para prevenir secuestro de sesión
        $request->session()->invalidate();

        // 4. Regenerar el token CSRF
        $request->session()->regenerateToken();

        // 5. Redirigir al usuario a la página de login o a donde necesites
        // Para una aplicación web, normalmente redirigís.
        return redirect('/login'); // O return redirect()->route('login');
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        try {
            return response()->json([
                'access_token' => JWTAuth::refresh(),
                'token_type' => 'bearer',
                'expires_in' => config('jwt.ttl') * 60/*,
                'redirect_to' => url('/dashboard')*/
            ]);
        } catch (TokenExpiredException $e) {
            return response()->json(['error' => 'Token has expired and can no longer be refreshed'], 401);
        } catch (JWTException $e) {
            return response()->json(['error' => 'Could not refresh token'], 500);
        }
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();

            if (!$user) {
                return response()->json(['error' => 'User not found or token invalid'], 404);
            }

            return response()->json($user);
        } catch (TokenExpiredException $e) {
            return response()->json(['error' => 'Token has expired'], 401);
        } catch (TokenInvalidException $e) {
            return response()->json(['error' => 'Token is invalid'], 401);
        } catch (JWTException $e) {
            return response()->json(['error' => 'Token not provided or other JWT error'], 401);
        }
    }
}