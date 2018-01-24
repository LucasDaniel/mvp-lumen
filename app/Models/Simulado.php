<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Model for simulado table
 * 
*/
final class Simulado extends Model
{
	protected $table      = 'simulado';
	protected $primaryKey = 'id';
	public    $timestamps = false;
	protected $fillable   = ['banca','assunto'];
}