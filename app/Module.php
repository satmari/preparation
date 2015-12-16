<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Module extends Model {

	//
	protected $table = 'modules';
    protected $fillable = ['module','group','line_leader'];

}
