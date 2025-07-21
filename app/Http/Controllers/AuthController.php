<?php

namespace App\Http\Controllers;

use App\Factory\Auth\AuthFactory;
use App\Factory\Auth\FacebookAuth;
use App\Factory\Auth\GoogleAuth;
use App\Repositories\Users\UserRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    private $userRepo;

    public function __construct(UserRepositoryInterface $userRepo)
    {
        $this->userRepo = $userRepo;
    }

    public function auth()
    {
        return view('login');
    }

    public function redirect(Request $request, string $provider)
    {
        $authFactory = AuthFactory::make($provider);
        return redirect()->away($authFactory->getOAuthUrl());
    }

    public function callback(Request $request, string $provider)
    {
        $params = $request->all();
        $authFactory = AuthFactory::make($provider);
        try {
            $accessToken = $authFactory->getAccessToken($params['code']);
            $userInfo = $authFactory->getUserInfo($accessToken);
            $user = $this->userRepo->findUserUsingEmail($userInfo['email']);
            if (is_null($user)) {
                $user = $this->userRepo->create([
                    'name' => $userInfo['name'],
                    'email' => $userInfo['email'],
                    'avatar' => $userInfo['picture']
                ]);
            }
            Auth::login($user);
            return redirect('/home');
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return redirect('/auth');
        }
    }

    public function logout(Request $request)
    {
        if (Auth::check()) {
            Auth::logout();
        }
        return redirect('/auth');
    }
}