<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class BarcodeRequest extends Model {

	//
	protected $table = 'barcode_requests';

	protected $fillable = ['ponum','size','qty','module','leader','status','type','comment'];

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
