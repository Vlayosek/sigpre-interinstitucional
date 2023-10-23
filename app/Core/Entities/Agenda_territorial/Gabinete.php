<?php

namespace App\Core\Entities\Agenda_territorial;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Gabinete extends Model
{
    protected $table = 'core.gabinetes';
    protected $connection = 'pgsql_presidencia';

    public $timestamps = false;
 
}
