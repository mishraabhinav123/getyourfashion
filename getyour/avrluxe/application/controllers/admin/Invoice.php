<?php
defined('BASEPATH') or exit('No direct script access allowed');


class Invoice extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->helper(['url', 'language', 'timezone_helper']);
        $this->load->model(['Invoice_model', 'Order_model']);
        $this->session->set_flashdata('authorize_flag', "");
    }

    public function index()
    {
        if ($this->ion_auth->logged_in() && $this->ion_auth->is_admin()) {
            $this->data['main_page'] = VIEW . 'invoice';
            $settings = get_settings('system_settings', true);
            $this->data['title'] = 'Invoice Management |' . $settings['app_name'];
            $this->data['meta_description'] = 'Ekart | Invoice Management';
            if (isset($_GET['edit_id']) && !empty($_GET['edit_id'])) {
                $res = $this->Order_model->get_order_details(['o.id' => $_GET['edit_id']], true);
                if (!empty($res)) {
                    $items = [];
                    $promo_code = [];
                    if (!empty($res[0]['promo_code'])) {
                        $promo_code = fetch_details('promo_codes', ['promo_code' => trim($res[0]['promo_code'])]);
                    }
                    foreach ($res as $row) {
                        //print_R($row);
                        $temp['product_id'] = $row['product_id'];
                        $temp['product_variant_id'] = $row['product_variant_id'];
                        $temp['pname'] = $row['pname'];
                        $temp['type'] = $row['type'];
                        $temp['quantity'] = $row['quantity'];
                        $temp['discounted_price'] = $row['discounted_price'];
                        $temp['tax_percent'] = $row['tax_percent'];
                        $temp['tax_amount'] = $row['tax_amount'];
                        $temp['price'] = $row['price'];
                        $temp['delivery_boy'] = $row['delivery_boy'];
                        $temp['active_status'] = $row['oi_active_status'];
                        array_push($items, $temp);
                    }
                    $this->data['order_detls'] = $res;
                    $this->data['items'] = $items;
                    $this->data['promo_code'] = $promo_code;
                    // $this->data['invoice'] = fetch_details(['order_id'=>$res[0]['order_id']],'invoice');
                    $this->data['settings'] = get_settings('system_settings', true);
                    $this->load->view('admin/template', $this->data);
                } else {
                    redirect('admin/orders/', 'refresh');
                }
            } else {
                redirect('admin/orders/', 'refresh');
            }
        } else {
            redirect('admin/login', 'refresh');
        }
    }


    public function sales_invoice()
    {
        if ($this->ion_auth->logged_in() && $this->ion_auth->is_admin()) {
            $this->data['main_page'] = TABLES . 'sales-invoice';
            $settings = get_settings('system_settings', true);
            $this->data['title'] = 'Sales Invoice |' . $settings['app_name'];
            $this->data['meta_description'] = 'Ekart';
            $this->load->view('admin/template', $this->data);
        } else {
            redirect('admin/login', 'refresh');
        }
    }


    public function get_sales_list()
    {
        if ($this->ion_auth->logged_in() && $this->ion_auth->is_admin()) {
            return $this->Invoice_model->get_sales_list();
        } else {
            redirect('admin/login', 'refresh');
        }
    }

    //inventory report
    public function inventory_report()
    {
        if ($this->ion_auth->logged_in() && $this->ion_auth->is_admin()) {
            $this->data['main_page'] = TABLES . 'inventory-report';
            $settings = get_settings('system_settings', true);
            $this->data['title'] = 'Inventory Report |' . $settings['app_name'];
            $this->data['meta_description'] = 'Ekart';
            $this->load->view('admin/template', $this->data);
        } else {
            redirect('admin/login', 'refresh');
        }
    }


    public function get_inventory_list()
    {
        if ($this->ion_auth->logged_in() && $this->ion_auth->is_admin()) {
            return $this->Invoice_model->inventory_list();
        } else {
            redirect('admin/login', 'refresh');
        }
    }
}
