<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsurePeriodeSelected
{
    public function handle(
        Request $request,
        Closure $next
    ): Response {

        if (!session()->has('periode_id')) {

            $routeName = $request->route()?->getName();

            return redirect()->route('periode.pilih', [
                'tujuan' => $routeName,
            ]);
        }

        return $next($request);
    }
}