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
        //$stock_b_qty = \DB::raw('sum(barcode_stocks.qty) as stock_b_qty FROM barcode_stocks WHERE barcode_stocks.po_id = 1');
        // $stock_b_qty = DB::table('barcode_stocks')->select(DB::raw('sum(barcode_stocks.qty) as stock_b_qty'))->where('barcode_stocks.po_id','=', 31 )->get();
        // dd($stock_b_qty[0]->stock_b_qty);
        // $stock_b_qty1 = DB::table("barcode_stocks")->select(DB::raw("sum(barcode_stocks.qty) as stock_b_qty"))->where("barcode_stocks.po_id","=", 31 )->get();
        // $stock_b_qty = $stock_b_qty1[0]->stock_b_qty;
        // dd($stock_b_qty);
        // $stock_b_qty1 = \DB::table("barcode_stocks")->select(\DB::raw("sum(barcode_stocks.qty) as stock_b_qty"))->where("barcode_stocks.po_id","=", 31 )->get();
        // $stock_b_qty = $stock_b_qty1[0]->stock_b_qty;
        // $stock_b_qty = (int)$stock_b_qty;
        // var_dump($stock_b_qty);
        // $stock_b_qty = DB::table('barcode_stocks')->select('barcode_stocks.qty as stock_b_qty')->first()->stock_b_qty;
        //$stock_b_qty = (int)$stock_b_qty->stock_b_qty;
        // var_dump($stock_b_qty);

        //$test = DB::select(DB::raw('SELECT SUM(barcode_stocks.qty) as stock_b_qty FROM barcode_stocks WHERE barcode_stocks.po_id = 31'))->pluck('stock_b_qty');
        //dd($test);

        return view('datatables.index');
    }

    /**
     * Process datatables ajax request.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function anyData()
    {
        /*
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
        ->groupBy('pos.po','pos.size','pos.style','pos.color','pos.color_desc','pos.season','pos.total_order_qty','pos.flash','pos.closed_po','pos.created_at','pos.updated_at');
        */

        /*
        SELECT  pos.id,
        pos.po,
        pos.style,
        (SELECT SUM(barcode_stocks.qty)  FROM barcode_stocks WHERE barcode_stocks.po_id = pos.id ) stock_b,
        (SELECT SUM(barcode_requests.qty)  FROM barcode_requests WHERE barcode_requests.po_id = pos.id ) request_b,
        (SELECT SUM(carelabel_stocks.qty)  FROM carelabel_stocks WHERE carelabel_stocks.po_id = pos.id ) stock_c,
        (SELECT SUM(carelabel_requests.qty)  FROM carelabel_requests WHERE carelabel_requests.po_id = pos.id ) request_c
        FROM pos
        */

        //$stock_b_qty = 0; //\DB::raw("sum(barcode_stocks.qty) as stock_b_qty FROM barcode_stocks WHERE barcode_stocks.po_id = 1");
        //dd($stock_b_qty);


        $table = Po::select([
        //$table = DB::table('pos')->select(
            'pos.id',
            //'po_key',
            //'order_code',
            //'pos.po',
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

            /*
            $sales = DB::table('order_lines')
            ->join('orders', 'orders.id', '=', 'order_lines.order_id')
            ->select(DB::raw('sum(order_lines.quantity*order_lines.per_qty) AS total_sales'))
            ->where('order_lines.product_id', $product->id)
            ->where('orders.order_status_id', 4)
            ->first();
            */
            
            // DB::select(DB::raw('SELECT SUM(barcode_stocks.qty) as stock_b_qty FROM barcode_stocks WHERE barcode_stocks.po_id = 31')),
            // DB::select(DB::raw('SELECT SUM(barcode_requests.qty) as request_b_qty FROM barcode_requests WHERE barcode_requests.po_id = 31')),
            // DB::select(DB::raw('SELECT SUM(carelabel_stocks.qty) as stock_c_qty FROM carelabel_stocks WHERE carelabel_stocks.po_id = 31')),
            // DB::select(DB::raw('SELECT SUM(carelabel_requests.qty) as request_c_qty FROM carelabel_requests WHERE carelabel_requests.po_id = 31'))
            // 
            // DB::table('barcode_stocks')->select('barcode_stocks.qty as stock_b_qty')->join('pos','barcode_stocks.po_id', '=', 'pos.id')->sum('barcode_stocks.qty'),
            // DB::table('barcode_requests')->select('barcode_requests.qty as request_b_qty')->join('pos','barcode_requests.po_id', '=', 'pos.id')->sum('barcode_requests.qty'),
            // DB::table('carelabel_stocks')->select('carelabel_stocks.qty  as stock_c_qty')->join('pos','carelabel_stocks.po_id', '=', 'pos.id')->sum('carelabel_stocks.qty'),
            // DB::table('carelabel_requests')->select('carelabel_requests.qty as request_c_qty')->join('pos','carelabel_requests.po_id', '=', 'pos.id')->sum('carelabel_requests.qty')

            // DB::table('barcode_stocks')->select('barcode_stocks.qty as stock_b_qty')->sum('barcode_stocks.qty'),  // Invalid coloumn name
            // DB::table('barcode_requests')->select('barcode_requests.qty as request_b_qty')->sum('barcode_requests.qty'),
            // DB::table('carelabel_stocks')->select('carelabel_stocks.qty  as stock_c_qty')->sum('carelabel_stocks.qty'),
            // DB::table('carelabel_requests')->select('carelabel_requests.qty as request_c_qty')->sum('carelabel_requests.qty')

            // DB::table('barcode_stocks')->select(DB::raw('sum(barcode_stocks.qty) as stock_b_qty'))->sum('barcode_stocks.qty'),  // Invalid coloumn name
            // DB::table('barcode_requests')->select(DB::raw('sum(barcode_requests.qty) as request_b_qty'))->sum('barcode_requests.qty'),
            // DB::table('carelabel_stocks')->select(DB::raw('sum(carelabel_stocks.qty)  as stock_c_qty'))->sum('carelabel_stocks.qty'),
            // DB::table('carelabel_requests')->select(DB::raw('sum(carelabel_requests.qty) as request_c_qty'))->sum('carelabel_requests.qty')

            //DB::table('barcode_stocks')->select(DB::raw('sum(barcode_stocks.qty) as stock_b_qty'))->where('barcode_stocks.po_id','=','pos.id')->get(),
            //DB::table('barcode_requests')->select(DB::raw('sum(barcode_requests.qty) as request_b_qty'))->where('barcode_requests.po_id','=','pos.id')->get(),
            //DB::table('carelabel_stocks')->select(DB::raw('sum(carelabel_stocks.qty)  as stock_c_qty'))->where('carelabel_stocks.po_id','=','pos.id')->get(),
            //DB::table('carelabel_requests')->select(DB::raw('sum(carelabel_requests.qty) as request_c_qty'))->where('carelabel_requests.po_id','=','pos.id')->get(),

            // DB::raw('sum(barcode_stocks.qty) as stock_b_qty')->leftjoin('barcode_stocks','barcode_stocks.po_id','=','pos.id')->groupBy(''),
            // DB::raw('sum(barcode_requests.qty) as request_b_qty')->leftjoin('barcode_requests','barcode_requests.po_id','=','pos.id')->groupBy(''),
            // DB::raw('sum(carelabel_stocks.qty)  as stock_c_qty')->leftjoin('carelabel_stocks','carelabel_stocks.po_id','=','pos.id')->groupBy(''),
            // DB::raw('sum(carelabel_requests.qty) as request_c_qty')->leftjoin('carelabel_requests','carelabel_requests.po_id','=','pos.id')->groupBy('')

            DB::raw('sum(barcode_stocks.qty) as stock_b_qty'),
            DB::raw('sum(barcode_requests.qty) as request_b_qty'),
            DB::raw('sum(carelabel_stocks.qty)  as stock_c_qty'),
            DB::raw('sum(carelabel_requests.qty) as request_c_qty')

            // DB::raw('SUM(barcode_stocks.qty) over (partition by pos.id)'),
            // DB::raw('SUM(barcode_requests.qty) over (partition by pos.id)'),
            // DB::raw('SUM(carelabel_stocks.qty) over (partition by pos.id)'),
            // DB::raw('SUM(carelabel_requests.qty) over (partition by pos.id)')

            // DB::table('barcode_stocks')->select(DB::raw('sum(barcode_stocks.qty) as stock_b_qty'))->join('pos','barcode_stocks.po_id', '=', 'pos.id')->groupBy('barcode_stocks.qty')->get(),
            // DB::table('barcode_requests')->select(DB::raw('sum(barcode_requests.qty) as request_b_qty'))->join('pos','barcode_requests.po_id', '=', 'pos.id')->groupBy('barcode_stocks.qty')->get(),
            // DB::table('carelabel_stocks')->select(DB::raw('sum(carelabel_stocks.qty)  as stock_c_qty'))->join('pos','carelabel_stocks.po_id', '=', 'pos.id')->groupBy('barcode_stocks.qty')->get(),
            // DB::table('carelabel_requests')->select(DB::raw('sum(carelabel_requests.qty) as request_c_qty'))->join('pos','carelabel_requests.po_id', '=', 'pos.id')->groupBy('barcode_stocks.qty')->get(),

        ])
        //);//->get();

        ->leftjoin('barcode_stocks','barcode_stocks.po_id','=','pos.id')
        ->leftjoin('barcode_requests','barcode_requests.po_id','=','pos.id')
        ->leftjoin('carelabel_stocks','carelabel_stocks.po_id','=','pos.id')
        ->leftjoin('carelabel_requests','carelabel_requests.po_id','=','pos.id')
        
        // ->where('barcode_stocks.po_id', 'pos.id')
        // ->where('barcode_requests.po_id', 'pos.id')
        // ->where('carelabel_stocks.po_id', 'pos.id')
        // ->where('carelabel_requests.po_id', 'pos.id')
        
        ->groupBy('pos.id','pos.po','pos.size','pos.style','pos.color','pos.color_desc','pos.season','pos.total_order_qty');
        
               

        //dd($table);

        return Datatables::of($table)
        //->editColumn('created_at', '{!! $total_order_qty-$stock_qty !!}')
        // ->addColumn('stock_b_qty', '{!! $stock_b_qty = 0; !!}')
        // ->addColumn('request_b_qty', '{!! $request_b_qty = 0; !!}')
        // ->addColumn('stock_c_qty', '{!! $stock_c_qty = 0; !!}')
        // ->addColumn('request_c_qty', '{!! $request_c_qty = 0; !!}')

        //->addColumn('stock_b_qty', "{!! $stock_b_qty = DB::table('barcode_stocks')->select(DB::raw('sum(barcode_stocks.qty) as stock_b_qty')->where('barcode_stocks.po_id','=', $id))->get(); !!} ")
        /*$stock_b_qty1 = \DB::table("barcode_stocks")->select(\DB::raw("sum(barcode_stocks.qty) as stock_b_qty"))->where("barcode_stocks.po_id","=", 31 )->get();
            $stock_b_qty = $stock_b_qty1[0]->stock_b_qty;*/
        // ->addColumn('stock_b_qty', '{!!
        //    $stock_b_qty = DB::table("barcode_stocks")->select("barcode_stocks.qty as stock_b_qty")->join("pos","barcode_stocks.po_id", "=", $id)->sum("barcode_stocks.qty");
        //    var_dump($stock_b_qty);

        //     !!} ')
        // ->addColumn('request_b_qty', '{!! $request_b_qty = 0; !!}')
        // ->addColumn('stock_c_qty', '{!! $stock_c_qty = 0; !!}')
        // ->addColumn('request_c_qty', '{!! $request_c_qty = 0; !!}')



        ->addColumn('b_stock', '{!! $total_order_qty-$stock_b_qty !!}')
        ->addColumn('b_request', '{!! $stock_b_qty-$request_b_qty !!}')
        ->addColumn('c_stock', '{!! $total_order_qty-$stock_c_qty !!}')
        ->addColumn('c_request', '{!! $stock_c_qty-$request_c_qty !!}')

        ->make(true);
    }
}
