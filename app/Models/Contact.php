<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use HasFactory;
     protected $table = 'contacts';
     protected $fillable = ['entry_by','exhibit_name','date', 'time', 'name','company_id', 'company', 'title', 'phone', 'email','remarks'];
}
