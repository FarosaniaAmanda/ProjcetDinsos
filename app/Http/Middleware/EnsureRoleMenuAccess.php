<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureRoleMenuAccess
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user === null) {
            return $next($request);
        }

        $routeName = $request->route()?->getName() ?? '';
        $role = $user->role;

        if ($this->isSharedAccountRoute($routeName)) {
            return $next($request);
        }

        $tujuanPeriode = match ($role) {
            'petugas' => 'kuisioner.index',
            'verifikator' => 'verifikasi.index',
            default => null,
        };

        if (in_array($routeName, ['periode.pilih', 'periode.set'], true)) {
            $tujuanDiminta = $routeName === 'periode.pilih'
                ? $request->query('tujuan')
                : $request->input('tujuan');

            if ($tujuanPeriode !== null && $tujuanDiminta !== $tujuanPeriode) {
                return redirect()->route('periode.pilih', [
                    'tujuan' => $tujuanPeriode,
                ]);
            }

            if (
                ! in_array($role, ['petugas', 'verifikator'], true)
                && (
                    $tujuanDiminta === null
                    || in_array($tujuanDiminta, ['kuisioner.index', 'verifikasi.index'], true)
                )
            ) {
                return redirect()->route('dashboard');
            }

            return $next($request);
        }

        if (str_starts_with($routeName, 'kuisioner.')) {
            return $role === 'petugas'
                ? $next($request)
                : $this->redirectForRole($role);
        }

        if (str_starts_with($routeName, 'verifikasi.')) {
            return $role === 'verifikator'
                ? $next($request)
                : $this->redirectForRole($role);
        }

        if (! in_array($role, ['petugas', 'verifikator'], true)) {
            return $next($request);
        }

        return $this->redirectForRole($role);
    }

    private function isSharedAccountRoute(string $routeName): bool
    {
        return in_array($routeName, [
            'login',
            'login.process',
            'logout',
            'profil.index',
            'profil.password',
            'profil.password.update',
        ], true) || str_starts_with($routeName, 'password.');
    }

    private function redirectForRole(?string $role): Response
    {
        return match ($role) {
            'petugas' => redirect()->route('periode.pilih', [
                'tujuan' => 'kuisioner.index',
            ]),
            'verifikator' => redirect()->route('periode.pilih', [
                'tujuan' => 'verifikasi.index',
            ]),
            default => redirect()->route('dashboard'),
        };
    }
}
