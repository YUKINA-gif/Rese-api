<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    use HasFactory;
    public function areas()
    {
        return $this->belongsTo(Area::class);
    }

    public function genres()
    {
        return $this->belongsTo(Genre::class);
    }
}
