<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExhibitName extends Model
{
     use HasFactory;
    protected $table = 'exhibit_names';
    protected $fillable = ['exhibit_name', 'exhibit_status'];
}
