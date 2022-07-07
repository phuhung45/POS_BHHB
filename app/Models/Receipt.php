<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Receipt extends Model
{
    use HasFactory;
    protected $fillable = [
        'image',
        'price',
        'status'
    ];
    public function user()
    {
        return $this->hasMany(User::class);
    }
}
