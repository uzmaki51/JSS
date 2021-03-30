<?php
/**
 * Created by PhpStorm.
 * User: Cmb
 * Date: 2017/4/10
 * Time: 10:00
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class Menu extends Model
{
    protected $table = 'tb_menu';

    public static function getSubTitleAndController($menu, $userId) {

    }

    public function userMenus()
    {
        return $this->hasOne('App\Users');
    }

    public function getMenuList() {
    	$records = self::all();

    	$datas = array();
    	foreach ($records as $index => $record) {
    		$datas[$record->parentId][] = $record;
	    }

	    $menus = array();
    	foreach ($datas[0] as $index => $data) {
    		$menus[] = array(
    			'id'            => $data->id,
    			'title'         => $data->title,
    			'parent'        => $data->parentId,
			    'is_admin'      => $data->admin,
			    'controller'    => $data->controller,
			    'children'      => array(),
			    'ids'           => array(),
		    );
	    }
	    foreach ($menus as $index => $menu) {
	    	$ret = $this->generateMenu($menu['id'], $menu, $datas);

		    $ids = array();
		    $ret = $this->getChildrenIds($menu['id'], $ids, $datas);
			$menus[$index] = $menu;
			$tmp = [];
			foreach($ids as $key => $item)
				foreach($item as $value)
					$tmp[] = is_array($value) ? $value[0] : $value;

			$tmp[] = $menu['id'];
		    $menus[$index]['ids'] += $tmp;
	    }

	    return $menus;
    }

    public function generateMenu($parent, &$menus, $datas) {
		if (!isset($datas[$parent])) return 1;

    	foreach ($datas[$parent] as $index => $data) {
    		$child = array(
    			'id'            => $data->id,
			    'title'         => $data->title,
			    'parent'        => $data->parentId,
			    'is_admin'      => $data->admin,
			    'controller'    => $data->controller,
				'children'		=> array(),
			);

    		$ret = $this->generateMenu($data->id, $child, $datas);
			$menus['children'][] = $child;
	    }

	    return 1;
    }


	public function getChildrenIds($parent, &$ids, $datas) {
		if (!isset($datas[$parent])) return 1;

		foreach ($datas[$parent] as $index => $data) {
			$child = [$data->id];

			$ret = $this->getChildrenIds($data->id, $child, $datas);
			$ids[] = $child;
		}

		return 1;
	}
}