<?php

namespace App\Core\Entities\Admin;

use Illuminate\Database\Eloquent\Model;

class Gobierno extends Model
{
    protected $table = 'core.gobiernos';
    protected $connection = 'pgsql_presidencia';
    public $timestamps = false;

}
