<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use MongoDB\Laravel\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $connection = 'mongodb';
    protected $collection = 'users';

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'role',
        'calling_code',
        'phone_number',
        'verify_otp',
        'active_on',
        'merchant_id',
        'branch_ids',
    ];

    protected $hidden = [
        'password',
    ];

    public function image()
    {
        return $this->hasOne(Image::class, 'object_id', '_id')->whereNull('deleted_at');
    }

    public function merchant()
    {
        return $this->hasOne(Store::class, '_id', 'merchant_id')->whereNull('deleted_at');
    }

    public function getBranches()
    {
        return $this->branch_ids ? Store::whereIn('_id', $this->branch_ids)
            ->whereNotNull('parent_id')
            ->whereNull('deleted_at')
            ->get() : collect([]);
    }

    public function getFullName()
    {
        return $this->first_name." ".$this->last_name;
    }

    public function getActiveBranch()
    {
        return Store::find($this->active_on);
    }
}
