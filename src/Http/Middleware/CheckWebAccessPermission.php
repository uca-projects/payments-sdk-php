<?php

namespace Uca\Payments\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response as HttpFoundationResponse;

class CheckWebAccessPermission
{
    /**
     * Handle an incoming request.
     * Solo permito llamadas al las rutas del archivo web a quienes conoces el app.key de esta app
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): HttpFoundationResponse
    {
        $request_api_key = Str::after(str_replace(' ', '+', $request->query('api_key')), 'base64:');
        $config_app_key = Str::after(config('app.key'), 'base64:');

        abort_unless($request_api_key === $config_app_key, HttpFoundationResponse::HTTP_FORBIDDEN, "Access denied. Invalid application key.");
        return $next($request);
    }
}
