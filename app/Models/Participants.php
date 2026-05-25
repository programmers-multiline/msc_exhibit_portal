<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Participants extends Model
{
    use HasFactory;

    protected $fillable = [
    'company_id',
    'exhibit_name',
    'entry_by',
    'day_num',
    'level_type',
    'participant_type',
    'participant_name',
    'participant_email',
    'participant_company',
    'participant_position',
    'participant_contact',
    'participant_source',
    'participant_address',
    'participant_remarks',
    'participant_photo',
    'entry_from',
    'assigned_psc',
    'last_update_date',
    'status',
    'description',
    'image_name',
    'city_province_code',
    'address'
    ];

  

    protected $table = 'participants';

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }


      
        public function images()
        {
            return $this->hasMany(ParticipantImage::class, 'participant_id', 'id');
        }

       public function updates() {
            return $this->hasMany(ParticipantsUpdate::class, 'participant_id', 'id');
        }
        public function latestUpdate()
    {
        return $this->hasOne(\App\Models\ParticipantsUpdate::class, 'participant_id')
                    ->latest('update_date'); // pinaka-bagong entry
    }

        public function files()
        {
            return $this->hasMany(ParticipantFile::class, 'participant_id');
        }

       

}
