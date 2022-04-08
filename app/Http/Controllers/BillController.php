<?php

namespace App\Http\Controllers;

use App\Events\SendMail;
use App\Models\BillingHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class BillController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $bills = BillingHistory::with('customer')->paginate(20);
        return $this->success('Bill lists',[
            'bill' => $bills
        ]);
    }
    public function myBills()
    {
        $bills = BillingHistory::where('customer_id',\auth()->guard('customer')->id())->paginate(20);
        return $this->success('My bills lists',[
            'bill' => $bills
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $rules = [
            'customer_id' => 'required',
            'bill_month' => 'required',
            'bill_year' => 'required',
            'amount' => 'required'
        ];
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return $this->error($validator->errors()->first());
        }
        $bill = new BillingHistory();
        $bill->customer_id = $request->customer_id;
        $bill->bill_month = $request->bill_month;
        $bill->bill_year = $request->bill_year;
        $bill->amount = $request->amount;
        $bill->status = $request->status;
        $bill->save();

        event(new SendMail($request->customer_id));

        return $this->success('Bill created successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {

        $bill = BillingHistory::find($id);
        if (!isset($bill)){
            return $this->error('Not found');
        }
        return $this->success('Bill info',[
            'bill'=>$bill
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $rules = [
            'status' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return $this->error($validator->errors()->first());
        }
        $bill = BillingHistory::find($id);
        if (!isset($bill)){
            return $this->error('Not found');
        }
        $bill->status = $request->status;
        $bill->save();

        return $this->success('Bill updated successfully');
    }
}
