<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

    class SchoolClass extends Model
    {
        use HasFactory;

        protected $fillable = ['name', 'school_id', 'teacher_id'];

        public function school()
        {
            return $this->belongsTo(School::class);
        }

        public function teacher()
        {
            return $this->belongsTo(Teacher::class);
        }

        public function students()
        {
            return $this->hasMany(Student::class);
        }
    }
