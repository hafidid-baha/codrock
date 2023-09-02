<?php

namespace App\Controllers;

use Sober\Controller\Controller;

use function App\asset_path;

class TemplateCollections extends Controller
{
    public function GetCollections()
    {
        global $wpdb;

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        $collections_table = $wpdb->prefix . "collections";
        $collections = $wpdb->get_results("select * from $collections_table");

        return $collections;
    }

    public function GetDefaultCover()
    {
        return asset_path('images/product-image-placeholder.jpg');
    }
}
