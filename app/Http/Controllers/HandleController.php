<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Order;

class HandleController extends Controller
{
    //
    public function __construct()
    {
    }

    public function handle_post(Request $request){
        $number = $request->get('number');
        $order = Order::where('number', $number)->orderByDesc('created_at')->get();
        if(isset($order) && count($order) < 1) $order = null;
        return view('welcome',  ['order' => $order]);
    }
}
