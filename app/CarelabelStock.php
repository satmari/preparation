<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class CarelabelStock extends Model {

	//
	protected $table = 'carelabel_stocks';
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
