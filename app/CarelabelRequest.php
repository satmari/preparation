<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class CarelabelRequest extends Model {

	//
	protected $table = 'carelabel_requests';

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
