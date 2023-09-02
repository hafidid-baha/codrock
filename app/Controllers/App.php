<?php

namespace App\Controllers;

use Sober\Controller\Controller;

class App extends Controller
{
    public function siteName()
    {
        return get_bloginfo('name');
    }

    public static function title()
    {
        if (is_home()) {
            if ($home = get_option('page_for_posts', true)) {
                return get_the_title($home);
            }
            return __('Latest Posts', 'sage');
        }
        if (is_archive()) {
            return get_the_archive_title();
        }
        if (is_search()) {
            return sprintf(__('Search Results for %s', 'sage'), get_search_query());
        }
        if (is_404()) {
            return __('Not Found', 'sage');
        }
        return get_the_title();
    }

    public function GetFooterMenu()
    {
        $footerMenu = wp_get_theme()->name . ' Footer Menu';
        $menu = wp_get_nav_menu_object($footerMenu);
        $menu_items = wp_get_nav_menu_items($menu);
        foreach ($menu_items as $item) {
            $item->parent_menu = intval(get_post_meta($item->ID, "_menu_item_menu_item_parent")[0]);
            $item->sub_menus = array();
        }
        $items = [];
        for ($i = 0; $i < count($menu_items); $i++) {
            if ($menu_items[$i]->parent_menu == 0) {
                $items[$menu_items[$i]->ID] = $menu_items[$i];
            } else if ($menu_items[$i]->parent_menu > 0) {
                if (array_key_exists($menu_items[$i]->parent_menu, $items)) {
                    array_push($items[$menu_items[$i]->parent_menu]->sub_menus, $menu_items[$i]);
                    // echo '<pre>';
                    // echo var_dump($menu->sub_menus);
                    // echo '</pre>';
                }
            }
        }
        return $items;
    }
}
