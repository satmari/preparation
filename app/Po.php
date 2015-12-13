<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Po extends Model {

	//
	protected $table = 'pos';
    protected $fillable = ['po_key','order_code','po','size','style','color','color_desc','season','total_order_qty','flash','closed_po','status','type','comment'];

    public function barcode_stocks()
    {
        //return $this->hasMany('App\BarcodeStock','po_id');
        return $this->hasMany('App\BarcodeStock');
    }

    // use Sortable;

    // /**
    //  * The attributes which may be used for sorting dynamically.
    //  *
    //  * @var array
    //  */
    // protected $sortable = ['po_size','order_code','po','size','style','color','color_desc','season','total_order_qty','flash','closed_po','status','comment'];

}
