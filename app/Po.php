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

    public function barcode_requests()
    {
        //return $this->hasMany('App\BarcodeRequests','po_id');
        return $this->hasMany('App\BarcodeRequests');
    }

    public function carelabel_stocks()
    {
        //return $this->hasMany('App\CarelabelStock','po_id');
        return $this->hasMany('App\CarelabelStock');
    }

    public function carelabel_requests()
    {
        //return $this->hasMany('App\CarelabelRequests','po_id');
        return $this->hasMany('App\CarelabelRequests');
    }

}
