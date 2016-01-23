<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class BarcodeStock extends Model {

	//
	protected $table = 'barcode_stocks';
    protected $fillable = ['ponum','size','qty','module','status','type','comment'];

    public function po()
    {
        //return $this->belongsTo('App\PoModel','id');
        return $this->belongsTo('App\Po');
    }

    public function user()
    {
        //return $this->belongsTo('App\User','id');
        return $this->belongsTo('App\User');
    }

}
