<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Withdrawal;
use Illuminate\Support\Facades\Auth;

class AdminWithdrawalController extends Controller
{
    public function dashboard()
    {
        $withdrawals = Withdrawal::with('agent')->orderBy('created_at', 'desc')->get();
        return view('dashboard', compact('withdrawals'));
    }

    public function approve(Withdrawal $withdrawal)
    {
        $withdrawal->update([
            'status' => 'approved',
            'processed_by' => Auth::id(),
            'processed_at' => now(),
        ]);
        return back()->with('success', 'Withdrawal approved successfully.');
    }

    public function reject(Request $request, Withdrawal $withdrawal)
    {

        $agent = $withdrawal->agent;
        $agent->wallet_balance += $withdrawal->amount;
        $agent->save();


        $withdrawal->update([
            'status' => 'rejected',
            'processed_by' => Auth::id(),
            'processed_at' => now(),
        ]);
        return back()->with('success', 'Withdrawal rejected successfully.');
    }
}
