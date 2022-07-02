<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class User_Form extends Model
{
    use HasFactory;
    protected $table = 'user_forms';
    protected $primaryKey = 'id';
    public $timestamps = true;
    
    protected $fillable = [
        'user_id',
        'form_id',
        'unique_str',
        'progress_status',
        'started_at',
        'ended_at',
        'active'
    ];
}
