<?php


namespace App\Http\Controllers\Auth;


use App\Http\Controllers\ApiController;
use App\Http\Controllers\ProjectController;
use App\Http\Resources\Auth\AuthResource;
use App\Http\Resources\ProfileResource;
use App\Http\Resources\ProjectResource;
use App\Models\Auth\EducationHistory;
use App\Models\Auth\Role;
use App\Models\Auth\SocialProfile;
use App\Models\Auth\User;
use App\Models\Auth\UserRole;
use App\Models\DocumentVerify;
use App\Models\Image;
use App\Models\Portfolio;
use App\Models\Projects;
use App\Models\Review;
use App\Models\UserProfile;
use App\Models\WorkExperience;
use Carbon\Carbon;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password as RulesPassword;

class AuthController extends ApiController
{

    public function login(Request $request)
    {
        $rules = [
            'email' => 'required',
            'password' => 'required|string'
        ];
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return $this->error($validator->errors()->first());
        }
        $guard = 'admin';
        $tokenName = 'authToken';
        if ($request->is_customer){
            $guard = 'customer_web';
            $tokenName = 'customerToken';
        }
        if (!Auth::guard($guard)->attempt($request->only('email','password'))) {
            return $this->error('Credentials not match', 401);
        }
        return $this->success('Login Success', [
            'access_token' => auth()->guard($guard)->user()->createToken($tokenName)->plainTextToken,
            'token_type' => 'Bearer'
        ]);
    }
    public function info(){
        return $this->success('Auth info',[
            'user'=>new AuthResource(\auth()->user())
        ]);
    }

    public function logout()
    {
        auth()->user()->tokens()->delete();

        return $this->success('Logout Successfully');
    }


}
