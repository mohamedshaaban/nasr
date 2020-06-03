<?php

use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('admin_menu')->truncate();
        $adminMenus = array(
            array('parent_id' => '0', 'order' => '1','title'=>'Index','icon'=>'fa-bar-chart','uri'=>'/','permission'=>''),
            array('parent_id' => '0', 'order' => '2','title'=>'Admin','icon'=>'fa-tasks','uri'=>'','permission'=>''),
            array('parent_id' => '2', 'order' => '3','title'=>'Users','icon'=>'fa-users','uri'=>'auth/users','permission'=>''),
            array('parent_id' => '2', 'order' => '4','title'=>'Roles','icon'=>'fa-user','uri'=>'auth/roles','permission'=>''),
            array('parent_id' => '2', 'order' => '5','title'=>'Permission','icon'=>'fa-ban','uri'=>'auth/permissions','permission'=>''),
            array('parent_id' => '2', 'order' => '6','title'=>'Menu','icon'=>'fa-bars','uri'=>'auth/menu','permission'=>''),
            array('parent_id' => '2', 'order' => '7','title'=>'Operation log','icon'=>'fa-history','uri'=>'auth/logs','permission'=>''),
            array('parent_id' => '0', 'order' => '9','title'=>'Exception Reporter','icon'=>'fa-bug','uri'=>'exceptions','permission'=>''),
            array('parent_id' => '0', 'order' => '10','title'=>'CMS','icon'=>'fa-bug','uri'=>'exceptions','permission'=>''),
            array('parent_id' => '9', 'order' => '11','title'=>'Posts Categories','icon'=>'fa-bug','uri'=>'posts_categories','permission'=>''),
            array('parent_id' => '9', 'order' => '12','title'=>'Posts','icon'=>'fa-bug','uri'=>'posts','permission'=>''),
            array('parent_id' => '9', 'order' => '13','title'=>'Pages','icon'=>'fa-bug','uri'=>'pages','permission'=>''),
            array('parent_id' => '9', 'order' => '14','title'=>'Sliders','icon'=>'fa-bug','uri'=>'sliders','permission'=>''),
            array('parent_id' => '9', 'order' => '15','title'=>'Faqs','icon'=>'fa-bug','uri'=>'faqs','permission'=>''),
            array('parent_id' => '0', 'order' => '16','title'=>'Settings','icon'=>'fa-bug','uri'=>'settings','permission'=>''),
        );

        $insertSql = [];
        foreach ($adminMenus as $key => $adminMenu) {
            $insertSql[] = "( \"" . implode('", "', $adminMenu) . '")';
        }

        DB::insert(
            "insert into admin_menu (`parent_id`, `order`, `title`,`icon`,`uri`,`permission`) values " .
                implode(', ', $insertSql) 

        );
    }
}
