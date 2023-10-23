<?php

namespace App\Core\Entities\Agenda_territorial;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Tipo extends Model
{
    protected $table = 'sc_agenda_territorial.tipos';
    protected $connection = 'pgsql_presidencia';

    public $timestamps = false;
 
}
