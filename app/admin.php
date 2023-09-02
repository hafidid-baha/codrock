<?php

namespace App;

use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\PHPMailer;

/**
 * Theme customizer
 */
add_action('customize_register', function (\WP_Customize_Manager $wp_customize) {
    // Add postMessage support
    $wp_customize->get_setting('blogname')->transport = 'postMessage';
    $wp_customize->selective_refresh->add_partial('blogname', [
        'selector' => '.brand',
        'render_callback' => function () {
            bloginfo('name');
        }
    ]);
});

/**
 * Customizer JS
 */
add_action('customize_preview_init', function () {
    wp_enqueue_script('sage/customizer.js', asset_path('scripts/customizer.js'), ['customize-preview'], null, true);
});


/** ======== create admin menu for cod rock theme */
/**
 * add main admin menu for our theme
 */

/** create the page of our menu items */
function codrock_admin_main_menu()
{
    $template = require_once('Pages/dashboard.php');
    echo $template;
}

/** orders page builder function */
function codRock_admin_orders()
{
    $template = require_once('Pages/orders.php');
    echo $template;
}
/** product collections page builder function */
function codRock_admin_product_collections()
{
    $template = require_once('Pages/collections.php');
    echo $template;
}
/** product page builder function */
function codRock_admin_products()
{
    $template = require_once('Pages/products.php');
    echo $template;
}
/** product review page builder function */
function codRock_admin_product_reviews()
{
    $template = require_once('Pages/reviews.php');
    echo $template;
}
/** product review page builder function */
function codRock_admin_product_messages()
{
    $template = require_once('Pages/messages.php');
    echo $template;
}
/** product page settings page builder function */
function codRock_admin_store_page()
{
    echo "this is the main page to controll your store pages";
}
/** upsell & cross sell page builder function */
function codRock_admin_sells_page()
{
    echo "here you can find the upsell and cross sell options";
}
/** google sheet linker page builder function */
function codRock_admin_sheets()
{
    echo "here you can link your online goole sheets with your store";
}
/** payment methods page builder function */
function codRock_admin_payment_methods()
{
    echo "this the page where you can set up your payment methods";
}
/** themes page builder function */
function codRock_admin_themes()
{
    echo "chose your themes";
}
/** fb pixel page builder function */
function codRock_admin_fb_pixel()
{
    echo "this is where you can add your fb pixels and controll theme";
}
/** google tage manager page builder function */
function codRock_admin_google_tracking()
{
    echo "add your goole tracker to the store";
}

/**
 * inject bootstrap to custom admin pages
 */
function admin_styles()
{
    wp_enqueue_style('sage/bootstrap.css', asset_path('styles/bootstrap'), false, null);
    // wp_enqueue_style('sage/custom', asset_path('styles/custom'), false, null);
    wp_enqueue_style('datatables-styles', asset_path('styles/datatables.css'), array('sage/bootstrap.css'), '1.11.3');
    wp_enqueue_style('toastr-styles', asset_path('styles/toastr'), null);
    wp_enqueue_style('fontawsome', asset_path('styles/fontawsome'), null);
    wp_enqueue_script('fontawsome', asset_path('scripts/fontawsome'));
    wp_enqueue_script('datatable', asset_path('scripts/dataTables'), array('jquery'), '1.11.3', false);
    wp_enqueue_script('bootstrap-datatable', asset_path('scripts/bootstrap-datatables'), array('jquery'), '1.11.3', false);
    wp_enqueue_script('popper-js', 'https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js',);
    wp_enqueue_script('bootstrap-js', asset_path('scripts/bootstrap'), array('popper-js'), '5', false);
    wp_enqueue_script('toastr-js', asset_path('scripts/toastr.min'), array(), false, false);
    wp_enqueue_script('jquery-form');
    wp_enqueue_script('dt_fixedheader', asset_path('scripts/dt_fixedheader.min'), array('bootstrap-datatable', 'jquery', 'datatable'), false, false);
    wp_enqueue_script('dt_responsive', asset_path('scripts/dt_responsive.min.js'), array('bootstrap-datatable', 'jquery', 'datatable', 'dt_fixedheader'), false, false);
    wp_enqueue_script('responsive_bootstrap', asset_path('scripts/responsive_bootstrp.min.js'), array('bootstrap-datatable', 'jquery', 'datatable', 'dt_fixedheader', 'dt_responsive'), false, false);


    wp_enqueue_style('dt_fixedheader', asset_path('styles/dt_fixedheader.min.css'), array('sage/bootstrap.css', 'datatables-styles'));
    wp_enqueue_style('dt_responsive', asset_path('styles/dt_responsive.min.css'), array('sage/bootstrap.css', 'datatables-styles', 'dt_fixedheader'));

    if (!did_action('wp_enqueue_media')) {
        wp_enqueue_media();
    }
    // wp_enqueue_script('sage/main.js', asset_path('scripts/main'), null, false, true);
}

/**
 * convert text area into a wp editor
 */
add_action('admin_init', 'App\convert_textarea_to_wysiwyg');

function convert_textarea_to_wysiwyg()
{
    wp_enqueue_editor();
    add_action('admin_print_footer_scripts', function () {
?>
        <script>
            jQuery(function() {
                wp.editor.initialize('product_desc_textarea', {
                    textarea_name: 'product_desc',
                    tinymce: {
                        wpautop: true,
                        plugins: 'charmap colorpicker hr lists paste tabfocus textcolor fullscreen wordpress wpautoresize wpeditimage wpemoji wpgallery wplink wptextpattern',
                        toolbar1: 'formatselect,bold,italic,bullist,numlist,blockquote,alignleft,aligncenter,alignright,link,wp_more,spellchecker,fullscreen,wp_adv,listbuttons',
                        toolbar2: 'styleselect,strikethrough,hr,forecolor,pastetext,removeformat,charmap,outdent,indent,undo,redo,wp_help',
                        textarea_rows: 40
                    },
                    quicktags: {
                        buttons: 'strong,em,link,block,del,ins,img,ul,ol,li,code,more,close'
                    },
                    mediaButtons: true,
                });
            });
        </script>
<?php
    }, 10, 2);
}

/**
 * remove items from dashbord menu
 * posts item
 * comments
 */
add_action('admin_menu', 'App\remove_options');

function remove_options()
{
    remove_menu_page('edit.php');
    remove_menu_page('edit-comments.php');
}


/**
 * create theme admin menus
 */
function codrock_main_menu()
{
    /** create first admin menu
     * dashboard page
     */
    $dashboard = add_menu_page("Overview", "CodRcok", "manage_options", "codrock-dashboard", "App\codrock_admin_main_menu", "dashicons-store", 4);
    /** orders page */
    $orders = add_submenu_page("codrock-dashboard", "Orders", "Orders", "manage_options", "codrock-orders", "App\codRock_admin_orders");
    /** collection page */
    $collections = add_submenu_page("codrock-dashboard", "Collections", "Collections", "manage_options", "codrock-collections", "App\codRock_admin_product_collections");
    /** product page */
    $products = add_submenu_page("codrock-dashboard", "Products", "Products", "manage_options", "codrock-products", "App\codRock_admin_products");
    /** product page */
    $reviews = add_submenu_page("codrock-dashboard", "Reviews", "Reviews", "manage_options", "codrock-reviews", "App\codRock_admin_product_reviews");
    /** messages page */
    $messages = add_submenu_page("codrock-dashboard", "Messages", "Messages", "manage_options", "codrock-messages", "App\codRock_admin_product_messages");
    /** product page settings */
    $product_page = add_submenu_page("codrock-dashboard", "Store Pages Settings", "Store Pages", "manage_options", "codrock-product-page", "App\codRock_admin_store_page");
    /** upsell & crossell page  */
    $sells = add_submenu_page("codrock-dashboard", "Upsell And Cross Sell", "Upsell & Cross Sell", "manage_options", "codrock-upsell-crosssell", "App\codRock_admin_sells_page");
    /** googlr sheet likner page */
    $sheets = add_submenu_page("codrock-dashboard", "Google Sheets", "Google Sheets", "manage_options", "codrock-sheets", "App\codRock_admin_sheets");
    /** payments methods page */
    $payments = add_submenu_page("codrock-dashboard", "Payment Methods", "Payment Methods", "manage_options", "codrock-payment_methods", "App\codRock_admin_payment_methods");
    /** themes methods page */
    $themes = add_submenu_page("codrock-dashboard", "Themes", "Themes", "manage_options", "codrock-themes", "App\codRock_admin_themes");
    /** facebook pexel  page */
    $facebook = add_submenu_page("codrock-dashboard", "Facebook Pixel", "Facebook Pixel", "manage_options", "codrock-facebook", "App\codRock_admin_fb_pixel");
    /** google tag manager page */
    $google = add_submenu_page("codrock-dashboard", "Google Tracking", "Google Tracking", "manage_options", "codrock-google", "App\codRock_admin_google_tracking");

    add_action('admin_print_styles-' . $dashboard, 'App\admin_styles');
    add_action('admin_print_styles-' . $orders, 'App\admin_styles');
    add_action('admin_print_styles-' . $collections, 'App\admin_styles');
    add_action('admin_print_styles-' . $products, 'App\admin_styles');
    add_action('admin_print_styles-' . $reviews, 'App\admin_styles');
    add_action('admin_print_styles-' . $messages, 'App\admin_styles');
    add_action('admin_print_styles-' . $product_page, 'App\admin_styles');
    add_action('admin_print_styles-' . $sells, 'App\admin_styles');
    add_action('admin_print_styles-' . $sheets, 'App\admin_styles');
    add_action('admin_print_styles-' . $payments, 'App\admin_styles');
    add_action('admin_print_styles-' . $themes, 'App\admin_styles');
    add_action('admin_print_styles-' . $facebook, 'App\admin_styles');
    add_action('admin_print_styles-' . $google, 'App\admin_styles');
}

add_action('admin_menu', 'App\codrock_main_menu');

/**
 * product page operations
 * add new product
 * remove existing product
 * update product
 * using ajax
 */
//save this in the database
// $content=sanitize_text_field( htmlentities($_POST['content']) );

// //to display, use
// html_entity_decode($content);

require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
function add_new_product_action()
{
    global $wpdb;
    $products_table = $wpdb->prefix . "products";
    if (isset($_POST['codrock_add_new_product']) && $_POST['codrock_add_new_product'] == 'create_new_product') {
        $name = sanitize_text_field($_POST['product_name']);
        $price = floatval($_POST['product_price']);
        $promo = floatval($_POST['product_promo']);
        $images = $_POST['product_images'];
        $desc = sanitize_text_field(htmlentities($_POST['product_desc']));
        $collection = intval($_POST['product_collection']);
        $date = wp_date('Y-m-d h:i:s');



        $wpdb->insert($products_table, array(
            "name" => $name,
            "price" => $price,
            "description" => $desc,
            "collection_id" => $collection,
            "promo" => $promo,
            "images" => $images,
            "date" => $date
        ));

        wp_redirect(admin_url('/admin.php?page=codrock-products&success=product_created'));
        exit;
    }
}

function remove_product_action()
{
    global $wpdb;
    $products_table = $wpdb->prefix . "products";
    $status = $wpdb->delete($products_table, array('id' => intval($_POST['product'])));
    $status = 200;
    if ($status === false) {
        $status = 500;
        wp_send_json_success('Failed To Delete Product ', $status);
    } else {
        wp_send_json_success('Product Removed Successfully', $status);
    }
}

function update_product_action()
{
    global $wpdb;
    $products_table = $wpdb->prefix . "products";
    if (isset($_POST['codrock_add_new_product']) && $_POST['codrock_add_new_product'] == 'update_product') {
        $data = [
            "name" => sanitize_text_field($_POST['product_name']),
            'price' => floatval($_POST['product_price']),
            'promo' => floatval($_POST['product_promo']),
            'images' => $_POST['product_images'],
            'description' => sanitize_text_field($_POST['product_desc']),
            'collection_id' => intval($_POST['product_collection']),
            'date' => wp_date('Y-m-d h:i:s')
        ];
        $where = ['id' => intval($_POST['product'])]; // NULL value in WHERE clause.
        $updated = $wpdb->update($products_table, $data, $where);
        // exit(var_dump($wpdb->last_query));
        if ($updated) {
            wp_redirect(admin_url('/admin.php?page=codrock-products&success=product_updated'));
            exit;
        } else {
            wp_redirect(admin_url('/admin.php?page=codrock-products&error=product_updated_failed'));
            exit;
        }
    }
}

add_action("admin_init", 'App\add_new_product_action');
add_action("admin_init", 'App\update_product_action');
add_action("wp_ajax_remove_product", 'App\remove_product_action');

/**
 * collections operation
 * add new collection
 * remove existing collection
 * update collection
 * using ajax requests
 */

function add_new_collection_action()
{
    global $wpdb;
    $collection_table = $wpdb->prefix . "collections";
    if (isset($_POST['codrock_add_new_collection_btn']) && $_POST['action'] == "create_new_collection") {
        $title = sanitize_text_field($_POST['collection_title']);
        $sub_title = sanitize_text_field($_POST['collection_sub_title']);
        $image = sanitize_text_field($_POST['collection_image']);

        $wpdb->insert($collection_table, array(
            "title" => $title,
            "sub_title" => $sub_title,
            "image" => $image,
        ));

        wp_redirect(admin_url('/admin.php?page=codrock-collections&success=collection_created'));
        exit;
    }
}

function remove_collection_action()
{
    global $wpdb;
    $collection_table = $wpdb->prefix . "collections";
    $status = $wpdb->delete($collection_table, array('id' => intval($_POST['collection'])));
    if ($status === false) {
        wp_send_json_success('Failed To Delete Collection ', 500);
    } else {
        wp_send_json_success('Collection Removed Successfully ', 200);
    }
}

function update_collection_action()
{
    global $wpdb;
    $collections_table = $wpdb->prefix . "collections";
    if (isset($_POST['codrock_add_new_collection_btn']) && $_POST['action'] == "update_collection") {
        $data = [
            "title" => sanitize_text_field($_POST['collection_title']),
            "sub_title" => sanitize_text_field($_POST['collection_sub_title']),
            "image" => sanitize_text_field($_POST['collection_image']),
        ];
        $where = ['id' => intval($_POST['collection'])]; // NULL value in WHERE clause.
        $updated = $wpdb->update($collections_table, $data, $where);
        // exit(var_dump($wpdb->last_query));
        if ($updated) {
            wp_redirect(admin_url('/admin.php?page=codrock-collections&success=collection_Updated'));
        } else {
            wp_redirect(admin_url('/admin.php?page=codrock-collections&error=collection_Updated'));
        }
    }
}

add_action("admin_init", 'App\add_new_collection_action');
add_action("wp_ajax_remove_collection", 'App\remove_collection_action');
add_action("admin_init", 'App\update_collection_action');

/**
 * order operations
 * remove order
 * add new status
 */

function remove_order_action()
{
    global $wpdb;
    $orders_table = $wpdb->prefix . "orders";
    $order_status_table = $wpdb->prefix . "order_status";
    $order = intval($_POST['order']);
    $status = $wpdb->delete($orders_table, array('order_id' => $order));
    if ($status === false) {
        wp_send_json_success('Failed To Delete Order ', 500);
    } else {
        $wpdb->delete($order_status_table, array('order_id' => $order));
        wp_send_json_success('Order Removed Successfully ', 200);
    }
}

function remove_order_status_action()
{
    global $wpdb;
    $status_table = $wpdb->prefix . "status";
    $status = $wpdb->delete($status_table, array('id' => intval($_POST['status'])));
    if ($status === false) {
        wp_send_json_success('Failed To Delete Status ', 500);
    } else {
        wp_send_json_success('Status Removed Successfully ', 200);
    }
}

function detail_remove_order_status_action()
{
    global $wpdb;
    $order_status_table = $wpdb->prefix . "order_status";
    $status = $wpdb->delete($order_status_table, array('status_id' => intval($_POST['order_status']), 'order_id' => intval($_POST['order'])));
    if ($status === false) {
        wp_send_json_success('Failed To Delete Order Status ', 500);
    } else {
        wp_send_json_success('Order Status Removed Successfully ', 200);
    }
}

function update_order()
{
    if (isset($_POST['codrock_update_order']) && $_POST['codrock_update_order'] == 'codrock_update_order') {
        global $wpdb;
        $orders_table = $wpdb->prefix . "orders";

        $name = sanitize_text_field($_POST['order_name']);
        $phone = sanitize_text_field($_POST['order_phone']);
        $city = sanitize_text_field($_POST['order_city']);
        $address = sanitize_text_field($_POST['order_address']);
        $country = sanitize_text_field($_POST['order_country']);
        $qte = intval($_POST['order_qte']);
        $status = intval($_POST['order_status']);
        $order = intval(trim($_POST['order']));
        // $total = intval($_POST['product_collection']);
        // $product_id = intval($_POST['product_id']);
        // $qte = intval($_POST['order_qte']);
        // $unit_price = floatval($_POST['unit_price']);
        // $date = wp_date('Y-m-d h:i:s');
        $inserted_status = false;
        if ($status > 0) {
            $order_status_table = $wpdb->prefix . "order_status";
            $inserted_status = $wpdb->insert($order_status_table, array(
                "order_id" => $order,
                "status_id" => $status,
                "date" => wp_date('Y-m-d h:i:s'),
            ));
        }
        $where = ['order_id' => $order];
        $updated = $wpdb->update($orders_table, array(
            "name" => $name,
            "phone" => $phone,
            "city" => $city,
            "address" => $address,
            "country" => $country,
            "qte" => $qte
        ), $where);
        if ($updated || $inserted_status) {
            setcookie('OrderUpdatedMessage', 'Your Order Is Updated Successfully', strtotime('+1 day'), '/wp-admin', $_SERVER['SERVER_NAME']);
            wp_redirect(admin_url('/admin.php?page=codrock-orders'));
            exit;
        } else {
            setcookie('OrderFailedUpdatedMessage', 'Order Failed To Be Updated', strtotime('+1 day'), '/wp-admin', $_SERVER['SERVER_NAME']);
            wp_redirect(admin_url('/admin.php?page=codrock-orders'));
            exit;
        }
    }
}
/**
 * add new order status
 * update existng status
 */
function add_new_order_status()
{
    global $wpdb;
    $status_table = $wpdb->prefix . "status";
    if (isset($_POST['codrock_add_new_order_status']) && $_POST['codrock_add_new_order_status'] ==  "add_new_order_status") {
        $title = sanitize_text_field($_POST['status_title']);
        $color = sanitize_text_field($_POST['status_color']);
        $wpdb->insert($status_table, array(
            "title" => $title,
            "color" => $color
        ));
        // add cookie to show success toast
        setcookie('newStatusAddedMessage', 'New Status Added Successfully', strtotime('+1 day'), '/wp-admin', $_SERVER['SERVER_NAME']);
        wp_redirect(admin_url('/admin.php?page=codrock-orders'));
        exit;
    } elseif (isset($_POST['codrock_update_order_status']) && $_POST['codrock_update_order_status'] ==  "update_order_status") {
        $data = [
            "title" => sanitize_text_field($_POST['status_title']),
            'color' => sanitize_text_field($_POST['status_color']),
        ];
        $where = ['id' => intval($_POST['status_id'])]; // NULL value in WHERE clause.
        $updated = $wpdb->update($status_table, $data, $where);
        // exit(var_dump($wpdb->last_query));
        if ($updated) {
            setcookie('StatusUpdatedMessage', 'Status Updated Successfully', strtotime('+1 day'), '/wp-admin', $_SERVER['SERVER_NAME']);
            wp_redirect(admin_url('/admin.php?page=codrock-orders'));
            exit;
        } else {
            setcookie('StatusFailedUpdatedMessage', 'Status Failed To Be Updated', strtotime('+1 day'), '/wp-admin', $_SERVER['SERVER_NAME']);
            wp_redirect(admin_url('/admin.php?page=codrock-orders'));
            exit;
        }
    }
}

/**
 * check for status cookies (update,delete,creation cookies)
 * show toastr if the order is placed successfully
 */
add_action('admin_init', 'App\check_status_cookie_exists');

function check_status_cookie_exists()
{
    if (isset($_COOKIE['newStatusAddedMessage'])) {
        $value = $_COOKIE['newStatusAddedMessage'];
        add_action('admin_footer', function () use ($value) {
            echo "
                <script>
                    toastr.success('" . $value . "');
                </script>
            ";
        });
        setcookie('newStatusAddedMessage', '', strtotime('-1 day'), '/wp-admin', $_SERVER['SERVER_NAME']);
        unset($_COOKIE['newStatusAddedMessage']);
    }
    if (isset($_COOKIE['StatusUpdatedMessage'])) {
        $value = $_COOKIE['StatusUpdatedMessage'];
        add_action('admin_footer', function () use ($value) {
            echo "
                <script>
                    toastr.success('" . $value . "');
                </script>
            ";
        });
        setcookie('StatusUpdatedMessage', '', strtotime('-1 day'), '/wp-admin', $_SERVER['SERVER_NAME']);
        unset($_COOKIE['StatusUpdatedMessage']);
    }
    if (isset($_COOKIE['StatusFailedUpdatedMessage'])) {
        $value = $_COOKIE['StatusFailedUpdatedMessage'];
        add_action('admin_footer', function () use ($value) {
            echo "
                <script>
                    toastr.error('" . $value . "');
                </script>
            ";
        });
        setcookie('StatusFailedUpdatedMessage', '', strtotime('-1 day'), '/wp-admin', $_SERVER['SERVER_NAME']);
        unset($_COOKIE['StatusFailedUpdatedMessage']);
    }
}
/**
 * check for status cookies (update,delete,creation cookies)
 * show toastr if the order is placed successfully
 */
add_action('admin_init', 'App\check_order_cookie_exists');

function check_order_cookie_exists()
{
    if (isset($_COOKIE['OrderUpdatedMessage'])) {
        $value = $_COOKIE['OrderUpdatedMessage'];
        add_action('admin_footer', function () use ($value) {
            echo "
                <script>
                    toastr.success('" . $value . "');
                </script>
            ";
        });
        setcookie('OrderUpdatedMessage', '', strtotime('-1 day'), '/wp-admin', $_SERVER['SERVER_NAME']);
        unset($_COOKIE['OrderUpdatedMessage']);
    }
    if (isset($_COOKIE['OrderFailedUpdatedMessage'])) {
        $value = $_COOKIE['OrderFailedUpdatedMessage'];
        add_action('admin_footer', function () use ($value) {
            echo "
                <script>
                    toastr.error('" . $value . "');
                </script>
            ";
        });
        setcookie('OrderFailedUpdatedMessage', '', strtotime('-1 day'), '/wp-admin', $_SERVER['SERVER_NAME']);
        unset($_COOKIE['OrderFailedUpdatedMessage']);
    }
}
add_action("admin_init", 'App\add_new_order_status');
add_action("admin_init", 'App\update_order');
add_action("wp_ajax_remove_order", 'App\remove_order_action');
add_action("wp_ajax_remove_order_status", 'App\remove_order_status_action');
add_action("wp_ajax_detail_remove_order_status", 'App\detail_remove_order_status_action');

/**
 * review operations
 * update review status
 * remove review
 */
function activate_review()
{
    global $wpdb;
    $reviews_table = $wpdb->prefix . "reviews";
    $review = intval($_POST['review']);
    if ($review > 0) {
        $data = [
            'status' => 1
        ];
        $where = ['id' => $review]; // NULL value in WHERE clause.
        $updated = $wpdb->update($reviews_table, $data, $where);
        if ($updated) {
            wp_send_json_success('Review Status Has Been Changed Successfully ', 200);
            exit;
        } else {
            wp_send_json_success('Failed To Change Review Status ', 500);
            exit;
        }
    }
}
function disactivate_review()
{
    global $wpdb;
    $reviews_table = $wpdb->prefix . "reviews";
    $review = intval($_POST['review']);
    if ($review > 0) {
        $data = [
            'status' => 0
        ];
        $where = ['id' => $review]; // NULL value in WHERE clause.
        $updated = $wpdb->update($reviews_table, $data, $where);
        if ($updated) {
            wp_send_json_success('Review Status Has Been Changed Successfully ', 200);
            exit;
        } else {
            wp_send_json_success('Failed To Change Review Status ', 500);
            exit;
        }
    }
}
function remove_review()
{
    global $wpdb;
    $reviews_table = $wpdb->prefix . "reviews";
    $review = intval($_POST['review']);
    if ($review > 0) {
        $deleted = $wpdb->delete($reviews_table, array('id' => $review));
        if ($deleted) {
            wp_send_json_success('Review Has Been Deleted Successfully', 200);
            exit;
        } else {
            wp_send_json_success('Failed To Delete Review', 500);
            exit;
        }
    }
}
add_action("wp_ajax_activate_review", 'App\activate_review');
add_action("wp_ajax_disactivate_review", 'App\disactivate_review');
add_action("wp_ajax_remove_review", 'App\remove_review');

/**
 * message operations
 * remove messages
 * send emails via smtp
 */
function remove_message()
{
    global $wpdb;
    $messages_table = $wpdb->prefix . "messages";
    $message = intval($_POST['message']);
    if ($message > 0) {
        $deleted = $wpdb->delete($messages_table, array('id' => $message));
        if ($deleted) {
            wp_send_json_success('Message Has Been Deleted Successfully', 200);
            exit;
        } else {
            wp_send_json_success('Failed To Delete Message', 500);
            exit;
        }
    }
}

function send_email()
{
    // TODO: refactor the smtp params to settings page

    // echo '<pre>';
    // echo var_dump($_POST);
    // echo '</pre>';
    // return;
    $mail = new PHPMailer();
    $mail->IsSMTP();
    $mail->Mailer = "smtp";

    $mail->SMTPDebug  = 1;
    $mail->SMTPAuth   = TRUE;
    $mail->SMTPSecure = "tls";
    $mail->Port       = 587;
    $mail->Host       = "smtp.gmail.com";
    $mail->Username   = "codRock.team@gmail.com";
    $mail->Password   = "W3Rj__M[yENZ6V8;";

    $mail->IsHTML(true);
    $mail->AddAddress(sanitize_text_field($_POST['email']), sanitize_text_field($_POST['fullname']));
    $mail->SetFrom("codRock.team@gmail.com", "codRock Team");
    $mail->AddReplyTo("codRock.team@gmail.com", "codRock Team");
    // $mail->AddCC("cc-recipient-email@domain", "cc-recipient-name");
    $mail->Subject = sanitize_text_field($_POST['subject']);
    $content = "<b>" . sanitize_text_field($_POST['message']) . "</b>";

    $mail->MsgHTML($content);
    if (!$mail->Send()) {
        wp_send_json_success('Error while sending Email.', 500);
        // var_dump($mail);
        exit;
    } else {
        wp_send_json_success('Email sent successfully.', 200);
        exit;
    }
}
add_action("wp_ajax_send_email", 'App\send_email');
add_action("wp_ajax_remove_message", 'App\remove_message');
