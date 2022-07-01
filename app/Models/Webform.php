<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Webform extends Model
{
    use HasFactory;
    protected $fillable = [
        'unique_str',
        'form_name',
        'created_id',
        'company_id',
        'active'
    ];
}
