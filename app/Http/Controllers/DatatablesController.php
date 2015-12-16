<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\User;
use yajra\Datatables\Datatables;

use Request;
use App\Po;
use App\BarcodeStock;
use App\BarcodeRequests;

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
            //\DB::raw('count(barcode_stocks.po_id) as count'),
            \DB::raw('sum(barcode_stocks.qty) as stock_qty'),
            //'pos.created_at',
            \DB::raw('sum(barcode_requests.qty) as request_qty'),
            //'pos.updated_at',
            //'pos.flash',
            //'pos.closed_po'
            //'pos.brand',
            //'pos.status',
            //'pos.type',
            //'pos.comment',
                   
        ])
        ->leftJoin('barcode_stocks','barcode_stocks.po_id','=','pos.id')
        ->leftJoin('barcode_requests','barcode_requests.po_id','=','pos.id')
        //->where('pos.closed_po','=',0)
        ->groupBy('pos.po','pos.size','pos.style','pos.color','pos.color_desc','pos.season','pos.total_order_qty'/*,'pos.flash','pos.closed_po','pos.created_at','pos.updated_at'*/);

        return Datatables::of($table)
        //->editColumn('created_at', '{!! $total_order_qty-$stock_qty !!}')
        ->addColumn('b_stock', '{!! $total_order_qty-$stock_qty !!}')
        ->addColumn('b_request', '{!! $stock_qty-$request_qty !!}')
        ->make(true);
    }
}
