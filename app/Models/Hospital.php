<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hospital extends Model
{
    use HasFactory;

    // Kolom yang dapat diisi mass-assignable
    protected $fillable = [
        'name', 'address', 'latitude', 'longitude', 'description', 'type'
    ];

    // Relasi one-to-many dengan Review
    public function reviews()
    {
        return $this->hasMany(Review::class); // Satu rumah sakit memiliki banyak review
    }
}
