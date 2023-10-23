<?php

namespace App\Core\Entities\Admin;

use Illuminate\Database\Eloquent\Model;

class IPS extends Model
{
    protected $table = 'core.ips';
    protected $connection = 'pgsql_presidencia';
    public $timestamps = false;

}
