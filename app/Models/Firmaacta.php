<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Firmaacta extends Model
{
    protected $table = 'firma_actas';
    protected $fillable = ['iduser','idacta'];
    public $timestamps = false;
}
