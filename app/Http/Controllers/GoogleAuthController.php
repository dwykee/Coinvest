<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\InvalidStateException;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class GoogleAuthController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    public function callback()
    {
        try {
            Log::info('Google OAuth Callback Started');
            
            // Check if state is valid
            $request = request();
            Log::info('Callback params', [
                'has_code' => $request->has('code'),
                'has_state' => $request->has('state'),
            ]);

            $googleUser = Socialite::driver('google')->user();
            
            Log::info('Google User Retrieved', [
                'email' => $googleUser->getEmail(),
                'name' => $googleUser->getName(),
            ]);

            $user = User::updateOrCreate(
                ['email' => $googleUser->getEmail()],
                [
                    'name' => $googleUser->getName(),
                    'google_id' => $googleUser->getId(),
                    'password' => bcrypt(str()->random(24)),
                    'email_verified_at' => now(),
                ]
            );

            Log::info('User Created/Updated', ['user_id' => $user->id, 'email' => $user->email]);

            Auth::login($user, remember: true);
            
            Log::info('User Authenticated', ['user_id' => Auth::id()]);

            session()->regenerate();
            
            Log::info('Session Regenerated');

            $redirectTo = route('dashboard');
            Log::info('Redirecting to', ['url' => $redirectTo]);
            
            return redirect()->intended($redirectTo);

        } catch (InvalidStateException $e) {
            Log::error('Google OAuth Invalid State: ' . $e->getMessage());
            return redirect('/login')->with('error', 'Session State Invalid. Please try again.');
        } catch (\Exception $e) {
            Log::error('Google OAuth Error', [
                'error' => $e->getMessage(),
                'class' => get_class($e),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
            return redirect('/login')->with('error', 'Google login failed: ' . $e->getMessage());
        }
    }
}