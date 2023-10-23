<?php

namespace App\Core\Entities\Admin;

use Illuminate\Database\Eloquent\Model;

class Auditable extends Model
{
    protected $table = 'core.audits';
    protected $connection = 'pgsql_presidencia';
    public $timestamps = false;

}
