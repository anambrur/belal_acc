<?php

namespace App\Http\Controllers;

use App\Models\PaymentMethod;
use Illuminate\Http\Request;

class PaymentMethodController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $paymentMethod = PaymentMethod::all();
        return view('paymentSetting.paymentMethod', compact('paymentMethod'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'payment_name' => 'required'
        ]);
        $payment_name = $request->payment_name;
        PaymentMethod::create(['payment_method' => $payment_name]);
        return redirect()->route('paymentMethod.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(PaymentMethod $paymentMethod)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PaymentMethod $paymentMethod)
    {
        dd($paymentMethod);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PaymentMethod $paymentMethod)
    {
    //    dd($request);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PaymentMethod $paymentMethod)
    {
        $paymentMethod->delete();
        return redirect()->route('paymentMethod.index');
    }
    /**
     * api the specified resource from storage.
     */
    public function payment_method_edit($id)
    {
        $data = PaymentMethod::findOrFail($id);

        return response()->json($data);
        
    }
    public function payment_method_update(Request $request)
    {
        
        dd($request->all());
    }
}
