<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <!-- Main content -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h4>Custom message </h4>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= base_url('admin/home') ?>">Home</a></li>
                        <li class="breadcrumb-item active">Custom message </li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-info">
                        <!-- form start -->
                        <form class="form-horizontal form-submit-event" action="<?= base_url('admin/custom_notification/add_notification'); ?>" method="POST" id="add_product_form" enctype="multipart/form-data">
                            <?php
                            if (isset($fetched_data[0]['id'])) {
                            ?>
                                <input type="hidden" id="edit_custom_notification" name="edit_custom_notification" value="<?= @$fetched_data[0]['id'] ?>">
                                <input type="hidden" id="update_id" name="update_id" value="1">
                            <?php
                            }
                            ?>
                            <div class="card-body">
                                <div class="form-group row">
                                    <label for="type" class="col-sm-2 col-form-label">Type <span class='text-danger text-sm'>*</span></label>
                                    <div class="col-sm-10">
                                        <select class='form-control' name='type' require="">
                                            <option value=" ">Select Type</option>
                                            <option value='place_order' <?= (isset($fetched_data[0]['type']) &&  $fetched_data[0]['type'] == 'place_order') ? 'selected' : ''; ?>><?php echo ucwords(str_replace('_', " ", "place_order")); ?></option>
                                            <option value='settle_cashback_discount' <?= (isset($fetched_data[0]['type']) &&  $fetched_data[0]['type'] == 'settle_cashback_discount') ? 'selected' : ''; ?>><?php echo ucwords(str_replace('_', " ", "settle_cashback_discount")); ?></option>
                                            <option value='settle_seller_commission' <?= (isset($fetched_data[0]['type']) &&  $fetched_data[0]['type'] == 'settle_seller_commission') ? 'selected' : ''; ?>><?php echo ucwords(str_replace('_', " ", "settle_seller_commission")); ?></option>
                                            <option value='customer_order_update' <?= (isset($fetched_data[0]['type']) &&  $fetched_data[0]['type'] == 'customer_order_update') ? 'selected' : ''; ?>><?php echo ucwords(str_replace('_', " ", "customer_order_update")); ?></option>
                                            <option value='customer_order_received' <?= (isset($fetched_data[0]['type']) &&  $fetched_data[0]['type'] == 'customer_order_received') ? 'selected' : ''; ?>><?php echo ucwords(str_replace('_', " ", "customer_order_received")); ?></option>
                                            <option value='customer_order_processed' <?= (isset($fetched_data[0]['type']) &&  $fetched_data[0]['type'] == 'customer_order_processed') ? 'selected' : ''; ?>><?php echo ucwords(str_replace('_', " ", "customer_order_processed")); ?></option>
                                            <option value='customer_order_shipped' <?= (isset($fetched_data[0]['type']) &&  $fetched_data[0]['type'] == 'customer_order_shipped') ? 'selected' : ''; ?>><?php echo ucwords(str_replace('_', " ", "customer_order_shipped")); ?></option>
                                            <option value='customer_order_delivered' <?= (isset($fetched_data[0]['type']) &&  $fetched_data[0]['type'] == 'customer_order_delivered') ? 'selected' : ''; ?>><?php echo ucwords(str_replace('_', " ", "customer_order_delivered")); ?></option>
                                            <option value='customer_order_cancelled' <?= (isset($fetched_data[0]['type']) &&  $fetched_data[0]['type'] == 'customer_order_cancelled') ? 'selected' : ''; ?>><?php echo ucwords(str_replace('_', " ", "customer_order_cancelled")); ?></option>
                                            <option value='customer_order_returned' <?= (isset($fetched_data[0]['type']) &&  $fetched_data[0]['type'] == 'customer_order_returned') ? 'selected' : ''; ?>><?php echo ucwords(str_replace('_', " ", "customer_order_returned")); ?></option>
                                            <option value='delivery_boy_order_deliver' <?= (isset($fetched_data[0]['type']) &&  $fetched_data[0]['type'] == 'delivery_boy_order_deliver') ? 'selected' : ''; ?>><?php echo ucwords(str_replace('_', " ", "delivery_boy_order_deliver")); ?></option>
                                            <option value='wallet_transaction' <?= (isset($fetched_data[0]['type']) &&  $fetched_data[0]['type'] == 'wallet_transaction') ? 'selected' : ''; ?>><?php echo ucwords(str_replace('_', " ", "wallet_transaction")); ?></option>
                                            <option value='ticket_status' <?= (isset($fetched_data[0]['type']) &&  $fetched_data[0]['type'] == 'ticket_status') ? 'selected' : ''; ?>><?php echo ucwords(str_replace('_', " ", "ticket_status")); ?></option>
                                            <option value='ticket_message' <?= (isset($fetched_data[0]['type']) &&  $fetched_data[0]['type'] == 'ticket_message') ? 'selected' : ''; ?>><?php echo ucwords(str_replace('_', " ", "ticket_message")); ?></option>
                                            <option value='bank_transfer_receipt_status' <?= (isset($fetched_data[0]['type']) &&  $fetched_data[0]['type'] == 'bank_transfer_receipt_status') ? 'selected' : ''; ?>><?php echo ucwords(str_replace('_', " ", "bank_transfer_receipt_status")); ?></option>
                                            <option value='bank_transfer_proof' <?= (isset($fetched_data[0]['type']) &&  $fetched_data[0]['type'] == 'bank_transfer_proof') ? 'selected' : ''; ?>><?php echo ucwords(str_replace('_', " ", "bank_transfer_proof")); ?></option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="title" class="col-sm-2 col-form-label">Title <span class='text-danger text-sm'>*</span></label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="title" placeholder="Title Name" name="title" value="<?= isset($fetched_data[0]['title']) ?  $fetched_data[0]['title'] : "" ?>">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="message" class="col-sm-2 col-form-label">Message<span class='text-danger text-sm'>*</span></label>
                                    <div class="col-sm-10">
                                        <textarea name="message" class="form-control" placeholder="Place some text here"><?= (isset($fetched_data[0]['id'])) ? $fetched_data[0]['message'] : ''; ?></textarea>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <button type="reset" class="btn btn-warning">Reset</button>
                                    <button type="submit" class="btn btn-success" id="submit_btn"><?= (isset($fetched_data[0]['id'])) ? 'Update Custom message ' : 'Add Custom message ' ?></button>
                                </div>
                            </div>
                            <div class="d-flex justify-content-center">
                                <div class="form-group" id="error_box">
                                </div>
                            </div>
                        </form>
                    </div>
                    <!--/.card-->
                </div>
                <div class="modal fade edit-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLongTitle">Custom message </h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12 main-content">
                    <div class="card content-area p-4">
                        <div class="card-head">
                            <h4 class="card-title text-center">Custom message List</h4>
                        </div>
                        <div class="card-innr">
                            <div class="gaps-1-5x"></div>
                            <table class='table-striped' data-toggle="table" data-url="<?= base_url('admin/custom_notification/view_notification') ?>" data-click-to-select="true" data-side-pagination="server" data-pagination="true" data-page-list="[5, 10, 20, 50, 100, 200]" data-search="true" data-show-columns="true" data-show-refresh="true" data-trim-on-search="false" data-sort-name="id" data-sort-order="asc" data-mobile-responsive="true" data-toolbar="" data-show-export="true" data-maintain-selected="true" data-export-types='["txt","excel"]' data-export-options='{
                        "fileName": "custom-notifications-list",
                        "ignoreColumn": ["operate"] 
                        }' data-query-params="queryParams">
                                <thead>
                                    <tr>
                                        <th data-field="id" data-sortable="true">ID</th>
                                        <th data-field="title" data-sortable="false">Title</th>
                                        <th data-field="type" data-sortable="true">Type</th>
                                        <th data-field="message" data-sortable="true">Message</th>
                                        <th data-field="operate" data-sortable="true">Action</th>
                                    </tr>
                                </thead>
                            </table>
                        </div><!-- .card-innr -->
                    </div><!-- .card -->
                </div>
            </div>
            <!-- /.row -->
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
</div>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <!-- Main content -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h4>Custom message </h4>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= base_url('admin/home') ?>">Home</a></li>
                        <li class="breadcrumb-item active">Custom message </li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-info">
                        <!-- form start -->
                        <form class="form-horizontal form-submit-event" action="<?= base_url('admin/custom_notification/add_notification'); ?>" method="POST" id="add_product_form" enctype="multipart/form-data">
                            <?php
                            if (isset($fetched_data[0]['id'])) {
                            ?>
                                <input type="hidden" id="edit_custom_notification" name="edit_custom_notification" value="<?= @$fetched_data[0]['id'] ?>">
                                <input type="hidden" id="update_id" name="update_id" value="1">
                            <?php
                            }
                            ?>
                            <div class="card-body">
                                <div class="form-group row">
                                    <label for="type" class="col-sm-2 col-form-label">Type <span class='text-danger text-sm'>*</span></label>
                                    <div class="col-sm-10">
                                        <select class='form-control' name='type' require="">
                                            <option value=" ">Select Type</option>
                                            <option value='place_order' <?= (isset($fetched_data[0]['type']) &&  $fetched_data[0]['type'] == 'place_order') ? 'selected' : ''; ?>><?php echo ucwords(str_replace('_', " ", "place_order")); ?></option>
                                            <option value='settle_cashback_discount' <?= (isset($fetched_data[0]['type']) &&  $fetched_data[0]['type'] == 'settle_cashback_discount') ? 'selected' : ''; ?>><?php echo ucwords(str_replace('_', " ", "settle_cashback_discount")); ?></option>
                                            <option value='settle_seller_commission' <?= (isset($fetched_data[0]['type']) &&  $fetched_data[0]['type'] == 'settle_seller_commission') ? 'selected' : ''; ?>><?php echo ucwords(str_replace('_', " ", "settle_seller_commission")); ?></option>
                                            <option value='customer_order_update' <?= (isset($fetched_data[0]['type']) &&  $fetched_data[0]['type'] == 'customer_order_update') ? 'selected' : ''; ?>><?php echo ucwords(str_replace('_', " ", "customer_order_update")); ?></option>
                                            <option value='customer_order_received' <?= (isset($fetched_data[0]['type']) &&  $fetched_data[0]['type'] == 'customer_order_received') ? 'selected' : ''; ?>><?php echo ucwords(str_replace('_', " ", "customer_order_received")); ?></option>
                                            <option value='customer_order_processed' <?= (isset($fetched_data[0]['type']) &&  $fetched_data[0]['type'] == 'customer_order_processed') ? 'selected' : ''; ?>><?php echo ucwords(str_replace('_', " ", "customer_order_processed")); ?></option>
                                            <option value='customer_order_shipped' <?= (isset($fetched_data[0]['type']) &&  $fetched_data[0]['type'] == 'customer_order_shipped') ? 'selected' : ''; ?>><?php echo ucwords(str_replace('_', " ", "customer_order_shipped")); ?></option>
                                            <option value='customer_order_delivered' <?= (isset($fetched_data[0]['type']) &&  $fetched_data[0]['type'] == 'customer_order_delivered') ? 'selected' : ''; ?>><?php echo ucwords(str_replace('_', " ", "customer_order_delivered")); ?></option>
                                            <option value='customer_order_cancelled' <?= (isset($fetched_data[0]['type']) &&  $fetched_data[0]['type'] == 'customer_order_cancelled') ? 'selected' : ''; ?>><?php echo ucwords(str_replace('_', " ", "customer_order_cancelled")); ?></option>
                                            <option value='customer_order_returned' <?= (isset($fetched_data[0]['type']) &&  $fetched_data[0]['type'] == 'customer_order_returned') ? 'selected' : ''; ?>><?php echo ucwords(str_replace('_', " ", "customer_order_returned")); ?></option>
                                            <option value='delivery_boy_order_deliver' <?= (isset($fetched_data[0]['type']) &&  $fetched_data[0]['type'] == 'delivery_boy_order_deliver') ? 'selected' : ''; ?>><?php echo ucwords(str_replace('_', " ", "delivery_boy_order_deliver")); ?></option>
                                            <option value='wallet_transaction' <?= (isset($fetched_data[0]['type']) &&  $fetched_data[0]['type'] == 'wallet_transaction') ? 'selected' : ''; ?>><?php echo ucwords(str_replace('_', " ", "wallet_transaction")); ?></option>
                                            <option value='ticket_status' <?= (isset($fetched_data[0]['type']) &&  $fetched_data[0]['type'] == 'ticket_status') ? 'selected' : ''; ?>><?php echo ucwords(str_replace('_', " ", "ticket_status")); ?></option>
                                            <option value='ticket_message' <?= (isset($fetched_data[0]['type']) &&  $fetched_data[0]['type'] == 'ticket_message') ? 'selected' : ''; ?>><?php echo ucwords(str_replace('_', " ", "ticket_message")); ?></option>
                                            <option value='bank_transfer_receipt_status' <?= (isset($fetched_data[0]['type']) &&  $fetched_data[0]['type'] == 'bank_transfer_receipt_status') ? 'selected' : ''; ?>><?php echo ucwords(str_replace('_', " ", "bank_transfer_receipt_status")); ?></option>
                                            <option value='bank_transfer_proof' <?= (isset($fetched_data[0]['type']) &&  $fetched_data[0]['type'] == 'bank_transfer_proof') ? 'selected' : ''; ?>><?php echo ucwords(str_replace('_', " ", "bank_transfer_proof")); ?></option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="title" class="col-sm-2 col-form-label">Title <span class='text-danger text-sm'>*</span></label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="title" placeholder="Title Name" name="title" value="<?= isset($fetched_data[0]['title']) ?  $fetched_data[0]['title'] : "" ?>">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="message" class="col-sm-2 col-form-label">Message<span class='text-danger text-sm'>*</span></label>
                                    <div class="col-sm-10">
                                        <textarea name="message" class="form-control" placeholder="Place some text here"><?= (isset($fetched_data[0]['id'])) ? $fetched_data[0]['message'] : ''; ?></textarea>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <button type="reset" class="btn btn-warning">Reset</button>
                                    <button type="submit" class="btn btn-success" id="submit_btn"><?= (isset($fetched_data[0]['id'])) ? 'Update Custom message ' : 'Add Custom message ' ?></button>
                                </div>
                            </div>
                            <div class="d-flex justify-content-center">
                                <div class="form-group" id="error_box">
                                </div>
                            </div>
                        </form>
                    </div>
                    <!--/.card-->
                </div>
                <div class="modal fade edit-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLongTitle">Custom message </h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12 main-content">
                    <div class="card content-area p-4">
                        <div class="card-head">
                            <h4 class="card-title text-center">Custom message List</h4>
                        </div>
                        <div class="card-innr">
                            <div class="gaps-1-5x"></div>
                            <table class='table-striped' data-toggle="table" data-url="<?= base_url('admin/custom_notification/view_notification') ?>" data-click-to-select="true" data-side-pagination="server" data-pagination="true" data-page-list="[5, 10, 20, 50, 100, 200]" data-search="true" data-show-columns="true" data-show-refresh="true" data-trim-on-search="false" data-sort-name="id" data-sort-order="asc" data-mobile-responsive="true" data-toolbar="" data-show-export="true" data-maintain-selected="true" data-export-types='["txt","excel"]' data-export-options='{
                        "fileName": "custom-notifications-list",
                        "ignoreColumn": ["operate"] 
                        }' data-query-params="queryParams">
                                <thead>
                                    <tr>
                                        <th data-field="id" data-sortable="true">ID</th>
                                        <th data-field="title" data-sortable="false">Title</th>
                                        <th data-field="type" data-sortable="true">Type</th>
                                        <th data-field="message" data-sortable="true">Message</th>
                                        <th data-field="operate" data-sortable="true">Action</th>
                                    </tr>
                                </thead>
                            </table>
                        </div><!-- .card-innr -->
                    </div><!-- .card -->
                </div>
            </div>
            <!-- /.row -->
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
</div>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <!-- Main content -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h4>Custom message </h4>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= base_url('admin/home') ?>">Home</a></li>
                        <li class="breadcrumb-item active">Custom message </li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-info">
                        <!-- form start -->
                        <form class="form-horizontal form-submit-event" action="<?= base_url('admin/custom_notification/add_notification'); ?>" method="POST" id="add_product_form" enctype="multipart/form-data">
                            <?php
                            if (isset($fetched_data[0]['id'])) {
                            ?>
                                <input type="hidden" id="edit_custom_notification" name="edit_custom_notification" value="<?= @$fetched_data[0]['id'] ?>">
                                <input type="hidden" id="update_id" name="update_id" value="1">
                            <?php
                            }
                            ?>
                            <div class="card-body">
                                <div class="form-group row">
                                    <label for="type" class="col-sm-2 col-form-label">Type <span class='text-danger text-sm'>*</span></label>
                                    <div class="col-sm-10">
                                        <select class='form-control' name='type' require="">
                                            <option value=" ">Select Type</option>
                                            <option value='place_order' <?= (isset($fetched_data[0]['type']) &&  $fetched_data[0]['type'] == 'place_order') ? 'selected' : ''; ?>><?php echo ucwords(str_replace('_', " ", "place_order")); ?></option>
                                            <option value='settle_cashback_discount' <?= (isset($fetched_data[0]['type']) &&  $fetched_data[0]['type'] == 'settle_cashback_discount') ? 'selected' : ''; ?>><?php echo ucwords(str_replace('_', " ", "settle_cashback_discount")); ?></option>
                                            <option value='settle_seller_commission' <?= (isset($fetched_data[0]['type']) &&  $fetched_data[0]['type'] == 'settle_seller_commission') ? 'selected' : ''; ?>><?php echo ucwords(str_replace('_', " ", "settle_seller_commission")); ?></option>
                                            <option value='customer_order_update' <?= (isset($fetched_data[0]['type']) &&  $fetched_data[0]['type'] == 'customer_order_update') ? 'selected' : ''; ?>><?php echo ucwords(str_replace('_', " ", "customer_order_update")); ?></option>
                                            <option value='customer_order_received' <?= (isset($fetched_data[0]['type']) &&  $fetched_data[0]['type'] == 'customer_order_received') ? 'selected' : ''; ?>><?php echo ucwords(str_replace('_', " ", "customer_order_received")); ?></option>
                                            <option value='customer_order_processed' <?= (isset($fetched_data[0]['type']) &&  $fetched_data[0]['type'] == 'customer_order_processed') ? 'selected' : ''; ?>><?php echo ucwords(str_replace('_', " ", "customer_order_processed")); ?></option>
                                            <option value='customer_order_shipped' <?= (isset($fetched_data[0]['type']) &&  $fetched_data[0]['type'] == 'customer_order_shipped') ? 'selected' : ''; ?>><?php echo ucwords(str_replace('_', " ", "customer_order_shipped")); ?></option>
                                            <option value='customer_order_delivered' <?= (isset($fetched_data[0]['type']) &&  $fetched_data[0]['type'] == 'customer_order_delivered') ? 'selected' : ''; ?>><?php echo ucwords(str_replace('_', " ", "customer_order_delivered")); ?></option>
                                            <option value='customer_order_cancelled' <?= (isset($fetched_data[0]['type']) &&  $fetched_data[0]['type'] == 'customer_order_cancelled') ? 'selected' : ''; ?>><?php echo ucwords(str_replace('_', " ", "customer_order_cancelled")); ?></option>
                                            <option value='customer_order_returned' <?= (isset($fetched_data[0]['type']) &&  $fetched_data[0]['type'] == 'customer_order_returned') ? 'selected' : ''; ?>><?php echo ucwords(str_replace('_', " ", "customer_order_returned")); ?></option>
                                            <option value='delivery_boy_order_deliver' <?= (isset($fetched_data[0]['type']) &&  $fetched_data[0]['type'] == 'delivery_boy_order_deliver') ? 'selected' : ''; ?>><?php echo ucwords(str_replace('_', " ", "delivery_boy_order_deliver")); ?></option>
                                            <option value='wallet_transaction' <?= (isset($fetched_data[0]['type']) &&  $fetched_data[0]['type'] == 'wallet_transaction') ? 'selected' : ''; ?>><?php echo ucwords(str_replace('_', " ", "wallet_transaction")); ?></option>
                                            <option value='ticket_status' <?= (isset($fetched_data[0]['type']) &&  $fetched_data[0]['type'] == 'ticket_status') ? 'selected' : ''; ?>><?php echo ucwords(str_replace('_', " ", "ticket_status")); ?></option>
                                            <option value='ticket_message' <?= (isset($fetched_data[0]['type']) &&  $fetched_data[0]['type'] == 'ticket_message') ? 'selected' : ''; ?>><?php echo ucwords(str_replace('_', " ", "ticket_message")); ?></option>
                                            <option value='bank_transfer_receipt_status' <?= (isset($fetched_data[0]['type']) &&  $fetched_data[0]['type'] == 'bank_transfer_receipt_status') ? 'selected' : ''; ?>><?php echo ucwords(str_replace('_', " ", "bank_transfer_receipt_status")); ?></option>
                                            <option value='bank_transfer_proof' <?= (isset($fetched_data[0]['type']) &&  $fetched_data[0]['type'] == 'bank_transfer_proof') ? 'selected' : ''; ?>><?php echo ucwords(str_replace('_', " ", "bank_transfer_proof")); ?></option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="title" class="col-sm-2 col-form-label">Title <span class='text-danger text-sm'>*</span></label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="title" placeholder="Title Name" name="title" value="<?= isset($fetched_data[0]['title']) ?  $fetched_data[0]['title'] : "" ?>">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="message" class="col-sm-2 col-form-label">Message<span class='text-danger text-sm'>*</span></label>
                                    <div class="col-sm-10">
                                        <textarea name="message" class="form-control" placeholder="Place some text here"><?= (isset($fetched_data[0]['id'])) ? $fetched_data[0]['message'] : ''; ?></textarea>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <button type="reset" class="btn btn-warning">Reset</button>
                                    <button type="submit" class="btn btn-success" id="submit_btn"><?= (isset($fetched_data[0]['id'])) ? 'Update Custom message ' : 'Add Custom message ' ?></button>
                                </div>
                            </div>
                            <div class="d-flex justify-content-center">
                                <div class="form-group" id="error_box">
                                </div>
                            </div>
                        </form>
                    </div>
                    <!--/.card-->
                </div>
                <div class="modal fade edit-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLongTitle">Custom message </h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12 main-content">
                    <div class="card content-area p-4">
                        <div class="card-head">
                            <h4 class="card-title text-center">Custom message List</h4>
                        </div>
                        <div class="card-innr">
                            <div class="gaps-1-5x"></div>
                            <table class='table-striped' data-toggle="table" data-url="<?= base_url('admin/custom_notification/view_notification') ?>" data-click-to-select="true" data-side-pagination="server" data-pagination="true" data-page-list="[5, 10, 20, 50, 100, 200]" data-search="true" data-show-columns="true" data-show-refresh="true" data-trim-on-search="false" data-sort-name="id" data-sort-order="asc" data-mobile-responsive="true" data-toolbar="" data-show-export="true" data-maintain-selected="true" data-export-types='["txt","excel"]' data-export-options='{
                        "fileName": "custom-notifications-list",
                        "ignoreColumn": ["operate"] 
                        }' data-query-params="queryParams">
                                <thead>
                                    <tr>
                                        <th data-field="id" data-sortable="true">ID</th>
                                        <th data-field="title" data-sortable="false">Title</th>
                                        <th data-field="type" data-sortable="true">Type</th>
                                        <th data-field="message" data-sortable="true">Message</th>
                                        <th data-field="operate" data-sortable="true">Action</th>
                                    </tr>
                                </thead>
                            </table>
                        </div><!-- .card-innr -->
                    </div><!-- .card -->
                </div>
            </div>
            <!-- /.row -->
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
</div>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <!-- Main content -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h4>Custom message </h4>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= base_url('admin/home') ?>">Home</a></li>
                        <li class="breadcrumb-item active">Custom message </li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-info">
                        <!-- form start -->
                        <form class="form-horizontal form-submit-event" action="<?= base_url('admin/custom_notification/add_notification'); ?>" method="POST" id="add_product_form" enctype="multipart/form-data">
                            <?php
                            if (isset($fetched_data[0]['id'])) {
                            ?>
                                <input type="hidden" id="edit_custom_notification" name="edit_custom_notification" value="<?= @$fetched_data[0]['id'] ?>">
                                <input type="hidden" id="update_id" name="update_id" value="1">
                            <?php
                            }
                            ?>
                            <div class="card-body">
                                <div class="form-group row">
                                    <label for="type" class="col-sm-2 col-form-label">Type <span class='text-danger text-sm'>*</span></label>
                                    <div class="col-sm-10">
                                        <select class='form-control' name='type' require="">
                                            <option value=" ">Select Type</option>
                                            <option value='place_order' <?= (isset($fetched_data[0]['type']) &&  $fetched_data[0]['type'] == 'place_order') ? 'selected' : ''; ?>><?php echo ucwords(str_replace('_', " ", "place_order")); ?></option>
                                            <option value='settle_cashback_discount' <?= (isset($fetched_data[0]['type']) &&  $fetched_data[0]['type'] == 'settle_cashback_discount') ? 'selected' : ''; ?>><?php echo ucwords(str_replace('_', " ", "settle_cashback_discount")); ?></option>
                                            <option value='settle_seller_commission' <?= (isset($fetched_data[0]['type']) &&  $fetched_data[0]['type'] == 'settle_seller_commission') ? 'selected' : ''; ?>><?php echo ucwords(str_replace('_', " ", "settle_seller_commission")); ?></option>
                                            <option value='customer_order_update' <?= (isset($fetched_data[0]['type']) &&  $fetched_data[0]['type'] == 'customer_order_update') ? 'selected' : ''; ?>><?php echo ucwords(str_replace('_', " ", "customer_order_update")); ?></option>
                                            <option value='customer_order_received' <?= (isset($fetched_data[0]['type']) &&  $fetched_data[0]['type'] == 'customer_order_received') ? 'selected' : ''; ?>><?php echo ucwords(str_replace('_', " ", "customer_order_received")); ?></option>
                                            <option value='customer_order_processed' <?= (isset($fetched_data[0]['type']) &&  $fetched_data[0]['type'] == 'customer_order_processed') ? 'selected' : ''; ?>><?php echo ucwords(str_replace('_', " ", "customer_order_processed")); ?></option>
                                            <option value='customer_order_shipped' <?= (isset($fetched_data[0]['type']) &&  $fetched_data[0]['type'] == 'customer_order_shipped') ? 'selected' : ''; ?>><?php echo ucwords(str_replace('_', " ", "customer_order_shipped")); ?></option>
                                            <option value='customer_order_delivered' <?= (isset($fetched_data[0]['type']) &&  $fetched_data[0]['type'] == 'customer_order_delivered') ? 'selected' : ''; ?>><?php echo ucwords(str_replace('_', " ", "customer_order_delivered")); ?></option>
                                            <option value='customer_order_cancelled' <?= (isset($fetched_data[0]['type']) &&  $fetched_data[0]['type'] == 'customer_order_cancelled') ? 'selected' : ''; ?>><?php echo ucwords(str_replace('_', " ", "customer_order_cancelled")); ?></option>
                                            <option value='customer_order_returned' <?= (isset($fetched_data[0]['type']) &&  $fetched_data[0]['type'] == 'customer_order_returned') ? 'selected' : ''; ?>><?php echo ucwords(str_replace('_', " ", "customer_order_returned")); ?></option>
                                            <option value='delivery_boy_order_deliver' <?= (isset($fetched_data[0]['type']) &&  $fetched_data[0]['type'] == 'delivery_boy_order_deliver') ? 'selected' : ''; ?>><?php echo ucwords(str_replace('_', " ", "delivery_boy_order_deliver")); ?></option>
                                            <option value='wallet_transaction' <?= (isset($fetched_data[0]['type']) &&  $fetched_data[0]['type'] == 'wallet_transaction') ? 'selected' : ''; ?>><?php echo ucwords(str_replace('_', " ", "wallet_transaction")); ?></option>
                                            <option value='ticket_status' <?= (isset($fetched_data[0]['type']) &&  $fetched_data[0]['type'] == 'ticket_status') ? 'selected' : ''; ?>><?php echo ucwords(str_replace('_', " ", "ticket_status")); ?></option>
                                            <option value='ticket_message' <?= (isset($fetched_data[0]['type']) &&  $fetched_data[0]['type'] == 'ticket_message') ? 'selected' : ''; ?>><?php echo ucwords(str_replace('_', " ", "ticket_message")); ?></option>
                                            <option value='bank_transfer_receipt_status' <?= (isset($fetched_data[0]['type']) &&  $fetched_data[0]['type'] == 'bank_transfer_receipt_status') ? 'selected' : ''; ?>><?php echo ucwords(str_replace('_', " ", "bank_transfer_receipt_status")); ?></option>
                                            <option value='bank_transfer_proof' <?= (isset($fetched_data[0]['type']) &&  $fetched_data[0]['type'] == 'bank_transfer_proof') ? 'selected' : ''; ?>><?php echo ucwords(str_replace('_', " ", "bank_transfer_proof")); ?></option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="title" class="col-sm-2 col-form-label">Title <span class='text-danger text-sm'>*</span></label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="title" placeholder="Title Name" name="title" value="<?= isset($fetched_data[0]['title']) ?  $fetched_data[0]['title'] : "" ?>">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="message" class="col-sm-2 col-form-label">Message<span class='text-danger text-sm'>*</span></label>
                                    <div class="col-sm-10">
                                        <textarea name="message" class="form-control" placeholder="Place some text here"><?= (isset($fetched_data[0]['id'])) ? $fetched_data[0]['message'] : ''; ?></textarea>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <button type="reset" class="btn btn-warning">Reset</button>
                                    <button type="submit" class="btn btn-success" id="submit_btn"><?= (isset($fetched_data[0]['id'])) ? 'Update Custom message ' : 'Add Custom message ' ?></button>
                                </div>
                            </div>
                            <div class="d-flex justify-content-center">
                                <div class="form-group" id="error_box">
                                </div>
                            </div>
                        </form>
                    </div>
                    <!--/.card-->
                </div>
                <div class="modal fade edit-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLongTitle">Custom message </h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12 main-content">
                    <div class="card content-area p-4">
                        <div class="card-head">
                            <h4 class="card-title text-center">Custom message List</h4>
                        </div>
                        <div class="card-innr">
                            <div class="gaps-1-5x"></div>
                            <table class='table-striped' data-toggle="table" data-url="<?= base_url('admin/custom_notification/view_notification') ?>" data-click-to-select="true" data-side-pagination="server" data-pagination="true" data-page-list="[5, 10, 20, 50, 100, 200]" data-search="true" data-show-columns="true" data-show-refresh="true" data-trim-on-search="false" data-sort-name="id" data-sort-order="asc" data-mobile-responsive="true" data-toolbar="" data-show-export="true" data-maintain-selected="true" data-export-types='["txt","excel"]' data-export-options='{
                        "fileName": "custom-notifications-list",
                        "ignoreColumn": ["operate"] 
                        }' data-query-params="queryParams">
                                <thead>
                                    <tr>
                                        <th data-field="id" data-sortable="true">ID</th>
                                        <th data-field="title" data-sortable="false">Title</th>
                                        <th data-field="type" data-sortable="true">Type</th>
                                        <th data-field="message" data-sortable="true">Message</th>
                                        <th data-field="operate" data-sortable="true">Action</th>
                                    </tr>
                                </thead>
                            </table>
                        </div><!-- .card-innr -->
                    </div><!-- .card -->
                </div>
            </div>
            <!-- /.row -->
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
</div>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <!-- Main content -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h4>Custom message </h4>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= base_url('admin/home') ?>">Home</a></li>
                        <li class="breadcrumb-item active">Custom message </li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-info">
                        <!-- form start -->
                        <form class="form-horizontal form-submit-event" action="<?= base_url('admin/custom_notification/add_notification'); ?>" method="POST" id="add_product_form" enctype="multipart/form-data">
                            <?php
                            if (isset($fetched_data[0]['id'])) {
                            ?>
                                <input type="hidden" id="edit_custom_notification" name="edit_custom_notification" value="<?= @$fetched_data[0]['id'] ?>">
                                <input type="hidden" id="update_id" name="update_id" value="1">
                            <?php
                            }
                            ?>
                            <div class="card-body">
                                <div class="form-group row">
                                    <label for="type" class="col-sm-2 col-form-label">Type <span class='text-danger text-sm'>*</span></label>
                                    <div class="col-sm-10">
                                        <select class='form-control' name='type' require="">
                                            <option value=" ">Select Type</option>
                                            <option value='place_order' <?= (isset($fetched_data[0]['type']) &&  $fetched_data[0]['type'] == 'place_order') ? 'selected' : ''; ?>><?php echo ucwords(str_replace('_', " ", "place_order")); ?></option>
                                            <option value='settle_cashback_discount' <?= (isset($fetched_data[0]['type']) &&  $fetched_data[0]['type'] == 'settle_cashback_discount') ? 'selected' : ''; ?>><?php echo ucwords(str_replace('_', " ", "settle_cashback_discount")); ?></option>
                                            <option value='settle_seller_commission' <?= (isset($fetched_data[0]['type']) &&  $fetched_data[0]['type'] == 'settle_seller_commission') ? 'selected' : ''; ?>><?php echo ucwords(str_replace('_', " ", "settle_seller_commission")); ?></option>
                                            <option value='customer_order_update' <?= (isset($fetched_data[0]['type']) &&  $fetched_data[0]['type'] == 'customer_order_update') ? 'selected' : ''; ?>><?php echo ucwords(str_replace('_', " ", "customer_order_update")); ?></option>
                                            <option value='customer_order_received' <?= (isset($fetched_data[0]['type']) &&  $fetched_data[0]['type'] == 'customer_order_received') ? 'selected' : ''; ?>><?php echo ucwords(str_replace('_', " ", "customer_order_received")); ?></option>
                                            <option value='customer_order_processed' <?= (isset($fetched_data[0]['type']) &&  $fetched_data[0]['type'] == 'customer_order_processed') ? 'selected' : ''; ?>><?php echo ucwords(str_replace('_', " ", "customer_order_processed")); ?></option>
                                            <option value='customer_order_shipped' <?= (isset($fetched_data[0]['type']) &&  $fetched_data[0]['type'] == 'customer_order_shipped') ? 'selected' : ''; ?>><?php echo ucwords(str_replace('_', " ", "customer_order_shipped")); ?></option>
                                            <option value='customer_order_delivered' <?= (isset($fetched_data[0]['type']) &&  $fetched_data[0]['type'] == 'customer_order_delivered') ? 'selected' : ''; ?>><?php echo ucwords(str_replace('_', " ", "customer_order_delivered")); ?></option>
                                            <option value='customer_order_cancelled' <?= (isset($fetched_data[0]['type']) &&  $fetched_data[0]['type'] == 'customer_order_cancelled') ? 'selected' : ''; ?>><?php echo ucwords(str_replace('_', " ", "customer_order_cancelled")); ?></option>
                                            <option value='customer_order_returned' <?= (isset($fetched_data[0]['type']) &&  $fetched_data[0]['type'] == 'customer_order_returned') ? 'selected' : ''; ?>><?php echo ucwords(str_replace('_', " ", "customer_order_returned")); ?></option>
                                            <option value='delivery_boy_order_deliver' <?= (isset($fetched_data[0]['type']) &&  $fetched_data[0]['type'] == 'delivery_boy_order_deliver') ? 'selected' : ''; ?>><?php echo ucwords(str_replace('_', " ", "delivery_boy_order_deliver")); ?></option>
                                            <option value='wallet_transaction' <?= (isset($fetched_data[0]['type']) &&  $fetched_data[0]['type'] == 'wallet_transaction') ? 'selected' : ''; ?>><?php echo ucwords(str_replace('_', " ", "wallet_transaction")); ?></option>
                                            <option value='ticket_status' <?= (isset($fetched_data[0]['type']) &&  $fetched_data[0]['type'] == 'ticket_status') ? 'selected' : ''; ?>><?php echo ucwords(str_replace('_', " ", "ticket_status")); ?></option>
                                            <option value='ticket_message' <?= (isset($fetched_data[0]['type']) &&  $fetched_data[0]['type'] == 'ticket_message') ? 'selected' : ''; ?>><?php echo ucwords(str_replace('_', " ", "ticket_message")); ?></option>
                                            <option value='bank_transfer_receipt_status' <?= (isset($fetched_data[0]['type']) &&  $fetched_data[0]['type'] == 'bank_transfer_receipt_status') ? 'selected' : ''; ?>><?php echo ucwords(str_replace('_', " ", "bank_transfer_receipt_status")); ?></option>
                                            <option value='bank_transfer_proof' <?= (isset($fetched_data[0]['type']) &&  $fetched_data[0]['type'] == 'bank_transfer_proof') ? 'selected' : ''; ?>><?php echo ucwords(str_replace('_', " ", "bank_transfer_proof")); ?></option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="title" class="col-sm-2 col-form-label">Title <span class='text-danger text-sm'>*</span></label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="title" placeholder="Title Name" name="title" value="<?= isset($fetched_data[0]['title']) ?  $fetched_data[0]['title'] : "" ?>">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="message" class="col-sm-2 col-form-label">Message<span class='text-danger text-sm'>*</span></label>
                                    <div class="col-sm-10">
                                        <textarea name="message" class="form-control" placeholder="Place some text here"><?= (isset($fetched_data[0]['id'])) ? $fetched_data[0]['message'] : ''; ?></textarea>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <button type="reset" class="btn btn-warning">Reset</button>
                                    <button type="submit" class="btn btn-success" id="submit_btn"><?= (isset($fetched_data[0]['id'])) ? 'Update Custom message ' : 'Add Custom message ' ?></button>
                                </div>
                            </div>
                            <div class="d-flex justify-content-center">
                                <div class="form-group" id="error_box">
                                </div>
                            </div>
                        </form>
                    </div>
                    <!--/.card-->
                </div>
                <div class="modal fade edit-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLongTitle">Custom message </h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12 main-content">
                    <div class="card content-area p-4">
                        <div class="card-head">
                            <h4 class="card-title text-center">Custom message List</h4>
                        </div>
                        <div class="card-innr">
                            <div class="gaps-1-5x"></div>
                            <table class='table-striped' data-toggle="table" data-url="<?= base_url('admin/custom_notification/view_notification') ?>" data-click-to-select="true" data-side-pagination="server" data-pagination="true" data-page-list="[5, 10, 20, 50, 100, 200]" data-search="true" data-show-columns="true" data-show-refresh="true" data-trim-on-search="false" data-sort-name="id" data-sort-order="asc" data-mobile-responsive="true" data-toolbar="" data-show-export="true" data-maintain-selected="true" data-export-types='["txt","excel"]' data-export-options='{
                        "fileName": "custom-notifications-list",
                        "ignoreColumn": ["operate"] 
                        }' data-query-params="queryParams">
                                <thead>
                                    <tr>
                                        <th data-field="id" data-sortable="true">ID</th>
                                        <th data-field="title" data-sortable="false">Title</th>
                                        <th data-field="type" data-sortable="true">Type</th>
                                        <th data-field="message" data-sortable="true">Message</th>
                                        <th data-field="operate" data-sortable="true">Action</th>
                                    </tr>
                                </thead>
                            </table>
                        </div><!-- .card-innr -->
                    </div><!-- .card -->
                </div>
            </div>
            <!-- /.row -->
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
</div>