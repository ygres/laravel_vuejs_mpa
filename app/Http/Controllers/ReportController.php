<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Order;
//use Barryvdh\DomPDF\PDF;
use Barryvdh\DomPDF\Facade as PDF;
use Maatwebsite\Excel\Facades\Excel as Excel;

class ReportController extends Controller
{
    //
    public function export_pdf(){

        $order = Order::all();
        $pdf = PDF::loadView('pdf', ['order' => $order]);

        return $pdf->download('export_to_pdf.pdf');
    }

    public function export_exl(){
        $items = Order::select('number', 'fio', 'birth_date')->get();

        return Excel::create('export_to_xls', function($excel) use($items) {
            $excel->sheet('ExportFile', function($sheet) use($items) {
                $sheet->fromArray($items);
            });
        })->export('xls');
    }

    public function export_csv(){
        $items = Order::select('number', 'fio', 'birth_date')->get();

        return Excel::create('export_to_csv', function($excel) use($items) {
            $excel->sheet('ExportFile', function($sheet) use($items) {
                $sheet->fromArray($items);
            });
        })->export('csv');
    }
}
