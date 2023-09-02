<?php

namespace App;

use Roots\Sage\Container;
use Roots\Sage\Assets\JsonManifest;
use Roots\Sage\Template\Blade;
use Roots\Sage\Template\BladeProvider;

/**
 * Theme assets
 */
add_action('wp_enqueue_scripts', function () {
    // wp_enqueue_style('sage/main.css', asset_path('styles/main.css'), false, null);
    // wp_enqueue_script('sage/main.js', asset_path('scripts/main.js'), ['jquery'], null, true);

    if (is_single() && comments_open() && get_option('thread_comments')) {
        wp_enqueue_script('comment-reply');
    }
}, 100);

/**
 * Theme setup
 */
add_action('after_setup_theme', function () {
    /**
     * Enable features from Soil when plugin is activated
     * @link https://roots.io/plugins/soil/
     */
    add_theme_support('soil-clean-up');
    add_theme_support('soil-jquery-cdn');
    add_theme_support('soil-nav-walker');
    add_theme_support('soil-nice-search');
    add_theme_support('soil-relative-urls');

    /**
     * Enable plugins to manage the document title
     * @link https://developer.wordpress.org/reference/functions/add_theme_support/#title-tag
     */
    add_theme_support('title-tag');

    /**
     * Register navigation menus
     * @link https://developer.wordpress.org/reference/functions/register_nav_menus/
     */
    register_nav_menus([
        'primary_navigation' => __('Primary Navigation', 'sage')
    ]);

    /**
     * Enable post thumbnails
     * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
     */
    add_theme_support('post-thumbnails');

    /**
     * Enable HTML5 markup support
     * @link https://developer.wordpress.org/reference/functions/add_theme_support/#html5
     */
    add_theme_support('html5', ['caption', 'comment-form', 'comment-list', 'gallery', 'search-form']);

    /**
     * Enable selective refresh for widgets in customizer
     * @link https://developer.wordpress.org/themes/advanced-topics/customizer-api/#theme-support-in-sidebars
     */
    add_theme_support('customize-selective-refresh-widgets');

    /**
     * Use main stylesheet for visual editor
     * @see resources/assets/styles/layouts/_tinymce.scss
     */
    add_editor_style(asset_path('styles/main.css'));
}, 20);

/**
 * Register sidebars
 */
add_action('widgets_init', function () {
    $config = [
        'before_widget' => '<section class="widget %1$s %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h3>',
        'after_title'   => '</h3>'
    ];
    register_sidebar([
        'name'          => __('Primary', 'sage'),
        'id'            => 'sidebar-primary'
    ] + $config);
    register_sidebar([
        'name'          => __('Footer', 'sage'),
        'id'            => 'sidebar-footer'
    ] + $config);
});

/**
 * Updates the `$post` variable on each iteration of the loop.
 * Note: updated value is only available for subsequently loaded views, such as partials
 */
add_action('the_post', function ($post) {
    sage('blade')->share('post', $post);
});

/**
 * Setup Sage options
 */
add_action('after_setup_theme', function () {
    /**
     * Add JsonManifest to Sage container
     */
    sage()->singleton('sage.assets', function () {
        return new JsonManifest(config('assets.manifest'), config('assets.uri'));
    });

    /**
     * Add Blade to Sage container
     */
    sage()->singleton('sage.blade', function (Container $app) {
        $cachePath = config('view.compiled');
        if (!file_exists($cachePath)) {
            wp_mkdir_p($cachePath);
        }
        (new BladeProvider($app))->register();
        return new Blade($app['view']);
    });

    /**
     * Create @asset() Blade directive
     */
    sage('blade')->compiler()->directive('asset', function ($asset) {
        return "<?= " . __NAMESPACE__ . "\\asset_path({$asset}); ?>";
    });
});

/**
 * create dummy table after activating our theme
 */
add_action("after_switch_theme", "App\create_orders_table");
add_action("after_switch_theme", "App\create_theme_pages");

// function to create a test database table
function create_orders_table()
{
    global $wpdb;

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

    $orders_table = $wpdb->prefix . "orders";
    $collections_table = $wpdb->prefix . "collections";
    $products_table = $wpdb->prefix . "products";
    $messages_table = $wpdb->prefix . "messages";
    $status_table = $wpdb->prefix . "status";
    $order_status_table = $wpdb->prefix . "order_status";
    $reviews_table = $wpdb->prefix . "reviews";

    // orders table
    $sql = "CREATE TABLE $orders_table (
        order_id INT unsigned NOT NULL AUTO_INCREMENT,
        name varchar(60) DEFAULT NULL,
        phone varchar(20) DEFAULT NULL,
        city varchar(30) DEFAULT NULL,
        address varchar(100) DEFAULT NULL,
        country varchar(30) DEFAULT NULL,
        total DECIMAL DEFAULT NULL,
        product_id INT unsigned DEFAULT NULL,
        variant varchar(100) DEFAULT NULL,
        qte INT unsigned DEFAULT NULL,
        unit_price DECIMAL DEFAULT NULL,
        date DATE NOT NULL,
        PRIMARY KEY  (order_id),
        KEY Index_2 (name),
        KEY Index_3 (phone)
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";

    // collection table
    $sql .= "CREATE TABLE $collections_table (
        id int unsigned NOT NULL AUTO_INCREMENT,
        title varchar(100) NOT NULL,
        sub_title varchar(255) NOT NULL,
        image int unsigned NOT NULL,
        PRIMARY KEY  (id),
        KEY Index_2 (title),
        KEY Index_3 (sub_title)
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";

    // product table
    $sql .= "CREATE TABLE $products_table (
        id int unsigned NOT NULL AUTO_INCREMENT,
        name varchar(255) NOT NULL,
        price DECIMAL NOT NULL,
        description TEXT NOT NULL,
        collection_id INT unsigned NOT NULL,
        promo DECIMAL DEFAULT NULL,
        images varchar(255) NOT NULL,
        date DATE NOT NULL,
        PRIMARY KEY  (id),
        KEY Index_2 (name)
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";

    // messages table
    $sql .= "CREATE TABLE $messages_table (
        id int unsigned NOT NULL AUTO_INCREMENT,
        email varchar(100) NOT NULL,
        subject varchar(255) NOT NULL,
        message TEXT NOT NULL,
        PRIMARY KEY  (id),
        KEY Index_2 (email)
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
    $sql .= "CREATE TABLE $status_table (
        id int unsigned NOT NULL AUTO_INCREMENT,
        title varchar(100) NOT NULL,
        color varchar(100) NOT NULL,
        PRIMARY KEY  (id),
        KEY Index_2 (title)
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
    $sql .= "CREATE TABLE $order_status_table (
        id int unsigned NOT NULL AUTO_INCREMENT,
        order_id int unsigned NOT NULL,
        status_id int unsigned NOT NULL,
        date DATE NOT NULL,
        PRIMARY KEY  (id)
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
    $sql .= "CREATE TABLE $reviews_table (
        id int unsigned NOT NULL AUTO_INCREMENT,
        fullname varchar(100) NOT NULL,
        email varchar(100) NOT NULL,
        rating float NOT NULL,
        product_id int unsigned NOT NULL,
        description text NOT NULL,
        status TINYINT NOT NULL,
        date DATE NOT NULL,
        PRIMARY KEY  (id)
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";

    dbDelta($sql);
}

/**
 * create theme pages and menus
 * header menu and pages
 * fotter menu and pages
 */

function create_theme_pages()
{
    $indexPage = create_page("Home", "", null, "views/template-home.blade.php");
    $collectionsPage = create_page("Collections", "", null, "views/template-collections.blade.php");
    $contactPage =  create_page("ContactUs", "", null, "views/template-contact.blade.php");
    $productPage =  create_page("Product", "", null, "views/template-products.blade.php");

    // footer pages
    // terms and polices
    $termsOfusePage =  create_page("Terms Of Use", "", null, "views/template-contact.blade.php");
    $ExchangeandReturnPolicyPage =  create_page("Exchange and Return Policy", "", null, "views/template-contact.blade.php");
    $ExchangeandReturnPolicyPage =  create_page("Exchange and Return Policy", "", null, "views/template-contact.blade.php");
    $PrivacyPolicyPage =  create_page("Privacy policy", "", null, "views/template-contact.blade.php");
    // about store
    $AbouttheStorePage =  create_page("About the Store", "", null, "views/template-contact.blade.php");
    $PaymentMethodsPage =  create_page("Payment Methods", "", null, "views/template-contact.blade.php");
    $ShippingPage =  create_page("Shipping", "", null, "views/template-contact.blade.php");
    // contact us
    $FrequentlyAskedQuestionsPage =  create_page("Frequently Asked Questions", "", null, "views/template-contact.blade.php");

    /**
     * make created index page as theme front page
     */
    if ($indexPage) {
        update_option('page_on_front', $indexPage);
        update_option('show_on_front', 'page');
    }
    /**
     * create the header menu
     * and it's items
     */
    $menuName = wp_get_theme()->name . ' Header Menu';
    $footerMenu = wp_get_theme()->name . ' Footer Menu';

    // footer menu
    if (!wp_get_nav_menu_object($footerMenu)) {
        $menu_id = wp_create_nav_menu($footerMenu);
        $termsParent = wp_update_nav_menu_item($menu_id, 0, array(
            'menu-item-title' => __('Terms and Policies'),
            'menu-item-object-id' => $termsOfusePage,
            'menu-item-object' => 'page',
            'menu-item-status' => 'publish',
            'menu-item-type' => 'post_type',
        ));
        // echo '<pre>';
        // echo var_dump($termsParent);
        // echo '</pre>';
        // die();
        wp_update_nav_menu_item($menu_id, 0, array(
            'menu-item-title' => __('Terms Of Use'),
            'menu-item-object-id' => $termsOfusePage,
            'menu-item-object' => 'page',
            'menu-item-status' => 'publish',
            'menu-item-type' => 'post_type',
            'menu-item-parent-id' => $termsParent,
        ));
        wp_update_nav_menu_item($menu_id, 0, array(
            'menu-item-title' => __('Exchangeand Return Policy'),
            'menu-item-object-id' => $ExchangeandReturnPolicyPage,
            'menu-item-object' => 'page',
            'menu-item-status' => 'publish',
            'menu-item-type' => 'post_type',
            'menu-item-parent-id' => $termsParent,
        ));
        wp_update_nav_menu_item($menu_id, 0, array(
            'menu-item-title' => __('Privacy Policy'),
            'menu-item-object-id' => $PrivacyPolicyPage,
            'menu-item-object' => 'page',
            'menu-item-status' => 'publish',
            'menu-item-type' => 'post_type',
            'menu-item-parent-id' => $termsParent,
        ));
        $aboutParent = wp_update_nav_menu_item($menu_id, 0, array(
            'menu-item-title' => __('About'),
            'menu-item-object-id' => $AbouttheStorePage,
            'menu-item-object' => 'page',
            'menu-item-status' => 'publish',
            'menu-item-type' => 'post_type',
        ));
        wp_update_nav_menu_item($menu_id, 0, array(
            'menu-item-title' => __('About the Store'),
            'menu-item-object-id' => $AbouttheStorePage,
            'menu-item-object' => 'page',
            'menu-item-status' => 'publish',
            'menu-item-type' => 'post_type',
            'menu-item-parent-id' => $aboutParent,
        ));
        wp_update_nav_menu_item($menu_id, 0, array(
            'menu-item-title' => __('Payment Methods'),
            'menu-item-object-id' => $PaymentMethodsPage,
            'menu-item-object' => 'page',
            'menu-item-status' => 'publish',
            'menu-item-type' => 'post_type',
            'menu-item-parent-id' => $aboutParent,
        ));
        wp_update_nav_menu_item($menu_id, 0, array(
            'menu-item-title' => __('Shipping'),
            'menu-item-object-id' => $ShippingPage,
            'menu-item-object' => 'page',
            'menu-item-status' => 'publish',
            'menu-item-type' => 'post_type',
            'menu-item-parent-id' => $aboutParent,
        ));
        $contactParent = wp_update_nav_menu_item($menu_id, 0, array(
            'menu-item-title' => __('Contact Us'),
            'menu-item-object-id' => $contactPage,
            'menu-item-object' => 'page',
            'menu-item-status' => 'publish',
            'menu-item-type' => 'post_type',
        ));
        wp_update_nav_menu_item($menu_id, 0, array(
            'menu-item-title' => __('Contact Us'),
            'menu-item-object-id' => $contactPage,
            'menu-item-object' => 'page',
            'menu-item-status' => 'publish',
            'menu-item-type' => 'post_type',
            'menu-item-parent-id' => $contactParent,
        ));
        wp_update_nav_menu_item($menu_id, 0, array(
            'menu-item-title' => __('Frequently Asked Questions'),
            'menu-item-object-id' => $FrequentlyAskedQuestionsPage,
            'menu-item-object' => 'page',
            'menu-item-status' => 'publish',
            'menu-item-type' => 'post_type',
            'menu-item-parent-id' => $contactParent,
        ));
    }

    //header menu
    if (!wp_get_nav_menu_object($menuName)) {
        $menu_id = wp_create_nav_menu($menuName);
        wp_update_nav_menu_item($menu_id, 0, array(
            'menu-item-title' => __('Home'),
            'menu-item-object-id' => $indexPage,
            'menu-item-object' => 'page',
            'menu-item-status' => 'publish',
            'menu-item-type' => 'post_type',
            'menu-item-position' => 2,
        ));
        wp_update_nav_menu_item($menu_id, 0, array(
            'menu-item-title' => __('Collections'),
            'menu-item-object-id' => $collectionsPage,
            'menu-item-object' => 'page',
            'menu-item-status' => 'publish',
            'menu-item-type' => 'post_type',
            'menu-item-position' => 2,
        ));
        wp_update_nav_menu_item($menu_id, 0, array(
            'menu-item-title' => __('ContactUs'),
            'menu-item-object-id' => $contactPage,
            'menu-item-object' => 'page',
            'menu-item-status' => 'publish',
            'menu-item-type' => 'post_type',
            'menu-item-position' => 3,
        ));
    }
}

// $locations = get_nav_menu_locations();

//     if (isset($locations['primary_navigation'])) {
//         $menu_id = $locations['primary_navigation'];

//         $new_menu_obj = array();

//         $nav_items_to_add = array(
//                 'shop' => array(
//                     'title' => 'Shop',
//                     'path' => 'shop',
//                     ),
//                 'shop_l2' => array(
//                     'title' => 'Shop',
//                     'path' => 'shop',
//                     'parent' => 'shop',
//                     ),
//                 'cart' => array(
//                     'title' => 'Cart',
//                     'path' => 'shop/cart',
//                     'parent' => 'shop',
//                     ),
//                 'checkout' => array(
//                     'title' => 'Checkout',
//                     'path' => 'shop/checkout',
//                     'parent' => 'shop',
//                     ),
//                 'my-account' => array(
//                     'title' => 'My Account',
//                     'path' => 'shop/my-account',
//                     'parent' => 'shop',
//                     ),
//                 'lost-password' => array(
//                     'title' => 'Lost Password',
//                     'path' => 'shop/my-account/lost-password',
//                     'parent' => 'my-account',
//                     ),
//                 'edit-address' => array(
//                     'title' => 'Edit My Address',
//                     'path' => 'shop/my-account/edit-address',
//                     'parent' => 'my-account',
//                     ),
//             );

//     foreach ( $nav_items_to_add as $slug => $nav_item ) {
//         $new_menu_obj[$slug] = array();
//         if ( array_key_exists( 'parent', $nav_item ) )
//             $new_menu_obj[$slug]['parent'] = $nav_item['parent'];
//         $new_menu_obj[$slug]['id'] = wp_update_nav_menu_item($menu_id, 0,  array(
//                 'menu-item-title' => $nav_item['title'],
//                 'menu-item-object' => 'page',
//                 'menu_item_parent' => $new_menu_obj[ $nav_item['parent'] ]['id'],
//                 'menu-item-object-id' => get_page_by_path( $nav_item['path'] )->ID,
//                 'menu-item-type' => 'post_type',
//                 'menu-item-status' => 'publish')
//         );
//     }

//     }
