<?php namespace App;

use Illuminate\Database\Eloquent\Model;

use Gbrock\Table\Traits\Sortable;

class MainModel extends Model {

	//
	protected $table = 'main';
    protected $fillable = ['po_size','order_code','po','size','style','color','color_desc','season','total_order_qty','flash','closed_po','status','comment'];


    // use Sortable;

    // /**
    //  * The attributes which may be used for sorting dynamically.
    //  *
    //  * @var array
    //  */
    // protected $sortable = ['po_size','order_code','po','size','style','color','color_desc','season','total_order_qty','flash','closed_po','status','comment'];
    
}
