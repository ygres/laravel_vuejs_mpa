<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Response;
use App\Order;
use Carbon\Carbon;
use Exception;
use Storage;

class RestController extends Controller
{
    //

    public function __construct()
    {
        //
    }

    public function add_order(Request $request){

        if($request->ajax()){

            $number = $request->get('number');
            $fio = $request->get('fio');
            $birth_date = $request->get('birth_date');
            $file = $request->file('photo');
            $file_name = null;

            if ($request->hasFile('photo')){
                $file_name = Storage::disk('uploads')->put('', $file);
            }

            try{
                $order = new Order();
                $order->number = $number;
                $order->fio = $fio;
                $order->birth_date = $birth_date;
                $order->file = $file_name;
                $order->created_at = Carbon::now();
                $order->save();
            }
            catch (Exception $e){ return Response::json(['success' => 0, 'message' => $e->getMessage()]); }



            return Response::json(['success' => 1, 'order' => $order::orderByDesc('created_at')->get() ]);

        }
        return abort(403, 'Unauthorized action.');
    }

    public function get_order(Request $request){
        if($request->ajax()){
            return Response::json(['success' => 1, 'order' => Order::orderByDesc('created_at')->get() ]);
        }

        return abort(403, 'Unauthorized action.');
    }

    public function update_order(Request $request){
        if($request->ajax()){

            $id = $request->get('id');
            $value = $request->get('value');
            $field = $request->get('field');

            try{
                $order = Order::find($id);

                $order->$field = $value;
                $order->save();
            }
            catch(Exception $e) { return Response::json(['success' => 0, 'message' => $e->getMessage()]); }


            return Response::json(['success' => 1, 'order' => $order]);
        }
        return abort(403, 'Unauthorized action.');
    }

    public function update_photo(Request $request){

        if($request->ajax()){

            $file = $request->file('file');
            $id = $request->get('id');
            $file_name = null;

            if ($request->hasFile('file')){
                $file_name = Storage::disk('uploads')->put('', $file);
            }


            try{
                $order = Order::find($id);

                $order->file = $file_name;
                $order->save();
            }
            catch(Exception $e) { return Response::json(['success' => 0, 'message' => $e->getMessage()]); }

            return Response::json(['success' => 1, 'order' => $order::orderByDesc('created_at')->get()]);
        }

        return abort(403, 'Unauthorized action.');
    }
}
