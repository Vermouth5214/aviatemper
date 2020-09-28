<?php 

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Temperature extends Model {
	protected $table = 'temperature';
	protected $hidden = ['created_at', 'updated_at'];
}