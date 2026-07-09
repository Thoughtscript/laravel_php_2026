<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

// Creates examples table
class Example extends Model
{
    use HasFactory;

    // Database mass assignment safe
    protected $fillable = [
        'name',
        'note'
    ];
}
