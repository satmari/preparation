<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\User;
use yajra\Datatables\Datatables;

use Request;
use App\MainModel;
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
        //return Datatables::of(User::select('*'))->make(true);
        //return Datatables::of(MainModel::all())->make(true);

        $table = MainModel::select([
            //'id',
            //'po_size',
            //'order_code',
            'po',
            'size',
            'style',
            'color',
            'color_desc',
            'season',
            'total_order_qty',
            'flash',
            'closed_po',
            //'created_at',
            //'updated_at'
        ]);

        return Datatables::of($table)->make(true);
    }
}
