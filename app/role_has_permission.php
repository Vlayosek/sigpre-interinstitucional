<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class role_has_permission extends Model
{
    public $timestamps = false;
    protected $table = 'core.role_has_permission';
    protected $connection = 'pgsql_presidencia';
    protected $primaryKey = ['permission_id','role_id'];
    protected $fillable = ['permission_id',
        'role_id',

    ];
}
