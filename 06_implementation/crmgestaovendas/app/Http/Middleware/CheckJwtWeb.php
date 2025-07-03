<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class CheckJwtWeb
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        try {
            // Intentamos obtener el token del header Authorization (si es una petición API)
            // o de una cookie/session/localStorage (si es una petición web/navegador).
            // Para Blade, asumiremos que el JS lo pondrá en un header o que lo pasaremos manualmente.
            // Para este ejemplo simple, el JS guardará en localStorage y podrías leerlo aquí si es necesario
            // o simplemente confiar en que el usuario no puede acceder si no tiene el token.

            // Para Blade, lo más sencillo es que el JavaScript ya redirigió si hay token.
            // Si el usuario intenta acceder directamente a /dashboard sin haber pasado por login:
            // Intentaremos cargar el usuario desde el token en la sesión o una cookie si lo guardaste así
            // O, para la simplicidad de este ejemplo, si no hay token en el localStorage (manejado por JS),
            // redirigimos.

            // Una forma más robusta:
            // Obtener el token del localStorage (en el cliente) y enviarlo al servidor
            // En una aplicación real, el token se enviaría en un encabezado 'Authorization'
            // para cada solicitud de recursos protegidos por la API.
            // Para Blade, podemos simularlo o simplemente redirigir si no hay token válido.

            // Aquí, simplemente verificamos si hay un token válido en la solicitud (ej. si se inyecta desde JS para una SPA)
            // o si JWTAuth puede parsear un token de alguna fuente.
            $user = JWTAuth::parseToken()->authenticate();

            if (!$user) {
                // No se pudo autenticar al usuario con el token.
                // Redirige al login.
                return redirect()->route('login');
            }

        } catch (JWTException $e) {
            // Si hay alguna excepción con el JWT (token expirado, inválido, no provisto)
            // Redirige al login.
            return redirect()->route('login');
        }

        return $next($request);
    }
}
