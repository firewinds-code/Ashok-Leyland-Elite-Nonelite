<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CTITICKET extends Model
{
    use HasFactory;

    protected $table = 'cti_ticket';

    protected $fillable = ['ticket_number', 'remarks', 'updated_by_name', 'contact_number', 'role'];
}
