<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactsUpdate extends Model
{
    use HasFactory;
    protected $table = 'contacts_update';

    protected $fillable = [
        'company_id',
        'status',
        'description',
        'updated_by',
        'assigned_psc',
        'update_date',
        'uploaded_file'
        
    ];

     public function contact()
    {
        return $this->belongsTo(Contact::class, 'company_id');
    }

    public function latestUpdate()
{
    return $this->hasOne(\App\Models\ContactsUpdate::class, 'company_id')
                ->latest('update_date'); // or 'created_at' kung yun ang gusto mong base
}

public function files()
{
    return $this->hasMany(Contact::class, 'company_id');
}
}
