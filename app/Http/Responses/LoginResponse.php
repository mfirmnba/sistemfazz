<?php

namespace App\Http\Responses;

use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;

class LoginResponse implements LoginResponseContract
{
    public function toResponse($request)
    {
        dd('Custom LoginResponse aktif'); // test dulu

        $role = $request->user()->role;

        switch ($role) {
            case 'admin':
                return redirect()->route('admin.dashboard');
            case 'owner':
                return redirect()->route('owner.dashboard');
            case 'driver':
                return redirect()->route('driver.dashboard');
            case 'produksi':
                return redirect()->route('produksi.dashboard');
            default:
                return redirect()->route('dashboard'); // fallback
        }
    }

}
