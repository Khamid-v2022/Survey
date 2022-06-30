<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

    protected $table = 'companies';
    protected $primaryKey = 'id';
    public $timestamps = true;
    protected $fillable = ['company_name', 'first_name', 'last_name', 'chamber_commerce', 'city', 'email', 'tel', 'active'];
}
