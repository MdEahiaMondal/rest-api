<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\ApiController;
use App\Mail\UserCreated;
use App\Transformers\UserTransformer;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class UserController extends ApiController
{

    public function __construct()
    {
        $this->middleware('client.credential')->only(['store', 'resend']);
        $this->middleware('auth:api')->except(['store', 'verify', 'resend']);
        $this->middleware('transform.input:'.UserTransformer::class)->only(['store', 'update']);
    }


    public function index()
    {
        $users = User::all();
        return $this->showAll($users);
    }


    public function store(Request $request)
    {
        $rules = [
          'name' => 'required',
          'email' => 'required|email|unique:users',
          'password' => 'required|min:8|confirmed',
        ];
        $this->validate($request, $rules);
        $data = $request->all();
        $data['password'] = bcrypt($request->password);
        $data['verified'] = User::UNVERIFIED_USER;
        $data['verification_token'] = User::generateVerificationToken();
        $data['admin'] = User::REGULAR_USER;

        $user = User::create($data);
        return  $this->showOne($user, 201);
    }


    public function show(User $user)
    {
        return  $this->showOne($user);
    }


    public function update(Request $request, User $user)
    {
        $rules = [
            'email' => 'required|email|unique:users,email,'.$user->id,
            'password' => 'required|min:8|confirmed',
            'admin' => 'in:'.User::ADMIN_USER . ',' . User::REGULAR_USER
        ];

        if ($request->has('name')){
            $user->name = $request->name;
        }

        if ($request->has('email') && $user->email != $request->email){ // if come to new email
            $user->verified = User::UNVERIFIED_USER;
            $user->verification_token = User::generateVerificationToken();
            $user->email = $request->email;
        }

        if ($request->has('admin')){
            if (!$user->isVerified())
            {
                return $this->errorResponse( 'Only verified users can modify the admin field', 409); // 409 mains not complete task
            }
        }
        if (!$user->isDirty()){ // The isDirty method determines if any attributes have been changed since the model was loaded. You may pass a specific attribute name to determine if a particular attribute is dirty
            return $this->errorResponse('You need to specify a different value to update', 422);
        }
        $user->save();
        return  $this->showOne($user);
    }


    public function destroy(User $user)
    {
        $user->delete();
        return  $this->showOne($user);
    }

    public  function verify($token){
        $user = User::where('verification_token', $token)->firstOrFail();
        $user->verified = User::VERIFIED_USER;
        $user->verification_token = null;
        $user->save();
        return $this->showMessages('The account has been verified successfully');
    }

    public function resend(User $user)
    {
        if ($user->isVerified())
        {
            return $this->errorResponse('The specify user is already verified', 409);
        }
        retry(5, function () use($user) {
            Mail::to($user)->send(new UserCreated($user));
        }, 100);

        return $this->showMessages('The verification code resend you email');
    }


}
