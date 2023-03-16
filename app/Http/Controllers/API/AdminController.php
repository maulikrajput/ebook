<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;


class AdminController extends Controller
{
    /**
     * Login for admin
     *
     * @param  $request Illuminate\Http\Request
     * @return json
     *
     */
    public function login(Request $request)
    {
        $credentials = [
            'email' => $request->email,
            'password' => $request->password
        ];

        if(Auth::attempt($credentials)) {
            return $this->success("Admin login successfully");
        } else {
            return $this->error("Unautorised");
        }
    }

    /**
     * logout for admin
     *
     * @return json
     *
     */
    public function logout()
    {
        try {
            Session::flush();
            $success = true;
            $message = "Logout successfully";
        } catch (\Illuminate\Database\QueryException $ex) {
            return $this->error($ex->getMessage());
        }
        return $this->success($message);
    }
}
