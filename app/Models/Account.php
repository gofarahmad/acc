<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Account extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'full_name',
        'first_name',
        'last_name',
        'date',
        'month',
        'year',
        'gender',
        'email',
        'password',
        'user_id',
        'box_id',
    ];

    protected function casts()
    {
        return [];
    }

    public function getDecryptedPassword()
    {
        if (empty($this->password)) {
            return null;
        }

        if (empty(session('__app_encryption_key'))) {
            return null;
        }

        return decrypt_with_password($this->password, session('__app_encryption_key'));
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function box()
    {
        return $this->belongsTo(Box::class, 'box_id');
    }

    protected static function booted()
    {
        static::creating(function ($account) {
            if (empty($account->user_id)) {
                $account->user_id = Auth::id();
            }
        });
    }
}
