<?php

use function App\asset_path;
use function App\prepare_orders_sql_results;

global $wpdb;

require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
$Orders_table = $wpdb->prefix . "orders";
$status_table = $wpdb->prefix . "status";
$order_status_table = $wpdb->prefix . "order_status";
// $Orders = $wpdb->get_results("select * from $Orders_table");
// $Orders = $wpdb->get_results("SELECT $Orders_table.*,$order_status_table.status_id FROM $Orders_table inner JOIN $order_status_table on $Orders_table.order_id = $order_status_table.order_id  WHERE $Orders_table.order_id = 10");
$Orders = $wpdb->get_results("SELECT $Orders_table.*,$status_table.title,$status_table.color FROM $Orders_table LEFT JOIN $order_status_table on $Orders_table.order_id = $order_status_table.order_id LEFT JOIN $status_table on $order_status_table.status_id = $status_table.id");
$Orders = prepare_orders_sql_results($Orders);
$available_status = $wpdb->get_results("select * from $status_table");
// echo '<pre>';
// echo var_dump();
// echo '</pre>';
// die();
$status_list = "";
$select_status_list = "";
foreach ($available_status as $s) {
    $status_list .= "
    <div class='border d-inline p-2' style='background-color:" . $s->color . ";'>
        <span class='badge'>
            " . $s->title . "
        </span>
        <span id='btn_delete_status' class='d-inline-block' style='cursor:pointer' data-id='$s->id'>
            <svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='#FFFFFF' class='bi bi-x-circle' viewBox='0 0 16 16'>
                <path d='M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z'/>
                <path d='M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z'/>
            </svg>
        </span>
        <span id='btn_edit_status' class='d-inline-block' style='cursor:pointer' data-id='$s->id' data-title='$s->title' data-color='$s->color'>
            <svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='#FFFFFF' class='bi bi-pencil-square' viewBox='0 0 16 16'>
                <path d='M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z'/>
                <path fill-rule='evenodd' d='M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5v11z'/>
            </svg>
        </span>
    </div>
    ";
    $select_status_list .= "
    <option value='" . $s->id . "'>" . $s->title . "</option>
    ";
}

$orders_list  = "";
foreach ($Orders as $order) {
    $allstatus = '';

    foreach ($order->all_status as $s) {
        $allstatus .= '<span class="badge mx-1" style="background:'.$s['color'].'">'.$s['title'].'</span>';
        // echo '<pre>';
        // echo lkjlkjkljklj;
        // echo '</pre>';
        // die();

    }
    $orders_list .= '
        <tr>
            <td>' . $order->name . '</td>
            <td>' . $order->country . '</td>
            <td>' . $order->city . '</td>
            <td>' . $order->phone . '</td>
            <td>' . $order->product_id . '</td>
            <td>' . $order->qte . '</td>
            <td>'.$allstatus.'</td>
            <td>' . $order->address . '</td>
            <td>' . $order->unit_price . '</td>
            <td>' . $order->date . '</td>
            <td>
                <button  type="button" class="btn_delete_order btn btn-outline-danger rounded-0"
                data-id="' . $order->order_id . '">
                    <i class="fas fa-trash-alt"></i>
                </button>
                <button type="button" class="btn_edite_order btn btn-outline-success rounded-0" data-id="' . $order->order_id . '">
                    <i class="fas fa-edit"></i>
                </button>
            </td>

        </tr>
    ';
}
// select $order_status_table.* from wp_order_status
//         inner join wp_Orders on wp_order_status.order_id = wp_Orders.order_id
//         inner join wp_status on wp_order_status.status_id = wp_status.id
//     where wp_order_status.order_id = 4


$update_order = null;
$update_order_status = null;
$order_status_list = "";
if (isset($_GET['order']) && intval($_GET['order']) > 0) {
    $update_order = array_shift($wpdb->get_results("select * from $Orders_table where order_id = " . intval($_GET['order'])));
    // $update_order_status = $wpdb->get_results(
    //     "
    // select $status_table.* from $order_status_table
    //     inner join $Orders_table on $order_status_table.order_id = $Orders_table.order_id
    //     inner join $status_table on $order_status_table.status_id = $status_table.id
    // where $order_status_table.order_id = " . intval($_GET['order'])
    // );
    $update_order_status = $wpdb->get_results(
        "
    select $status_table.* from $order_status_table
        inner join $status_table on $order_status_table.status_id = $status_table.id
    where $order_status_table.order_id = " . intval($_GET['order'])
    );

    // echo '<pre>';
    // echo var_dump($wpdb->queries);
    // echo '</pre>';
    foreach ($update_order_status as $os) {
        $order_status_list .= "
        <span class='badge p-2 m-1' style='background-color:" . $os->color . "'>        "
            . $os->title .
            "
        <span class='btn_remove_order_status' style='cursor:pointer;' data-status='" . $os->id . "' data-order='" . $update_order->order_id . "'>
            <svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='currentColor' class='bi bi-x-lg' viewBox='0 0 16 16'>
                <path fill-rule='evenodd' d='M13.854 2.146a.5.5 0 0 1 0 .708l-11 11a.5.5 0 0 1-.708-.708l11-11a.5.5 0 0 1 .708 0Z'/>
                <path fill-rule='evenodd' d='M2.146 2.146a.5.5 0 0 0 0 .708l11 11a.5.5 0 0 0 .708-.708l-11-11a.5.5 0 0 0-.708 0Z'/>
            </svg>
        </span>
        </span>";
    }
    echo "
    <script>
        var orderFound = true;
    </script>
    ";
} else {
    echo "
    <script>
        var orderFound = false;
    </script>
    ";
}

$order_client_name = isset($update_order) ? $update_order->name : "";
$order_client_phone = isset($update_order) ? $update_order->phone : "";
$order_client_city = isset($update_order) ? $update_order->city : "";
$order_client_address = isset($update_order) ? $update_order->address : "";
$order_client_country = isset($update_order) ? $update_order->country : "";
$order_client_qte = isset($update_order) ? $update_order->qte : "";


return "
    <div class='container mt-3'>
        <div class='row'>
            <div class='col'>
                <h5 class='text-capitalize fw-light'>Orders</h5>
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
                    <button class='nav-link active rounded-0 text-dark' id='Orders-tab' data-bs-toggle='tab' data-bs-target='#Orders_content_tab' type='button' role='tab' aria-controls='Orders_content_tab' aria-selected='true'>Orders</button>
                </li>
                <li class='nav-item' role='presentation'>
                    <button class='nav-link rounded-0 text-dark' id='add-new-status-tab' data-bs-toggle='tab' data-bs-target='#add_new_status_content_tab' type='button' role='tab' aria-controls='add_new_status_content_tab' aria-selected='false'>Order Status</button>
                </li>
                <li class='nav-item' role='presentation'>
                    <button class='nav-link rounded-0 text-dark d-none' id='edit-order-tab' data-bs-toggle='tab' data-bs-target='#edit_order_content_tab' type='button' role='tab' aria-controls='edit_order_content_tab' aria-selected='false'>Order Details</button>
                </li>
            </ul>
            <div class='tab-content' id='myTabContent'>
                <div class='tab-pane fade show active' id='Orders_content_tab' role='tabpanel' aria-labelledby='Orders-tab'>
                    <table id='example' class='table table-bordered nowrap mt-2' style='width:100%'>
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Country</th>
                                <th>City</th>
                                <th>Phone</th>
                                <th>Product</th>
                                <th>Qte</th>
                                <th>Order State</th>
                                <th>Address</th>
                                <th>Unit Price</th>
                                <th>Date</th>
                                <th>Options</th>
                            </tr>
                        </thead>
                        <tbody>
                            " . $orders_list . "
                        </tbody>
                    </table>
                </div>
                <div class='tab-pane' id='edit_order_content_tab' role='tabpanel' aria-labelledby='edit-order-tab'>
                    <div class='row'>
                        <div class='col-12'>
                            <form method='POST' action='" . $_SERVER['PHP_SELF'] . "' id='edit_order_form'>
                                <div class='mb-3'>
                                    <label for='order_name' class='form-label'>Name</label>
                                    <input type='text' value='" . $order_client_name . "' name='order_name' class='form-control rounded-0 border-primary' required id='order_name'>
                                </div>
                                <div class='mb-3'>
                                    <label for='order_phone' class='form-label'>Phone</label>
                                    <input type='number' value='" . $order_client_phone . "' name='order_phone' class='form-control rounded-0 border-primary' required id='order_phone'>
                                </div>
                                <div class='mb-3'>
                                    <label for='order_city' class='form-label'>City</label>
                                    <input type='text' value='" . $order_client_city . "' name='order_city' class='form-control rounded-0 border-primary' id='order_city'>
                                </div>
                                <div class='mb-3'>
                                    <label for='order_address' class='form-label'>Address</label>
                                    <input type='text' value='" . $order_client_address . "' name='order_address' class='form-control rounded-0 border-primary' id='order_address'>
                                </div>
                                <div class='mb-3'>
                                    <label for='order_country' class='form-label'>Country</label>
                                    <input type='text' value='" . $order_client_country . "' name='order_country' class='form-control rounded-0 border-primary' id='order_country'>
                                </div>
                                <div class='mb-3'>
                                    <label for='order_qte' class='form-label'>Qte</label>
                                    <input type='number' value='" . $order_client_qte . "' name='order_qte' class='form-control rounded-0 border-primary' id='order_qte'>
                                </div>
                                <div class='mb-3'>
                                    <label for='order_qte' class='form-label'>Change Order Status</label>
                                    <div class='mb-1'>
                                        " . $order_status_list . "
                                    </div>
                                    <select name='order_status' class='form-select form-select-lg mb-3' aria-label='.form-select-lg example'>
                                        <option value='0' selected>Select Status</option>
                                        " . $select_status_list . "
                                    </select>

                                </div>
                                <input type='hidden' name='order' value='" . ($update_order->order_id ?? '') . "' />

                                <button type='submit' id='codrock_update_order' name='codrock_update_order' value='codrock_update_order' class='btn btn-success rounded-0'>Save</button>
                            </form>
                        </div>

                    </div>
                </div>
                <div class='tab-pane' id='add_new_status_content_tab' role='tabpanel' aria-labelledby='add-new-status-tab'>
                    <div class='row'>
                        <div class='col-12'>
                        <div class='mb-3'>
                            <label for='order_qte' class='form-label'>Existing Order Status</label>
                            <div class='mb-1'>
                            " . $status_list . "
                            </div>
                        </div>
                        <hr />
                        <h5>Status Info (create / update order status)</h5>
                        <form method='POST' action='" . $_SERVER['PHP_SELF'] . "' id='add_status_form'>
                                <div class='mb-3'>
                                    <label for='status_title' class='form-label'>Title</label>
                                    <input type='text' required value='' name='status_title' class='form-control rounded-0 border-primary' required id='status_title'>
                                </div>
                                <div class='mb-3'>
                                    <label for='status_color' class='form-label'>Color</label>
                                    <input type='color' value='' required value='' name='status_color' class='form-control rounded-0 border-primary' required id='status_color'>
                                </div>
                                <input type='hidden' value='' name='status_id' />
                                <button type='submit' value='add_new_order_status' id='codrock_add_new_status' name='codrock_add_new_order_status' class='btn btn-success rounded-0'>Save</button>
                                <button type='submit' value='update_order_status' id='codrock_update_status' name='codrock_update_order_status' class='btn btn-primary rounded-0 d-none'>Update</button>
                                <button type='button' id='codrock_reset_status_form' class='btn btn-warning rounded-0 d-none'>Reset</button>
                        </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
    jQuery(document).ready(function ($) {
        var table = $('#example').DataTable( {
            responsive: true,
            columnDefs: [
                { responsivePriority: 1, targets: 0 },
                { responsivePriority: 2, targets: -1 },
                { responsivePriority: 3, targets: 2 },
                { responsivePriority: 4, targets: 1 },
                { responsivePriority: 5, targets: -4 },
                { responsivePriority: 6, targets: -5 }
            ]
        } );

        new $.fn.dataTable.FixedHeader( table );

        $('.btn_delete_order').click(function () {
            var del = confirm('are you sure you want to remove this Order?');
            if (del) {
                var parent = $(this).closest('tr');
                var order_id = $(this).data('id');
                $.ajax({
                    type: 'POST',
                    dataType: 'json',
                    url: '" . admin_url('admin-ajax.php') . "',
                    data: { action: \"remove_order\", order : order_id},
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

        $('.btn_edite_order').click(function () {
            // $('#products-tab').removeClass('active');
            // $('#products_content_tab').removeClass('show active');
            // $('#new-collection-tab').addClass('active');
            // $('#new_collection_content_tab').addClass('show active');
            var url = window.location.href + '&order=' + $(this).data('id');
            // url = url.replace('&success=collection_created','');
            // url = url.replace('&success=collection_Updated','');
            window.location.href = url;
        });

        $('.btn_remove_order_status').click(function () {
            var con = confirm('Are You Sure You Want To Remove This Item?');
            if(con){
                var order_status_id = $(this).data('status');
                var order_id = $(this).data('order');
                var parent = $(this).closest('span').parent();
                // alert(order_status_id);
                // return;
                $.ajax({
                    type: 'POST',
                    dataType: 'json',
                    url: '" . admin_url('admin-ajax.php') . "',
                    data: { action: \"detail_remove_order_status\", order_status : order_status_id,order: order_id},
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

        $('#btn_delete_status').click(function () {
            var del = confirm('are you sure you want to delete this status?');
            if(del){
                var parent = $(this).closest('div');
                var status_id = $(this).data('id');
                console.log(status_id);
                $.ajax({
                    type: 'POST',
                    dataType: 'json',
                    url: '" . admin_url('admin-ajax.php') . "',
                    data: { action: \"remove_order_status\", status : status_id},
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

        $('#btn_edit_status').click(function () {
            var status_id = $(this).data('id');
            var status_title = $(this).data('title');
            var status_color = $(this).data('color');
            // hide save btn and show update and reset
            $('#codrock_add_new_status').hide();
            $('#codrock_update_status').removeClass('d-none');
            $('#codrock_reset_status_form').removeClass('d-none');
            $(\"input[name='status_title']\").val(status_title);
            $(\"input[name='status_color']\").val(status_color);
            $(\"input[name='status_id']\").val(status_id);
        });
        $('#codrock_reset_status_form').click(function(){
            $('#add_status_form').trigger('reset');
            $('#codrock_add_new_status').show();
            $('#codrock_update_status').addClass('d-none');
            $('#codrock_reset_status_form').addClass('d-none');
        });

        if(orderFound){
            $('#Orders-tab').removeClass('active');
            $('#Orders_content_tab').removeClass('fade show active');
            $('#edit-order-tab').addClass('active');
            $('#edit_order_content_tab').addClass('fade show active');
        }

    });

    </script>

";
