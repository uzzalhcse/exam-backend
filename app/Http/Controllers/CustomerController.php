<?php

namespace App\Http\Controllers;

use App\Models\Auth\User;
use App\Models\BillingHistory;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class CustomerController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        return $this->success('Customer lists',[
            'customer' => Customer::paginate(20)
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
            'name' => 'required',
            'address' => 'required',
            'email' => 'required',
            'password' => 'required|string'
        ];
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return $this->error($validator->errors()->first());
        }
        $customer = New Customer();
        $customer->name = $request->name;
        $customer->address = $request->address;
        $customer->email = $request->email;
        $customer->password = Hash::make($request->password);
        $customer->save();

        return $this->success('Customer saved successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $customer = Customer::find($id);
        if (!isset($customer)){
            return $this->error('Customer not found');
        }
        return $this->success('Customer info',[
            'customer'=>$customer
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
            'name' => 'required',
            'address' => 'required',
            'email' => 'required',
            'password' => 'required|string'
        ];
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return $this->error($validator->errors()->first());
        }
        $customer = Customer::find($id);
        if (!isset($customer)){
            return $this->error('Customer not found');
        }
        $customer->name = $request->name;
        $customer->address = $request->address;
        $customer->email = $request->email;
        $customer->save();
        return $this->success('Customer updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $customer = Customer::find($id);
        if (!isset($customer)){
            return $this->error('Customer not found');
        }
        $customer->delete();
        return $this->success('Customer deleted');
    }
}
