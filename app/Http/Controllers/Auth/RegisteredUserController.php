<?php

namespace App\Http\Controllers\Auth;

use App\Events\NewUserRegisteredEvent;
use App\Events\NewUserRegisteredEvent2;
use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\User;
use App\Notifications\NewUserRegisteredNotification;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($user));

        ##----------------send notification
        $admin = Admin::find(1);
        //WAY ONE
        // $admin->notify(new NewUserRegisteredNotification($user));
        //WAY TWO
        Notification::send($admin, new NewUserRegisteredNotification($user));

        //--------------------------BROADCAST EVENT
        //WAY ONE
        NewUserRegisteredEvent::dispatch($user);
        // NewUserRegisteredEvent2::dispatch('Hello from the new event 2');

        //WAY TWO
        // Broadcast(new NewUserRegisteredEvent());

        //--------------------------MODEL BROADCASTING
        // $user->broadcastChannel();

        Auth::login($user);

        return redirect(RouteServiceProvider::HOME);
    }
}
