<?php

namespace Flippingbook\Http\Controllers\Admin;

class AuthController
{
    public function logout()
    {
        auth('web')->logout();

        return redirect('/');
    }
}
