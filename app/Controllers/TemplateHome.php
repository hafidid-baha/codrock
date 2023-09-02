<?php

namespace App\Controllers;

use Sober\Controller\Controller;

class TemplateHome extends Controller
{
    public function GetCollections()
    {
        global $wpdb;

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        $collections_table = $wpdb->prefix . "collections";
        $products_table = $wpdb->prefix . "products";
        $collections = $wpdb->get_results("select * from $collections_table");

        for ($i = 0; $i < count($collections); $i++) {
            $collections[$i]->products = $wpdb->get_results("select * from $products_table where collection_id = " . $collections[$i]->id);
        }
        $collections = array_filter($collections, function ($c) {
            if (count($c->products) > 0) {
                return true;
            }
            return false;
        });
        return $collections;
    }

    public static function getCollectionProducts($collection)
    {
        global $wpdb;
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        $products_table = $wpdb->prefix . "products";
        $products = $wpdb->get_results("select * from $products_table where collection_id=$collection");

        return $products;
    }

    public static function getCollectionById($collection)
    {
        global $wpdb;
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        $collections_table = $wpdb->prefix . "collections";
        $collection = $wpdb->get_results("select * from $collections_table where id=$collection limit 1");

        return $collection;
    }
}
