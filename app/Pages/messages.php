<?php

use function App\asset_path;

global $wpdb;

require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
$messages_table = $wpdb->prefix . "messages";
$messages = $wpdb->get_results("select * from $messages_table");

// echo '<pre>';
// echo print_r($messages);
// echo '</pre>';
// die();

$form_action_name = isset($_GET['collection']) ?  'update_collection' : 'create_new_collection';
$collection_title = isset($update_collection) ? $update_collection->title : "";
$collection_sub_title = isset($update_collection) ? $update_collection->sub_title : "";
$collection_image = isset($update_collection) ? $update_collection->image : "";
$update_collection_cover = isset($update_collection) && $update_collection->image > 0 ? wp_get_attachment_image_src($update_collection->image, array(354, 354))[0] : asset_path('images/product-image-placeholder.jpg');

$MessagesList = "";
foreach ($messages as $ms) {

    $MessagesList .= "
    <tr>
        <td>$ms->subject</td>
        <td>$ms->email</td>
        <td>" . substr_replace($ms->message, '...', 40) . "</td>
        <td>
            <button type='button' class='viewMessage btn btn-outline-primary rounded-0' data-bs-toggle='tooltip' data-bs-placement='top' title='View' data-message='$ms->message'>
                <i class='fas fa-eye'></i>
            </button>
            <button type='button' class='btn_open_replay_model btn btn-outline-success rounded-0' data-bs-toggle='tooltip' data-bs-placement='top' title='Replay' data-email='$ms->email' data-subject='$ms->subject'>
                <i class='fas fa-envelope-open-text'></i>
            </button>

            <button  type='button' class='btn_delete_message btn btn-outline-danger rounded-0' data-bs-toggle='tooltip' data-bs-placement='top' title='Delete' data-id='$ms->id'>
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
                <h5 class='text-capitalize fw-light'>messages</h5>
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
                    <button class='nav-link active rounded-0 text-dark' id='messages-tab' data-bs-toggle='tab' data-bs-target='#messages_content_tab' type='button' role='tab' aria-controls='messages_content_tab' aria-selected='true'>messages</button>
                </li>
            </ul>
            <div class='tab-content' id='myTabContent'>
                <div class='tab-pane fade show active' id='messages_content_tab' role='tabpanel' aria-labelledby='messages-tab'>
                    <table id='example' class='table table-bordered nowrap mt-2' style='width:100%'>
                        <thead>
                            <tr>
                                <th>Email</th>
                                <th>Subject</th>
                                <th>Message</th>
                                <th>Optiond</th>
                            </tr>
                        </thead>
                        <tbody>
                            " . $MessagesList . "
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal -->
    <div class='modal fade' id='reviewMessage' tabindex='-1' aria-labelledby='exampleModalLabel' aria-hidden='true'>
      <div class='modal-dialog modal-dialog-centered'>
        <div class='modal-content'>
          <div class='modal-header'>
            <h5 class='modal-title' id='exampleModalLabel'>Review</h5>
            <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
          </div>
          <div class='modal-body' id='full_message'>

          </div>
        </div>
      </div>
    </div>
    <!-- replay Modal -->
    <div class='modal fade' id='replayModel' tabindex='-1' aria-labelledby='exampleModalLabel' aria-hidden='true'>
      <div class='modal-dialog modal-dialog-centered'>
        <div class='modal-content'>
          <div class='modal-header'>
            <h5 class='modal-title' id='exampleModalLabel'>Write Your Replay</h5>
            <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
          </div>
          <div class='modal-body'>
            <div>
                <div class='mb-3'>
                    <label for='emailto' class='form-label'>Email To:</label>
                    <input type='email' value='' class='form-control rounded-0 disabled' disabled id='emailto'>
                </div>
                <div class='mb-3'>
                    <label for='fullname' class='form-label'>Full Name :</label>
                    <input type='email' value='ahmed moustafa' class='form-control rounded-0 disabled' disabled id='fullname'>
                </div>
                <div class='mb-3'>
                    <label for='subject' class='form-label'>Subject :</label>
                    <input type='email' class='form-control rounded-0' id='subject'>
                </div>
                <div class='mb-3'>
                    <label for='message' class='form-label'>Message :</label>
                    <textarea class='form-control rounded-0' id='user_message'></textarea>
                </div>

                <button type='submit' class='btn_send_email btn btn-primary rounded-0'>Send Email</button>
            </div>
          </div>
        </div>
      </div>
    </div>
    <script>
    jQuery(document).ready(function ($) {
        var replayModel = new bootstrap.Modal(document.getElementById('replayModel'), {
            keyboard: false
        });
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle=\"tooltip\"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
        });
        var table = $('#example').DataTable( {
            responsive: true
        } );

        $('.viewMessage').click(function(){
            var reviewMes = new bootstrap.Modal(document.getElementById('reviewMessage'), {
              keyboard: false
            });
            reviewMes.show();
            $('#full_message').html($(this).data('message'));
        });

        $('.btn_open_replay_model').click(function(){
            replayModel.show();
            $('#emailto').val($(this).data('email'));
            $('#subject').val($(this).data('subject'));
        });

        new $.fn.dataTable.FixedHeader( table );

        $('.btn_delete_message').on('click', function () {
          var del = confirm('are you sure you want to remove this message?');
          if (del) {
            var parent = $(this).closest('tr');
            // console.log(parent);
            //document.location.reload(true);
            $.ajax({
              type: 'POST',
              dataType: 'json',
              url: '" . admin_url('admin-ajax.php') . "',
              data: { action: \"remove_message\", message : $(this).data('id')},
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

        $('.btn_send_email').on('click', function () {
            replayModel.hide();
            $.ajax({
                type: 'POST',
                dataType: 'json',
                url: '" . admin_url('admin-ajax.php') . "',
                data: { action: \"send_email\",subject:$('#subject').val(),email:$('#emailto').val(),fullname:$('#fullname').val() ,message : $('#user_message').val()},
                success: function (response) {
                    if (response.success) {
                        toastr.success(response.data);
                    } else {
                        toastr.error(response.data);
                    }
                }
              });
        });

      });

    </script>

";
