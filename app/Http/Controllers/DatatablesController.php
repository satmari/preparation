<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\User;
use yajra\Datatables\Datatables;

use Request;
use App\Po;
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
            'pos.flash',
            'pos.closed_po',
            'pos.created_at',
            //'pos.updated_at'
        ])->leftJoin('barcode_stocks','barcode_stocks.po_id','=','pos.id')
        ->groupBy('pos.po','pos.size','pos.style','pos.color','pos.color_desc','pos.season','pos.total_order_qty','pos.flash','pos.closed_po','pos.created_at');

        return Datatables::of($table)
        ->editColumn('created_at', '{!! $total_order_qty-$stock_qty !!}')
        ->make(true);
    }
}
