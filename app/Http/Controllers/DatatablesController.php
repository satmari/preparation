<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\User;
use yajra\Datatables\Datatables;

use Request;
use App\Po;
use App\BarcodeStock;
use App\BarcodeRequests;
use App\CarelabelStock;
use App\CarelabelRequests;

use DB;

class DatatablesController extends Controller
{
    /**
     * Displays datatables front end view
     *
     * @return \Illuminate\View\View
     */
    public function getIndex()
    {
        return view('datatables.index');
    }

    /**
     * Process datatables ajax request.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function anyData()
    {
        
        $table = Po::select([
            //'id',
            //'po_key',
            //'order_code',
            'pos.po',
            'pos.size',
            'pos.style',
            'pos.color',
            'pos.color_desc',
            'pos.season',
            'pos.total_order_qty',
            //'pos.flash',
            //'pos.closed_po'
            //'pos.brand',
            //'pos.status',
            //'pos.type',
            //'pos.comment',
            //'pos.created_at',
            //'pos.updated_at',
            \DB::raw('sum(barcode_stocks.qty) as stock_b_qty'),
            \DB::raw('sum(barcode_requests.qty) as request_b_qty'),
            \DB::raw('sum(carelabel_stocks.qty) as stock_c_qty'),
            \DB::raw('sum(carelabel_requests.qty) as request_c_qty'),

                   
        ])
        ->leftJoin('barcode_stocks','barcode_stocks.po_id','=','pos.id')
        ->leftJoin('barcode_requests','barcode_requests.po_id','=','pos.id')
        ->leftJoin('carelabel_stocks','carelabel_stocks.po_id','=','pos.id')
        ->leftJoin('carelabel_requests','carelabel_requests.po_id','=','pos.id')
        //->where('pos.closed_po','=',0)
        ->groupBy('pos.po','pos.size','pos.style','pos.color','pos.color_desc','pos.season','pos.total_order_qty'/*,'pos.flash','pos.closed_po','pos.created_at','pos.updated_at'*/);

        return Datatables::of($table)
        //->editColumn('created_at', '{!! $total_order_qty-$stock_qty !!}')
        ->addColumn('b_stock', '{!! $total_order_qty-$stock_b_qty !!}')
        ->addColumn('b_request', '{!! $stock_b_qty-$request_b_qty !!}')
        ->addColumn('c_stock', '{!! $total_order_qty-$stock_c_qty !!}')
        ->addColumn('c_request', '{!! $stock_c_qty-$request_c_qty !!}')
        ->make(true);
    }
}
