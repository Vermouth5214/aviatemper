<?php 

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Lokasi extends Model {
	protected $table = 'lokasi';
	protected $hidden = ['created_at', 'updated_at'];
}