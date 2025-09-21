<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Agent;
use App\Models\Withdrawal;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AgentAuthController extends Controller
{
        public function showLoginForm() {
        return view('agents.login');
    }

    // Handle login
    public function login(Request $request) {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::guard('agent')->attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('/agent/dashboard');
        }

        return back()->withErrors([
            'email' => 'Invalid credentials'
        ])->onlyInput('email');
    }

    // Show registration form
    public function showRegisterForm() {
        return view('agents.register');
    }

    // Handle registration
    public function register(Request $request) {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:agents,email',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $agent = Agent::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        Auth::guard('agent')->login($agent);
        $request->session()->regenerate();

        return redirect('/agent/dashboard');
    }

    // Logout
    public function logout(Request $request) {
        Auth::guard('agent')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/agent/login');
    }


    public function dashboard()
    {
        $agent = Auth::guard('agent')->user();
        $withdrawals = Withdrawal::where('agent_id', $agent->id)->orderBy('created_at', 'desc')->get();
        return view('agents.dashboard', compact('agent', 'withdrawals'));
    }

   public function requestWithdrawal(Request $request)
    {
        $agent = Auth::guard('agent')->user();

        $request->validate([
            'amount' => 'required|numeric|min:1',
        ]);

        if ($request->amount > $agent->wallet_balance) {
            return back()->with('error', 'You don’t have enough balance in your wallet.');
        }

       $lastWithdrawal = Withdrawal::where('agent_id', $agent->id)
            ->where('status', '!=', 'rejected')
            ->orderBy('created_at', 'desc')
            ->first();

        if ($lastWithdrawal && $lastWithdrawal->created_at && $lastWithdrawal->created_at->gt(now()->subWeek())) {
            return back()->with('error', 'You can only withdraw once a week.');
        }

        Withdrawal::create([
            'agent_id' => $agent->id,
            'amount' => $request->amount,
            'status' => 'pending',
        ]);

        return back()->with('success', 'Withdrawal request submitted successfully.');
    }


}
