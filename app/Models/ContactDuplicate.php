<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactDuplicate extends Model
{
    use HasFactory;
    protected $table = 'contact_duplicate';
    protected $fillable = ['entry_by','exhibit_name','date', 'time', 'name', 'company', 'title', 'phone', 'email'];
}
