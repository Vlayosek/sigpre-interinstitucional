<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
class Menu extends Model
{
    public $timestamps = true;
    protected $table = 'core.menus';
    protected $connection = 'pgsql_presidencia';
    protected $primaryKey = 'id';
    protected $fillable = ['name',
        'slug',
        'parent',
        'order',
        'enabled',
        'created_at',
        'updated_at'
   ];

    public function getChildren($data, $line)
    {
        $children = [];
        foreach ($data as $line1) {
            if ($line['id'] == $line1['parent']) {
                $children = array_merge($children, [ array_merge($line1, ['submenu' => $this->getChildren($data, $line1) ]) ]);
            }
        }
        return $children;
    }
    public function optionsMenu()
    {
        $user = Auth::user()==null?0:Auth::user()->id;

        $result=DB::connection('pgsql_presidencia')
            ->table('core.role_has_permission as r')
            ->join('core.model_has_roles as k','k.role_id','r.role_id')
            ->join('core.menus as m','m.id','r.permission_id')
            ->where('k.model_id',$user)
            ->where('enabled', '1')
            ->select('m.name', 'm.id')
            ->orderby('m.name','asc')
            ->pluck('m.id')->toArray();


         return $this->where('enabled', 1)
            ->orderby('parent')
            ->orderby('name')
            ->orderby('order')
             ->whereIn('id', $result)
             ->get()
            ->toArray();


    }
    public static function menus()
    {
        $menus = new Menu();
        $data = $menus->optionsMenu();
        $menuAll = [];

        foreach ($data as $line) {
            $item = [ array_merge($line, ['submenu' => $menus->getChildren($data, $line) ]) ];
            $menuAll = array_merge($menuAll, $item);
        }
        return $menus->menuAll = $menuAll;
    }


}