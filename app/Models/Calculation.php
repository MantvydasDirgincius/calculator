<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Calculation extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'operation', 'numbers', 'result'];

    /**
     * Aksesorius, kad automatiškai dekoduotų JSON `numbers` stulpelį.
     */
    protected $casts = [
        'numbers' => 'array', // Automatiškai konvertuoja iš JSON į PHP masyvą
    ];
}
