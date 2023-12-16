<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PsfLogModel extends Model
{
    use HasFactory;

    protected $table = 'psf_info_logs';

    protected $guarded = [];
}
