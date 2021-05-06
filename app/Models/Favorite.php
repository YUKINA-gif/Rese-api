<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Favorite extends Model
{
    use HasFactory;
    use SoftDeletes;

    const DELETED_AT = "is_favorite";

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    protected $fillable = [
        'store_id',
        'user_id'
    ];
}
