<?php

namespace App\Core\Entities\Admin;

use Illuminate\Database\Eloquent\Model;

class Webservice extends Model
{
    protected $table = 'core.webservice';
    protected $connection = 'pgsql_presidencia';
}
