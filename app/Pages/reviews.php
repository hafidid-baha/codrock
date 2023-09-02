<?php

use function App\asset_path;

global $wpdb;

require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
$reviews_table = $wpdb->prefix . "reviews";
$products_table = $wpdb->prefix . "products";
$reviews = $wpdb->get_results("select * from $reviews_table");


$reviewList = "";
foreach ($reviews as $review) {
    $r_desc = substr_replace($review->description, '...', 40);
    $product = array_shift($wpdb->get_results("select id,name from $products_table where id = " . $review->product_id));
    // echo '<pre>';
    // echo print_r($product);
    // echo '</pre>';
    // die();
    $product_url = home_url('/product/?pid=' . $product->id);
    $btn_activate_review_classes = $review->status>0?'d-none':'';
    $btn_disactivate_review_classes = $review->status>0?'':'d-none';
    $btn_activate_review = "
    <button type='button' class='btn_activate_review $btn_activate_review_classes btn btn-outline-success rounded-0' data-id='$review->id' data-bs-toggle='tooltip' data-bs-placement='top' title='Activate'>
        <i class='fas fa-check-circle'></i>
    </button>";
    $btn_disactivate_review = "
    <button type='button' class='btn_disactivate_review $btn_disactivate_review_classes btn btn-outline-warning rounded-0' data-id='$review->id' data-bs-toggle='tooltip' data-bs-placement='top' title='Disactivate'>
        <i class='fas fa-hand-paper'></i>
    </button>";
    $btn_activate_disactivate = $btn_activate_review . $btn_disactivate_review;
    // echo '<pre>';
    // echo   var_dump($btn_activate_disactivate);
    // echo '</pre>';
    // die();
    $reviewList .= "
    <tr>
        <td>$review->fullname</td>
        <td>$review->email</td>
        <td>$r_desc</td>
        <td>$review->rating</td>
        <td><a href='$product_url' target=”_blank” class='link-primary text-decoration-none'>$product->name</a></td>
        <td>$review->date</td>
        <td>
            <button type='button' class='btn_view_review_desc btn btn-outline-primary rounded-0' data-desc='$review->description' data-id='$review->id' data-bs-toggle='tooltip' data-bs-placement='top' title='View Full Description'>
                <i class='fas fa-eye'></i>
            </button>
            $btn_activate_disactivate
            <button  type='button' class='btn_delete_review btn btn-outline-danger rounded-0' data-id='$review->id' data-bs-toggle='tooltip' data-bs-placement='top' title='Delete'>
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
                <h5 class='text-capitalize fw-light'>Reviews</h5>
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
                    <button class='nav-link active rounded-0 text-dark' id='Reviews-tab' data-bs-toggle='tab' data-bs-target='#Reviews_content_tab' type='button' role='tab' aria-controls='Reviews_content_tab' aria-selected='true'>Reviews</button>
                </li>
            </ul>
            <div class='tab-content' id='myTabContent'>
                <div class='tab-pane fade show active' id='Reviews_content_tab' role='tabpanel' aria-labelledby='Reviews-tab'>
                    <table id='example' class='table table-bordered nowrap mt-2' style='width:100%'>
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Description</th>
                                <th>Rating</th>
                                <th>Product</th>
                                <th>Date</th>
                                <th>Options</th>
                            </tr>
                        </thead>
                        <tbody>
                            " . $reviewList . "
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal -->
    <div class='modal fade' id='reviewDesc' tabindex='-1' aria-labelledby='exampleModalLabel' aria-hidden='true'>
      <div class='modal-dialog modal-dialog-centered'>
        <div class='modal-content'>
          <div class='modal-header'>
            <h5 class='modal-title' id='exampleModalLabel'>Review</h5>
            <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
          </div>
          <div class='modal-body' id='review_full_desc'>

          </div>
        </div>
      </div>
    </div>
    <script>
    jQuery(document).ready(function ($) {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle=\"tooltip\"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
        });
        var table = $('#example').DataTable( {
            responsive: true
        } );

        new $.fn.dataTable.FixedHeader( table );
        $('.btn_view_review_desc').click(function(){
          var reviewDesc = new bootstrap.Modal(document.getElementById('reviewDesc'), {
            keyboard: false
          });
          reviewDesc.show();
          $('#review_full_desc').html($(this).data('desc'));
        });

        $('.btn_activate_review').on('click', function () {
            var review = $(this).data('id');
            var elem = $(this);
            $.ajax({
            type: 'POST',
            dataType: 'json',
            url: '" . admin_url('admin-ajax.php') . "',
            data: { action: \"activate_review\", review : review},
            success: function (response) {
                if (response.success) {
                toastr.success(response.data);
                elem.addClass('d-none');
                elem.parent().find('.btn_disactivate_review').removeClass('d-none');
                } else {
                toastr.error(response.data);
                }
            }
            });
        });
        $('.btn_disactivate_review').on('click',function(){
            var review = $(this).data('id');
            var elem = $(this);
            $.ajax({
            type: 'POST',
            dataType: 'json',
            url: '" . admin_url('admin-ajax.php') . "',
            data: { action: \"disactivate_review\", review : review},
            success: function (response) {
                if (response.success) {
                    toastr.success(response.data);
                    elem.addClass('d-none');
                    elem.parent().find('.btn_activate_review').removeClass('d-none');
                    // console.log();
                } else {
                    toastr.error(response.data);
                }
            }
            });
        });

        $('.btn_delete_review').on('click', function () {
            var del = confirm('Are You Sure You Want To Delete this Review?');
            if(del){
                var review = $(this).data('id');
                var parent = $(this).closest('tr');
                $.ajax({
                    type: 'POST',
                    dataType: 'json',
                    url: '" . admin_url('admin-ajax.php') . "',
                    data: { action: \"remove_review\", review : review},
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

      });

    </script>

";
