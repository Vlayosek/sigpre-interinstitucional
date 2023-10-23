<?php

namespace App\Core\Entities\Admin;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $table = 'core.roles';
    protected $connection = 'pgsql_presidencia';
    protected $fillable = [
        'name',
        'guard_name',
        'created_at',
        'updated_at',
        'abv',
        'max_student'
];
}
