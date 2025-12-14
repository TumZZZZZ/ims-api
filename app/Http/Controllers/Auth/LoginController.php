<?php

namespace App\Http\Controllers\Auth;

use App\Enum\Constants;
use App\Http\Controllers\Controller;
use App\Mail\SendOTPMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class LoginController extends Controller
{
    public function showLogin()
    {
        $user = Auth::user();
        if ($user) {
            $role = $user->role;
            $role = str_replace('_', '-', strtolower($role));
            return redirect(route($role.'.dashboard'));
        }

        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        $email    = $request->email;
        $password = $request->password;

        $user = User::where('email', $email)->first();

        if (!$user || !Hash::check($password, $user->password)) {
            return back()->withErrors(['email' => 'Invalid credentials'])->withInput();
        }

        if ($user->getBranches()->count() >= 2) {
            return redirect()->route('select.branch')->with('user', $user);
        }

        Auth::login($user);

        if (in_array($user->role, [Constants::ROLE_ADMIN, Constants::ROLE_MANAGER])) {
            $branch = @$user->getBranches()->first();
            if ($branch) {
                $user->active_on = $branch->_id;
                $user->save();
            }
        }

        if ($user->role === 'SUPER_ADMIN') {
            return redirect()->route('super-admin.dashboard');
        } elseif ($user->role === 'ADMIN') {
            return redirect()->route('admin.dashboard');
        } elseif ($user->role === 'MANAGER') {
            return redirect()->route('manager.dashboard');
        }
    }

    public function showSelectBranch()
    {
        return view('auth.select-branch');
    }

    public function selectBranch(Request $request)
    {
        $user = User::find($request->user_id);

        if (Auth::user()) {
            $user->active_on = $request->branch_id;
            $user->save();
            return redirect()->back();
        }

        Auth::login($user);

        $user->active_on = $request->branch_id;
        $user->save();

        if ($user->role === 'ADMIN') {
            return redirect()->route('admin.dashboard');
        } elseif ($user->role === 'MANAGER') {
            return redirect()->route('manager.dashboard');
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();
        return redirect()->route('login');
    }

    public function forgotPassword()
    {
        return view('auth.forgot-password');
    }

    public function sendOTP(Request $request)
    {
        $email = $request->email;
        $user  = User::where('email', $email)->first();
        if (!$user) {
            return back()->with(['errors' => 'User not registered!'])->withInput();
        }
        $otp = rand(100000, 999999);
        $user->verify_otp = $otp;
        $user->save();

        Mail::to($email)->send(new SendOTPMail($otp));

        return redirect()->route('verify.otp.form');
    }

    public function verifyOTPForm()
    {
        return view('auth.verify-otp');
    }

    public function verifyOTPCode(Request $request)
    {
        $otp = "";
        for ($i=1; $i <= 6; $i++) {
            $otp .= $request->{"digit_$i"};
        }
        $otp  = (int) $otp;
        $user = User::where('verify_otp', $otp)->first();
        if (!$user) {
            return back()->with(['errors' => 'Invalid OTP code!'])->withInput();
        }
        $user->verify_otp = null;
        $user->save();

        return redirect()->route('reset.password.form', ['id' => $user->id]);
    }

    public function resetPasswordForm($id)
    {
        return view('auth.reset-password', ['id' => $id]);
    }

    public function resetPassword(Request $request)
    {
        $user = User::find($request->id);
        if (!$user) {
            throw new \Exception('User not found!');
        }
        $user->password = Hash::make($request->password);
        $user->save();

        return redirect()->route('login');
    }
}
