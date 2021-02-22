<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    //------------------------------- Relationships ------------------------------
    public function Tasks()
    {
        return $this->hasMany(Task::class);
    }
}
