<?php

namespace App\Http\Controllers;

use App\Models\Accounts;
use App\Models\BalanceTansfer;
use Illuminate\Http\Request;

class BalanceTansferController extends Controller
{
    public function balanceTransferView()
    {
        $accounts = Accounts::all();
        return view('accounts.balanceTransfer', compact('accounts'));
    }

    public function balanceTransfer(Request $request)
    {
        
        $request->validate([
            'reason' => 'required',
            'from_accounts_id' => 'required|exists:accounts,id',
            'to_accounts_id' => 'required|exists:accounts,id',
            'adjustAmount' => 'required',
        ]);
        
        $balance_transfer =BalanceTansfer::create([
            'reason' => $request->reason,
            'Balance' => $request->Balance,
            'adjustAmount' => $request->adjustAmount,
            'from_accounts_id' => $request->from_accounts_id,
            'to_accounts_id' => $request->to_accounts_id,
            'date' => $request->date,
            'status' => $request->status,
            'description' => $request->description,
        ]);
        // dd($request->all());
        $from_accounts = Accounts::findOrFail($request->from_accounts_id);
        $from_accounts->update(['amount' => $from_accounts->amount - $request->adjustAmount]);

        $to_accounts = Accounts::findOrFail($request->to_accounts_id);
        $to_accounts->update(['amount' => $to_accounts->amount + $request->adjustAmount]);

        return redirect()->route('accounts.index')->with('success', 'Balance adjusted successfully');
    }
}
