<?php 

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model {
	protected $table = 'karyawan';
	protected $hidden = ['created_at', 'updated_at'];
}