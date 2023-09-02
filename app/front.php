<?php

namespace App;

function front_styles()
{
    // wp_enqueue_style('bootstrap-rtl', asset_path('styles/bootstrap.rtl.min'));
    wp_enqueue_style('bootstrap', asset_path('styles/bootstrap.css'));
    // wp_enqueue_style('google-api', 'https://fonts.googleapis.com');
    // wp_enqueue_style('gstatic', 'https://fonts.gstatic.com', array('google-api'));
    wp_enqueue_style('fonts', 'https://fonts.googleapis.com/css2?family=Cairo:wght@200;300&family=El+Messiri&display=swap');
    wp_enqueue_style('custom-style', asset_path('styles/fronstyle.css'), array('bootstrap'));
    wp_enqueue_style('swiffy-slider-css', 'https://cdn.jsdelivr.net/npm/swiffy-slider@1.2.0/dist/css/swiffy-slider.min.css');
    wp_enqueue_style('lightslider-css', asset_path('styles/lightslider.min.css'));
    wp_enqueue_style('fontawsome-css', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css');
    // wp_enqueue_style('bootstrap-rating', 'https://cdn.jsdelivr.net/gh/kartik-v/bootstrap-star-rating@4.0.7/css/star-rating.css', array('bootstrap', 'fontawsome-css'));
    wp_enqueue_script('swiffy-slider-js', 'https://cdn.jsdelivr.net/npm/swiffy-slider@1.2.0/dist/js/swiffy-slider.min.js');
    wp_enqueue_script('laryLoader', asset_path('scripts/lazysizes.min.js'));
    wp_enqueue_script('lightslider.min.js', asset_path('scripts/lightslider.min.js'), array('jquery'));
    wp_enqueue_script('flipdown.min.js', asset_path('scripts/flipdown.min.js'));
    wp_enqueue_style('flipdown.min.css', asset_path('styles/flipdown.min.css'));
    wp_enqueue_script('productpage.js', asset_path('scripts/productpage.js'), array('jquery'), false, true);
    wp_enqueue_style('sweetalert2.min.css', asset_path('styles/sweetalert2.min.css'));
    wp_enqueue_script('sweetalert2.all.js', asset_path('scripts/sweetalert2.min.js'), array('jquery'), false, false);
    wp_enqueue_script('front-js', asset_path('scripts/front.js'), array('jquery'), false, true);
    // wp_enqueue_script('bootstrap-js', asset_path('scripts/bootstrap.js'), null, '5', false);
    // wp_enqueue_script('bootstrap-js', 'https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js', null, '5', false);
    wp_enqueue_script('bootstrap-js', 'https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js', null, '5', false);
    // wp_enqueue_script('bootstrap-rating-js', 'https://cdn.jsdelivr.net/gh/kartik-v/bootstrap-star-rating@4.0.7/js/star-rating.js', array('jquery'), false, false);
}

add_action('wp_enqueue_scripts', 'App\front_styles');

add_filter('style_loader_tag', 'App\front_style_loader_tag_filter', 10, 2);

function front_style_loader_tag_filter($html, $handle)
{
    if ($handle === 'gstatic') {
        return str_replace(
            "rel='stylesheet'",
            "rel='preconnect' crossorigin='anonymous'",
            $html
        );
    }
    if ($handle === 'swiffy-slider-css') {
        return str_replace(
            "rel='stylesheet'",
            "rel='stylesheet' crossorigin='anonymous'",
            $html
        );
    }
    return $html;
}

/**
 * process contact us form post
 */
function save_contact_us_data()
{
    global $wpdb;
    $messages_table = $wpdb->prefix . "messages";
    if (isset($_POST["btn_save_contact_info"]) && $_POST["btn_save_contact_info"] == "save_contact_info") {
        $email = sanitize_text_field($_POST['email']);
        $subject = sanitize_text_field($_POST['subject']);
        $message = sanitize_text_field($_POST['message']);
        $wpdb->insert($messages_table, array(
            "email" => $email,
            "subject" => $subject,
            "message" => $message,
        ));

        wp_redirect(home_url('/'));
        exit;
    }
}

add_action("init", 'App\save_contact_us_data');

/***
 * save order to database
 */

function save_order_details()
{
    if (isset($_POST['btn_add_new_order']) && $_POST['btn_add_new_order'] == 'add_new_order') {
        // echo '<pre>';
        // echo print_r($_POST);
        // echo '</pre>';
        // die();
        global $wpdb;
        $orders_table = $wpdb->prefix . "orders";

        $name = sanitize_text_field($_POST['fullName']);
        $phone = sanitize_text_field($_POST['phone']);
        $city = sanitize_text_field($_POST['city']);
        $address = sanitize_text_field($_POST['address']);
        $country = sanitize_text_field($_POST['country']);
        $variant = sanitize_text_field($_POST['variant']);
        // $total = intval($_POST['product_collection']);
        $product_id = intval($_POST['product_id']);
        $qte = intval($_POST['order_qte']);
        $unit_price = floatval($_POST['unit_price']);
        $date = wp_date('Y-m-d h:i:s');



        $wpdb->insert($orders_table, array(
            "name" => $name,
            "phone" => $phone,
            "city" => $city,
            "address" => $address,
            "country" => $country,
            "product_id" => $product_id,
            "qte" => $qte,
            "unit_price" => $unit_price,
            "variant" => $variant,
            "date" => $date
        ));
        // set success message cookie and update the shoping card cookie
        // if (!isset($_COOKIE['orderPlacedMessage'])) {
        // }
        setcookie('orderPlacedMessage', 'Your Orders Is Added Successfully', strtotime('+1 day'), '/', $_SERVER['SERVER_NAME']);

        // $product_link = esc_url(add_query_arg('pid', $product_id, get_permalink(get_page_by_path('Product'))));
        wp_redirect(home_url("/"));
        exit;
    }
}

function save_client_review()
{
    if (isset($_POST['save_client_review']) && $_POST['save_client_review'] == 'save_client_review') {
        global $wpdb;
        $reviews_table = $wpdb->prefix . "reviews";
        // echo '<pre>';
        // echo print_r($_SERVER['HTTP_REFERER']);
        // echo '</pre>';
        // die();

        $name = sanitize_text_field($_POST['name']);
        $email = sanitize_text_field($_POST['email']);
        $desc = sanitize_text_field($_POST['desc']);
        $rating = floatval($_POST['rating']);
        $date = wp_date('Y-m-d h:i:s');
        $product = intval($_POST['product']);
        if ($product == 0) {
            wp_redirect(home_url("/"));
            exit;
        }

        $wpdb->insert($reviews_table, array(
            "fullname" => $name,
            "email" => $email,
            "rating" => $rating,
            "description" => $desc,
            "date" => $date,
            "product_id" => $product
        ));
        setcookie('ReviewPlacedMessage', 'Your Review Is Added Successfully And Waiting To Be Approved', strtotime('+1 day'), '/', $_SERVER['SERVER_NAME']);
        setcookie('RemoveReviewBtn', $product, strtotime('+4 year'), '/', $_SERVER['SERVER_NAME']);

        if ($_SERVER['HTTP_REFERER'] != "") {
            wp_redirect($_SERVER['HTTP_REFERER']);
        } else {
            wp_redirect(home_url("/"));
        }
        exit;
    }
}

add_action("init", 'App\save_order_details');
add_action("init", 'App\save_client_review');

/**
 * show sweetalert if the order is placed successfully
 */
add_action('wp_footer', 'App\show_order_placed_alert');
function show_order_placed_alert()
{
    if (isset($_COOKIE['orderPlacedMessage'])) {
        $value = $_COOKIE['orderPlacedMessage'];
        echo "
                <script>
                    Swal.fire(
                        'new order',
                        '$value',
                        'success'
                    );
                </script>
            ";

        setcookie('orderPlacedMessage', '', time() - 3600, '/', $_SERVER['SERVER_NAME']);
        unset($_COOKIE['orderPlacedMessage']);
    }
}

add_action('wp_footer', 'App\show_review_placed_alert');
function show_review_placed_alert()
{
    if (isset($_COOKIE['ReviewPlacedMessage'])) {
        $value = $_COOKIE['ReviewPlacedMessage'];
        echo "
                <script>
                    var myModal = new bootstrap.Modal(document.getElementById('addreviewmodal'), {
                        keyboard: false
                    });
                    myModal.hide();
                    Swal.fire(
                        'Review Added',
                        '$value',
                        'success'
                    );
                </script>
            ";

        setcookie('ReviewPlacedMessage', '', time() - 3600, '/', $_SERVER['SERVER_NAME']);
        unset($_COOKIE['ReviewPlacedMessage']);
    }
}
