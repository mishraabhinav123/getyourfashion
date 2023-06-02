<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Test extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
    }
    public function  index()
    {
        $product_id = '35,15';
        $data =  is_exist_in_current_flash_sale($product_id);

        if ($data) {
            echo "123";
            return;
        } else {
            echo "13";
            return;
        }
    }
    public function webhook()
    {
        $request_body = file_get_contents('php://input');
        $event = json_decode($request_body, FALSE);
        log_message('error', 'Paystack Webhook --> ' . var_export($event, true));
        log_message('error', 'Paystack Webhook SERVER Variable --> ' . var_export($_SERVER, true));
    }
}
