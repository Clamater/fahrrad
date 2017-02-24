<?php
/**
 * Hauptverantwortlich: Enrico Costanzo
 */

namespace App\Http\Middleware;

use Closure;

/**
 * Class Admin
 * @package App\Http\Middleware
 */
class Admin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(!$request->hasCookie("admin")){
            return redirect("admin/login");
        }
        return $next($request);
    }
}
