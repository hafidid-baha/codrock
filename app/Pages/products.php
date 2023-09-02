<?php

use function App\asset_path;

// select all the prodiucts inside db
global $wpdb;

require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
$products_table = $wpdb->prefix . "products";
$collections_table = $wpdb->prefix . "collections";
$products = $wpdb->get_results("select * from $products_table");
$collections = $wpdb->get_results("select * from $collections_table");
$update_product = null;
if (isset($_GET['product']) && intval($_GET['product']) > 0) {
    $update_product = array_shift($wpdb->get_results("select * from $products_table where id = " . intval($_GET['product'])));
    echo "
    <script>
        var productFound = true;
    </script>
    ";
} else {
    echo "
    <script>
        var productFound = false;
    </script>
    ";
}

if (isset($_GET['success']) && $_GET['success'] == 'product_created') {
    echo "
    <script>
        var showProductCreateMessage = true;
    </script>
    ";
} else {
    echo "
    <script>
        var showProductCreateMessage = false;
    </script>
    ";
}
if (isset($_GET['success']) && $_GET['success'] == 'product_updated') {
    echo "
    <script>
        var showProductUpdatedMessage = true;
        var showProductUpdateErrorMessage = false;
    </script>
    ";
} elseif (isset($_GET['error']) && $_GET['error'] == 'product_updated_failed') {
    echo "
    <script>
        var showProductUpdatedMessage = false;
        var showProductUpdateErrorMessage = true;
    </script>
    ";
} else {
    echo "
    <script>
        var showProductUpdatedMessage = false;
        var showProductUpdateErrorMessage = false;
    </script>
    ";
}
$form_action_name = isset($_GET['product']) ?  'update_product' : 'create_new_product';
$update_product_price = isset($update_product) ? $update_product->price : "";
$update_product_promo = isset($update_product) ? $update_product->promo : 0;
$update_product_name = isset($update_product) ? $update_product->name : "";
$update_product_desc = isset($update_product) ? $update_product->description : "";
$update_product_ids = $update_product->images ?? '';
$update_product_cover = isset($update_product) && $update_product->images != "" ? wp_get_attachment_image_src(explode(',', $update_product->images)[0], array(354, 354))[0] : asset_path('images/product-image-placeholder.jpg');
$save_form_action = isset($update_product) ? "update_product" : "create_new_product";
// add editor
// $editor_settings = array("textarea_name" => "product_desc");
// wp_editor('put your desc', 'product_desc', $editor_settings);
// echo '<pre>';
// echo print_r();
// echo '</pre>';
$productsList = "";
foreach ($products as $product) {
    // echo '<pre>';
    // echo print_r($_GET);
    // echo '</pre>';
    $img_cover = array_filter(explode(',', trim($product->images)));
    $img_count = count($img_cover);
    if ($img_cover[0] != null) {
        $img_cover_url = wp_get_attachment_image_src($img_cover[0])[0];
    } else {
        $img_cover_url = asset_path("images/not-available.jpg");
    }
    $post_date = human_time_diff(strtotime("2021-12-08"), current_time('U')) . " ago";
    // echo '<pre>';
    // echo $post_date;
    // echo '</pre>';
    $productsList .= "
    <tr>
        <td>
            <img style='width:50px;' src='$img_cover_url' class='img-thumbnail m-0' alt=''>
        </td>
        <td>$product->name</td>
        <td>$product->price</td>
        <td>$product->promo</td>
        <td>$post_date</td>
        <td>
            <button type='button' class='btn btn-outline-primary rounded-0'>
                <i class='fas fa-eye'></i>
            </button>
            <button type='button' class='btn_edite_product btn btn-outline-success rounded-0' data-id='$product->id'>
                <i class='fas fa-edit'></i>
            </button>
            <button  type='button' class='btn_delete_product btn btn-outline-danger rounded-0' data-id='$product->id'>
                <i class='fas fa-trash-alt'></i>
            </button>
        </td>
    </tr>
    ";
}

$collectionsList = "";
foreach ($collections as $collection) {
    $collectionsList .= "<option value='" . $collection->id . "'>" . $collection->title . "</option>";
}



return "
    <div class='container mt-3'>
        <div class='row'>
            <div class='col'>
                <h5 class='text-capitalize fw-light'>Products</h5>
            </div>
        </div>
        <hr />
        <div
        class='
        row
        mt-3
        '
        >
            <ul class='nav nav-tabs mb-3' id='myTab' role='tablist'>
                <li class='nav-item' role='presentation'>
                    <button class='nav-link active rounded-0 text-dark' id='products-tab' data-bs-toggle='tab' data-bs-target='#products_content_tab' type='button' role='tab' aria-controls='products_content_tab' aria-selected='true'>Products</button>
                </li>
                <li class='nav-item' role='presentation'>
                    <button class='nav-link rounded-0 text-dark' id='new-product-tab' data-bs-toggle='tab' data-bs-target='#new_product_content_tab' type='button' role='tab' aria-controls='new_product_content_tab' aria-selected='false'>New Product</button>
                </li>
            </ul>
            <div class='tab-content' id='myTabContent'>
                <div class='tab-pane fade show active' id='products_content_tab' role='tabpanel' aria-labelledby='products-tab'>
                    <table id='example' class='table table-bordered nowrap mt-2' style='width:100%'>
                        <thead>
                            <tr>
                                <th>Cover Iamge</th>
                                <th>Name</th>
                                <th>Price</th>
                                <th>Promotion</th>
                                <th>Date</th>
                                <th>Options</th>
                            </tr>
                        </thead>
                        <tbody>
                            " . $productsList . "
                        </tbody>
                    </table>
                </div>
                <div class='tab-pane fade' id='new_product_content_tab' role='tabpanel' aria-labelledby='new-product-tab'>
                    <div class='row'>
                        <div class='col-8'>
                            <form method='POST' action='" . $_SERVER['PHP_SELF'] . "' id='add_product_form'>
                                <div class='mb-3'>
                                    <label for='product_name' class='form-label'>Product Name</label>
                                    <input type='text' value='" . $update_product_name . "' name='product_name' class='form-control rounded-0 border-primary' required id='product_name'>
                                </div>
                                <div class='mb-3'>
                                    <label for='product_price' class='form-label'>Product Price</label>
                                    <input type='number' value='" . $update_product_price . "' name='product_price' class='form-control rounded-0 border-primary' required id='product_price'>
                                </div>
                                <div class='mb-3'>
                                    <label for='product_promo' class='form-label'>Product Promotion</label>
                                    <input type='number' value='" . $update_product_promo . "' name='product_promo' class='form-control rounded-0 border-primary' id='product_promo'>
                                </div>
                                <div class='mb-3'>
                                    <label for='product_promo' class='form-label'>Product Image(s)<span id='p_i_count'>" . (isset($update_product) ? " - <b><mark>" . count(array_filter(explode(',', $update_product_ids))) . "</mark></b> image(s) found" : '') . "</span></label>
                                    <button type='button' id='select_product_images' class='form-control btn btn-outline-primary rounded-0'>Select Image(s)</button>
                                    <input type='hidden' value='" . $update_product_ids . "' id='product_image_ids' name='product_images' />
                                </div>
                                <div class='mb-3 w-100'>
                                    <label for='product_collection' class='form-label'>Product Collection</label>
                                    <select required class='form-control rounded-0 border-primary' name='product_collection'>
                                        <option value='' selected>Select A Collection</option>
                                        " . $collectionsList . "
                                    </select>
                                </div>
                                <div class='mb-3'>
                                    <label for='product_desc' class='form-label'>Product Description</label>
                                    <textarea class='form-control rounded-0 border-primary' name='product_desc'  id='product_desc_textarea'></textarea>
                                </div>
                                <input type='hidden' id='product_form_action_name' name='action' value='" . $form_action_name . "' />
                                <input type='hidden' name='product' value='" . ($update_product->id ?? '') . "' />

                                <button type='submit' value='create_new_product' id='codrock_add_new_product' name='codrock_add_new_product' class='btn btn-success rounded-0'>Save</button>
                            </form>
                        </div>
                        <div class='col-4'>
                            <div class='card rounded-0 border-primary p-0' >
                                <img id='product_image_view' src=" . $update_product_cover . " class='card-img-top' alt='...'>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
    jQuery(document).ready(function ($) {

        var table = $('#example').DataTable( {
            responsive: true
        } );

        new $.fn.dataTable.FixedHeader( table );
        jQuery('#add_product_form').submit(function(e){
            tinyMCE.triggerSave();
            var form_data = $(this).serializeArray();
            var form_data_obj = {};
            for (const element of form_data) {
                form_data_obj[element.name] = element.value;
            }
            form_data_obj['codrock_add_new_product']='submit';
            var fields = $(this).serialize().split('&');
            var error = false;
            fields.forEach(function(value, index, array){
                if(value.includes('product_images')){
                    var images = value.split('=')[1];
                    if(images==''){
                        toastr.error('Please choose at least one Product Image');
                        error = true;
                    }
                }
                if(value.includes('product_desc')){
                    var desc = value.split('=')[1];
                    if(desc==''){
                        toastr.error('please Enter Your Product Description');
                        error = true;
                    }
                }
            });
            if(error){
                toastr.error('error found');
                e.preventDefault();
                return false;
            }
        });
        $('#select_product_images').on('click', function (e) {
          var button = $(this),
            custom_uploader = wp.media({
              title: 'Insert image',
              library: {
                type: 'image'
              },
              button: {
                text: 'use image(s)'
              },
              multiple: true
            })
              .on('select', function () {
                var attachment = custom_uploader.state().get('selection').first().toJSON();
                $('#product_image_view').attr('src', attachment.url);
                var ids = [];
                $.each(custom_uploader.state().get('selection').toJSON(), function () {
                  ids.push(this.id);
                  console.log(this);
                });

                $('#product_image_ids').val(ids);
                $('#p_i_count').text(' | ' + ids.length + ' image(s) selected');
              }).open();
        });

        if(productFound){
            $('#products-tab').removeClass('active');
            $('#products_content_tab').removeClass('show active');
            $('#new-product-tab').addClass('active');
            $('#new_product_content_tab').addClass('show active');
            $('#codrock_add_new_product').val('update_product');
            $('#product_desc_textarea').html('" . $update_product_desc . "');
        }
        if(showProductCreateMessage){
            toastr.success('Product Created Successfully');
        }
        if(showProductUpdatedMessage){
            toastr.success('Product Info Updated Successfully');
        }
        if(showProductUpdateErrorMessage){
            toastr.error('Failed to Update Product Info');
        }

        $('.btn_delete_product').on('click', function () {
          var del = confirm('are you sure you want to remove this product?');
          if (del) {
            var parent = $(this).closest('tr');
            // console.log(parent);
            //document.location.reload(true);
            $.ajax({
              type: 'POST',
              dataType: 'json',
              url: '" . admin_url('admin-ajax.php') . "',
              data: { action: \"remove_product\", product : $(this).data('id')},
              success: function (response) {
                if (response.success) {
                  toastr.success(response.data);
                  parent.fadeOut();
                } else {
                  toastr.error(response.data);
                }
              }
            });
          }
        });

        $('.btn_edite_product').click(function () {

          var url = '" . admin_url('/admin.php?page=codrock-products&product=') . "' + $(this).data('id');
          window.location.href = url;
        });
      });

    </script>

";
