<?php

namespace App\Controllers;

use Sober\Controller\Controller;

class TemplateProducts extends Controller
{

    public static function getProduct($product_id)
    {
        global $wpdb;
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        $products_table = $wpdb->prefix . "products";
        $products = $wpdb->get_results("select * from $products_table where id=$product_id");

        return array_shift($products);
    }

    public static function getReview($product_id)
    {
        global $wpdb;
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        $reviews_table = $wpdb->prefix . "reviews";
        $reviews = $wpdb->get_results("select * from $reviews_table where product_id=$product_id AND status = 1");

        return $reviews;
    }
}
