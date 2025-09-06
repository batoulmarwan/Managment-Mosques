<?php

namespace App\Http\Controllers;

use App\Http\Requests\AdminLoginRequest;
use App\Http\Requests\UserLoginRequest;
use App\Http\Requests\UserRegisterRequest;
use App\Http\Resources\LoginResource;
use App\Http\Resources\RegisterResource;
use App\Mail\SendCodeResetPassword;
use App\Models\Admin;
use App\Models\ResetCodePassword;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class AuthController extends BaseController
{
    public function user_register(UserRegisterRequest $request)
    {
        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $input['activation_token'] = str::random(60);
        $user = user::query()->create($input);

        $accessToken = $user->createToken('MyApp', ['user'])->accessToken;
        $data['token'] = $accessToken;
        $data['user'] = new RegisterResource($user);
        return $this->sendResponse($data, "Registration Done");


    }

    public function user_login(UserLoginRequest $request)
    {
        $log = request(['email', 'password']);
        if (auth()->guard('user')->attempt($request->only('email', 'password'))) {
            config(['auth.guards.api.provider' => 'user']);
            $user = user::query()->select('users.*')->find(auth()->guard('user')->user()['id']);
            $success = $user;
            $success['token'] = $user->createToken('MyApp', ['user'])->accessToken;
            $success['user'] = new LoginResource($user);
            return $this->sendResponse($success, "Logining Done");
        } else {
            return $this->sendError('User not authenticated', [], 401);
        }
    }

    public function admin_login(AdminLoginRequest $request)
    {
      
        if (auth()->guard('admin')->attempt($request->only('email', 'password'))) {
            config(['auth.guards.api.provider' => 'admin']);
            $admin = Admin::query()->select('admins.*')->find(auth()->guard('admin')->user()['id']);
            $success = $admin;
            $success['token'] = $admin->createToken('MyApp', ['admin'])->accessToken;
            $success['admin'] = new LoginResource($admin);
            return $this->sendResponse($success, "Logining Done");
        } else {
            return $this->sendError('Admin not authenticated', [], 401);
        }
    }
    public function ForgetPassword(Request $request)
    {
        $data = $request->validate([
            'email' => 'required|email|exists:admins',
        ]);

        ResetCodePassword::where('email', $request->email)->delete();

        
        $data['code'] = mt_rand(100000, 999999);

        
        $codeData = ResetCodePassword::create($data);


        Mail::to($request->email)->send(new SendCodeResetPassword($codeData['code']));

        return response(['message' => trans('code.sent')], 200);

    }
    public function CodeCheck(Request $request)
    {
        $request->validate([
            'code' => 'required|string|exists:reset_code_passwords',
        ]);

    
        $passwordReset = ResetCodePassword::firstWhere('code', $request->code);

       
        if ($passwordReset->created_at > now()->addHour()) {
            $passwordReset->delete();
            return response(['message' => trans('passwords.code_is_expire')], 422);
        }

        return response([
            'code' => $passwordReset->code,
            'message' => trans('passwords.code_is_valid')
        ], 200);
    }

    public function ResetPassword(Request $request)
    {
        $request->validate([
            'code' => 'required|string|exists:reset_code_passwords',
            'password' => 'required|string|min:6|confirmed',
        ]);

       
        $passwordReset = ResetCodePassword::firstWhere('code', $request->code);

     
        if ($passwordReset->created_at < now()->subHour()) {  
            $passwordReset->delete();
            return response(['message' => trans('passwords.code_is_expire')], 422);
        }

        
        $user = Admin::firstWhere('email', $passwordReset->email);

        $user->update([
            'password' => Hash::make($request->password),
        ]);


        $passwordReset->delete();

        return response(['message' => 'Password has been successfully reset'], 200);
    }

    public function userProfile()
    {
        config(['auth.guards.api.provider' => 'user']); 
        $user = auth()->user();
    
        if (!$user) {
            return $this->sendError('المستخدم غير مصرح له', [], 401);
        }
    
        return $this->sendResponse(new RegisterResource($user), "تم جلب بيانات المستخدم");
    }
    
    public function adminProfile()
    {
        $admin = auth()->guard('admin-api')->user(); // ✅ استخدم admin-api
    
        if (!$admin) {
            return $this->sendError('المسؤول غير مصرح له', [], 401);
        }
    
        return $this->sendResponse(new LoginResource($admin), "تم جلب بيانات المشرف");
    }
    
    
}
