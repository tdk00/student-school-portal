<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class School extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $fillable = ['name', 'email', 'password', 'profile_details'];

    // Relationship with teachers
    public function teachers()
    {
        return $this->hasMany(Teacher::class);
    }

    // Relationship with students
    public function students()
    {
        return $this->hasMany(Student::class);
    }

    // Relationship with classes (if applicable)
    public function classes()
    {
        return $this->hasMany(SchoolClass::class);
    }
}
