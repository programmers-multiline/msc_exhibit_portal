<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeadStatusUpdate extends Model
{
    use HasFactory;
    protected $table = 'lead_agent_status';

    protected $fillable = [
        'lead_status',
        'description',
        'id ',
        'line_status'
    ];
}
