<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


use App\User;
use App\Models\PostsCategories;
use App\Models\Pages;
use App\Models\Posts;
use App\Models\Faq;
use App\Models\Settings;
use App\Models\Currency;
use App\Models\Governorate;
use App\Models\Area;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Tag;
use App\Models\Character;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // trancate tables
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        User::truncate();
        PostsCategories::truncate();
        Posts::truncate();
        Pages::truncate();
        Faq::truncate();
        Settings::truncate();
        Currency::truncate();
        Area::truncate();
        Governorate::truncate();
        Category::truncate();


        DB::statement('SET FOREIGN_KEY_CHECKS=1;');



  
        // users seeder
        factory(User::class, 50)->create();
        //Post Caregories Seeder
        factory(PostsCategories::class, 5)->create(); 
        //Posts Seeder
        factory(Posts::class, 5)->create();   
        // Pages seeder
        factory(Pages::class, 5)->create();
        // Faq seeder
        factory(Faq::class, 10)->create();   
        // Currency seeder
        factory(Currency::class, 1)->create();        
        // Settings seeder
        factory(Settings::class, 1)->create();
        // Governorate seeder
        factory(Governorate::class, 1)->create();    
         // Area seeder
        factory(Area::class, 1)->create();
        // Category seeder
        factory(Category::class, 5)->create();
        // Tag seeder

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
