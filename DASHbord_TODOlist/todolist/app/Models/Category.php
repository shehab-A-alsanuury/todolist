<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    // Add 'user_id' to the fillable array
    protected $fillable = ['name', 'color', 'user_id'];

    public function todos()
    {
        return $this->hasMany(Todo::class);
    }

    // Define the relationship to the User model
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
}
