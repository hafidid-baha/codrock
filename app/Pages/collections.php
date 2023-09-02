<?php

use function App\asset_path;

global $wpdb;

require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
$collections_table = $wpdb->prefix . "collections";
$collections = $wpdb->get_results("select * from $collections_table");


// $update_product = null;
// if (isset($_GET['product']) && intval($_GET['product']) > 0) {
//     $update_product = array_shift($wpdb->get_results("select * from $products_table where id = " . intval($_GET['product'])));
// }
// $update_product_price = isset($update_product) ? $update_product->price : "";
// $update_product_promo = isset($update_product) ? $update_product->promo : "";
// $update_product_name = isset($update_product) ? $update_product->name : "";
// $update_product_desc = isset($update_product) ? $update_product->description : "";
// $update_product_ids = $update_product->images;
// $update_product_cover = isset($update_product) && $update_product->images != "" ? wp_get_attachment_image_src(explode(',', $update_product->images)[0], array(354, 354))[0] : asset_path('images/product-image-placeholder.jpg');
// $save_form_action = isset($update_product) ? "update_product" : "create_new_product";

$update_collection = null;
if (isset($_GET['collection']) && intval($_GET['collection']) > 0) {
    $update_collection = array_shift($wpdb->get_results("select * from $collections_table where id = " . intval($_GET['collection'])));
    echo "
    <script>
        var collectionFound = true;
    </script>
    ";
} else {
    echo "
    <script>
        var collectionFound = false;
    </script>
    ";
}
if (isset($_GET['success']) && $_GET['success'] == 'collection_created') {
    echo "
    <script>
        var showCollectionCreateMessage = true;
    </script>
    ";
} else {
    echo "
    <script>
        var showCollectionCreateMessage = false;
    </script>
    ";
}
if (isset($_GET['success']) && $_GET['success'] == 'collection_Updated') {
    echo "
    <script>
        var showCollectionUpdatedMessage = true;
    </script>
    ";
} else {
    echo "
    <script>
        var showCollectionUpdatedMessage = false;
    </script>
    ";
}
if (isset($_GET['error']) && $_GET['error'] == 'collection_Updated') {
    echo "
    <script>
        var showCollectionUpdatedError = true;
    </script>
    ";
} else {
    echo "
    <script>
        var showCollectionUpdatedError = false;
    </script>
    ";
}
$form_action_name = isset($_GET['collection']) ?  'update_collection' : 'create_new_collection';
$collection_title = isset($update_collection) ? $update_collection->title : "";
$collection_sub_title = isset($update_collection) ? $update_collection->sub_title : "";
$collection_image = isset($update_collection) ? $update_collection->image : "";
$update_collection_cover = isset($update_collection) && $update_collection->image > 0 ? wp_get_attachment_image_src($update_collection->image, array(354, 354))[0] : asset_path('images/product-image-placeholder.jpg');

$collectionList = "";
foreach ($collections as $collection) {

    $collectionList .= "
    <tr>
        <td>$collection->title</td>
        <td>$collection->sub_title</td>
        <td>
            <button type='button' class='btn btn-outline-primary rounded-0'>
                <i class='fas fa-eye'></i>
            </button>
            <button type='button' class='btn_edite_collection btn btn-outline-success rounded-0' data-id='$collection->id'>
                <i class='fas fa-edit'></i>
            </button>
            <button  type='button' class='btn_delete_collection btn btn-outline-danger rounded-0' data-id='$collection->id'>
                <i class='fas fa-trash-alt'></i>
            </button>
        </td>
    </tr>
    ";
}

return "
    <div class='container mt-3'>
        <div class='row'>
            <div class='col'>
                <h5 class='text-capitalize fw-light'>Collections</h5>
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
                    <button class='nav-link active rounded-0 text-dark' id='Collections-tab' data-bs-toggle='tab' data-bs-target='#Collections_content_tab' type='button' role='tab' aria-controls='Collections_content_tab' aria-selected='true'>Collections</button>
                </li>
                <li class='nav-item' role='presentation'>
                    <button class='nav-link rounded-0 text-dark' id='new-collection-tab' data-bs-toggle='tab' data-bs-target='#new_collection_content_tab' type='button' role='tab' aria-controls='new_collection_content_tab' aria-selected='false'>New Collection</button>
                </li>
            </ul>
            <div class='tab-content' id='myTabContent'>
                <div class='tab-pane fade show active' id='Collections_content_tab' role='tabpanel' aria-labelledby='Collections-tab'>
                    <table id='example' class='table table-bordered nowrap mt-2' style='width:100%'>
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Sub Title</th>
                                <th>Options</th>
                            </tr>
                        </thead>
                        <tbody>
                            " . $collectionList . "
                        </tbody>
                    </table>
                </div>
                <div class='tab-pane fade' id='new_collection_content_tab' role='tabpanel' aria-labelledby='new-collection-tab'>
                    <div class='row'>
                        <div class='col-8'>
                            <form method='POST' action='" . $_SERVER['PHP_SELF'] . "' id='add_collection_form'>
                                <div class='mb-3'>
                                    <label for='collection_title' class='form-label'>Collection Title</label>
                                    <input type='text' value='" . $collection_title . "' name='collection_title' class='form-control rounded-0 border-primary' required id='collection_title'>
                                </div>
                                <div class='mb-3'>
                                    <label for='collection_sub_title' class='form-label'>Collection Sub Title</label>
                                    <input type='text' value='" . $collection_sub_title . "' name='collection_sub_title' class='form-control rounded-0 border-primary' required id='collection_sub_title'>
                                </div>
                                <div class='mb-3'>
                                    <label for='col_image' class='form-label'>Collection Image</label>
                                    <button type='button' id='select_collection_images' class='form-control btn btn-outline-primary rounded-0'>Select Image</button>
                                    <input type='hidden' value='" . $collection_image . "' id='collection_image_id' name='collection_image' />
                                </div>
                                <input type='hidden' name='action' value='" . $form_action_name . "' />
                                <input type='hidden' name='collection' value='" . ($update_collection->id ?? '') . "' />

                                <button type='submit' value='submit' name='codrock_add_new_collection_btn' id='codrock_add_new_collection_btn' class='btn btn-success rounded-0'>Save</button>
                            </form>
                        </div>
                        <div class='col-4'>
                            <div class='card rounded-0 border-primary p-0' >
                                <img id='collection_image_view' src=" . $update_collection_cover . " class='card-img-top' alt='...'>

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
        jQuery('#add_collection_form').submit(function(e){
            var form_data = $(this).serializeArray();
            var form_data_obj = {};
            for (const element of form_data) {
                form_data_obj[element.name] = element.value;
            }

            form_data_obj['codrock_add_new_collection_btn']='submit';
            var fields = $(this).serialize().split('&');
            var error = false;
            fields.forEach(function(value, index, array){
                if(value.includes('collection_image')){
                    var images = value.split('=')[1];
                    if(images==''){
                        toastr.error('Please choose Your Collection Image');
                        error = true;
                    }
                }
            });
            if(error){
                // toastr.error('Peqse Check Your Data');
                e.preventDefault();
                return false;
            }
        });
        // jQuery('#add_collection_form').ajaxForm({
        //   success: function (res) {
        //     toastr.info(res.data);
        //     // jQuery('#add_collection_form')[0].reset();

        //     $('#add_collection_form').find('input[type=text],input[type=number], textarea').val('');
        //   },
        //   error: function (res) {
        //     toastr.error('Failed To Update Product Info');
        //   }
        // });

        if(collectionFound){
            $('#Collections-tab').removeClass('active');
            $('#Collections_content_tab').removeClass('show active');
            $('#new-collection-tab').addClass('active');
            $('#new_collection_content_tab').addClass('show active');
        }


        $('.btn_delete_collection').on('click', function () {
          var del = confirm('are you sure you want to remove this product?');
          if (del) {
            var parent = $(this).closest('tr');
            // console.log(parent);
            //document.location.reload(true);
            $.ajax({
              type: 'POST',
              dataType: 'json',
              url: '" . admin_url('admin-ajax.php') . "',
              data: { action: \"remove_collection\", collection : $(this).data('id')},
              success: function (response) {
                if (response.success) {
                  toastr.success(response.data);
                  parent.fadeOut().remove();
                } else {
                  toastr.error(response.data);
                }
              }
            });
          }
        });

        $('.btn_edite_collection').click(function () {
          // $('#products-tab').removeClass('active');
          // $('#products_content_tab').removeClass('show active');
          // $('#new-collection-tab').addClass('active');
          // $('#new_collection_content_tab').addClass('show active');
          var url = window.location.href + '&collection=' + $(this).data('id');
          url = url.replace('&success=collection_created','');
          url = url.replace('&success=collection_Updated','');
          window.location.href = url;
        });

        $('#select_collection_images').on('click', function (e) {
            var button = $(this),
              custom_uploader = wp.media({
                title: 'Insert image',
                library: {
                  type: 'image'
                },
                button: {
                  text: 'use image'
                },
                multiple: false
              })
                .on('select', function () {
                  var attachment = custom_uploader.state().get('selection').first().toJSON();
                  $('#collection_image_view').attr('src', attachment.url);
                  var ids = [];
                  $.each(custom_uploader.state().get('selection').toJSON(), function () {
                    ids.push(this.id);
                    console.log(this);
                  });

                  $('#collection_image_id').val(ids);
                }).open();
        });

        if(showCollectionCreateMessage){
            toastr.success('Collection Created Successfully');
        }
        if(showCollectionUpdatedMessage){
            toastr.success('Collection Info Are Updated');
        }
        if(showCollectionUpdatedError){
            toastr.error('Failed To Update Your Collection Info');
        }
      });

    </script>

";
