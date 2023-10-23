<?php

namespace App\Core\Entities\Agenda_territorial;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Origen extends Model
{
    protected $table = 'sc_agenda_territorial.origenes';
    protected $connection = 'pgsql_presidencia';
    public $timestamps = false;
}
