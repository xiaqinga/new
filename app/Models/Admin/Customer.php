<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $table='member_customer';
    public $timestamps = false;
    public function SilverScore()
    {
        return $this->hasOne('App\Models\Admin\MemberWallet','customerId');
    }
}
