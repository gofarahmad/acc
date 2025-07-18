<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Box extends Model
{
    protected $fillable = ['name'];

    public function accounts()
    {
        return $this->hasMany(Account::class, 'box_id');
    }
}
