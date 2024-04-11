<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;


// https://www.youtube.com/watch?v=kwAGyN5LTSY

class CustomerController extends Controller
{
    
    protected function guard()
    {
        return Auth::guard('customer');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            // 'device_name' => 'required'
        ]);

        // return $request->all();

        $customer = Customer::where('email', $request->email)->first();


        if (!$customer || !Hash::check($request->password, $customer->password)) {
            return response([
                'email' => ['The provided credentials are incorrect.'],
            ], 404);
        }

        $customer['token'] = $customer->createToken("customer-$request->device_name")->plainTextToken;

        return response($customer, 200);
    }



    public function register(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'phone' => 'required|digits:10',
            'password' => 'required|string|confirmed',
        ]);


        // return $request->all();
        $customer = Customer::create(array_merge($request->all(), [
            'password' => Hash::make($request->password)
        ]));

        if ($customer) {
            return response()->json($customer, Response::HTTP_CREATED);
        } else {
            return response()->json(['message' => 'User Creation Failed']);
        }
    }


    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
