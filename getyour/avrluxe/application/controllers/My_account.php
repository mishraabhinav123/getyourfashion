<?php
defined('BASEPATH') or exit('No direct script access allowed');

class My_account extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->library(['ion_auth', 'form_validation', 'pagination']);


        $this->load->helper(['url', 'language']);
        $this->load->model(['cart_model', 'category_model', 'address_model', 'order_model', 'Transaction_model', 'Promo_code_model', 'Customer_model']);
        $this->lang->load('auth');
        $this->data['is_logged_in'] = ($this->ion_auth->logged_in()) ? 1 : 0;
        $this->data['user'] = ($this->ion_auth->logged_in()) ? $this->ion_auth->user()->row() : array();
        $this->data['settings'] = get_settings('system_settings', true);
        $this->data['web_settings'] = get_settings('web_settings', true);
        $this->response['csrfName'] = $this->security->get_csrf_token_name();
        $this->response['csrfHash'] = $this->security->get_csrf_hash();
    }


    public function index()
    {
        if ($this->data['is_logged_in']) {
            $this->data['main_page'] = 'dashboard';
            $this->data['title'] = 'Dashboard | ' . $this->data['web_settings']['site_title'];
            $this->data['keywords'] = 'Dashboard, ' . $this->data['web_settings']['meta_keywords'];
            $this->data['description'] = 'Dashboard | ' . $this->data['web_settings']['meta_description'];
            $this->load->view('front-end/' . THEME . '/template', $this->data);
        } else {
            redirect(base_url(), 'refresh');
        }
    }

    public function profile()
    {
        if ($this->ion_auth->logged_in()) {
            $identity_column = $this->config->item('identity', 'ion_auth');
            $this->data['users'] = $this->ion_auth->user()->row();
            $this->data['identity_column'] = $identity_column;
            $this->data['main_page'] = 'profile';
            $this->data['title'] = 'Profile | ' . $this->data['web_settings']['site_title'];
            $this->data['keywords'] = $this->data['web_settings']['meta_keywords'];
            $this->data['description'] = $this->data['web_settings']['meta_description'];
            $this->load->view('front-end/' . THEME . '/template', $this->data);
        } else {
            redirect(base_url(), 'refresh');
        }
    }

    public function orders()
    {
        if ($this->ion_auth->logged_in()) {
            $this->data['main_page'] = 'orders';
            $this->data['title'] = 'Orders | ' . $this->data['web_settings']['site_title'];
            $this->data['keywords'] = 'Orders, ' . $this->data['web_settings']['meta_keywords'];
            $this->data['description'] = 'Orders | ' . $this->data['web_settings']['meta_description'];
            $total = fetch_orders(false, $this->data['user']->id, false, false, 1, NULL, NULL, NULL, NULL);
            $limit = 10;
            $config['base_url'] = base_url('my-account/orders');
            $config['total_rows'] = $total['total'];
            $config['per_page'] = $limit;
            $config['num_links'] = 2;
            $config['use_page_numbers'] = TRUE;
            $config['reuse_query_string'] = TRUE;
            $config['page_query_string'] = FALSE;
            $config['uri_segment'] = 3;
            $config['attributes'] = array('class' => 'page-link');

            $config['full_tag_open'] = '<ul class="pagination justify-content-center">';
            $config['full_tag_close'] = '</ul>';

            $config['first_tag_open'] = '<li class="page-item">';
            $config['first_link'] = 'First';
            $config['first_tag_close'] = '</li>';

            $config['last_tag_open'] = '<li class="page-item">';
            $config['last_link'] = 'Last';
            $config['last_tag_close'] = '</li>';

            $config['prev_tag_open'] = '<li class="page-item">';
            $config['prev_link'] = '<i class="fa fa-arrow-left"></i>';
            $config['prev_tag_close'] = '</li>';

            $config['next_tag_open'] = '<li class="page-item">';
            $config['next_link'] = '<i class="fa fa-arrow-right"></i>';
            $config['next_tag_close'] = '</li>';

            $config['cur_tag_open'] = '<li class="page-item active"><a class="page-link">';
            $config['cur_tag_close'] = '</a></li>';

            $config['num_tag_open'] = '<li class="page-item">';
            $config['num_tag_close'] = '</li>';

            $page_no = (empty($this->uri->segment(4))) ? 1 : $this->uri->segment(4);
            if (!is_numeric($page_no)) {
                redirect(base_url('my-account/orders'));
            }
            $offset = ($page_no - 1) * $limit;
            $this->pagination->initialize($config);
            $this->data['links'] =  $this->pagination->create_links();
            $this->data['orders'] = fetch_orders(false, $this->data['user']->id, false, false, $limit, $offset, 'date_added', 'DESC', NULL);
            $this->data['payment_methods'] = get_settings('payment_method', true);
            $this->load->view('front-end/' . THEME . '/template', $this->data);
        } else {
            redirect(base_url(), 'refresh');
        }
    }

    public function order_details()
    {
        if ($this->ion_auth->logged_in()) {
            $bank_transfer = array();
            $this->data['main_page'] = 'order-details';
            $this->data['title'] = 'Orders | ' . $this->data['web_settings']['site_title'];
            $this->data['keywords'] = 'Orders, ' . $this->data['web_settings']['meta_keywords'];
            $this->data['description'] = 'Orders | ' . $this->data['web_settings']['meta_description'];
            $order_id = $this->uri->segment(3);
            $order = fetch_orders($order_id, $this->data['user']->id, false, false, 1, NULL, NULL, NULL, NULL);

            if (!isset($order['order_data']) || empty($order['order_data'])) {
                redirect(base_url('my-account/orders'));
            }
            $this->data['order'] = $order['order_data'][0];
            if ($order['order_data'][0]['payment_method'] == "Bank Transfer") {
                $bank_transfer = fetch_details('order_bank_transfer', ['order_id' => $order['order_data'][0]['id']]);
            }
            $this->data['bank_transfer'] = $bank_transfer;
            $this->load->view('front-end/' . THEME . '/template', $this->data);
        } else {
            redirect(base_url(), 'refresh');
        }
    }

    public function order_invoice($order_id)
    {
        $order_item_id = $this->uri->segment(3);
        $user_id = $this->data['user']->id;
        $oreder_item_data = fetch_details('order_items', ['order_id' => $order_item_id], '*');
        // print_r($oreder_item_data[0]['user_id']);
        if ($this->ion_auth->logged_in() && $user_id == $oreder_item_data[0]['user_id']) {
            $this->data['main_page'] = VIEW . 'api-order-invoice';
            $settings = get_settings('system_settings', true);
            $this->data['title'] = 'Invoice Management |' . $settings['app_name'];
            $this->data['meta_description'] = 'Invoice Management | ' . $this->data['web_settings']['meta_description'];;
            if (isset($order_id) && !empty($order_id)) {
                $res = $this->order_model->get_order_details(['o.id' => $order_id], true);
                if (!empty($res)) {
                    $items = [];
                    $promo_code = [];
                    if (!empty($res[0]['promo_code'])) {
                        $promo_code = fetch_details('promo_codes', ['promo_code' => trim($res[0]['promo_code'])]);
                    }
                    foreach ($res as $row) {
                        $row = output_escaping($row);
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
                    $this->data['print_btn_enabled'] = true;
                    $this->data['settings'] = get_settings('system_settings', true);
                    $this->load->view('admin/invoice-template', $this->data);
                } else {
                    redirect(base_url(), 'refresh');
                }
            } else {
                redirect(base_url(), 'refresh');
            }
        } else {
            redirect(base_url(), 'refresh');
        }
    }

    public function update_order_item_status()
    {
        $this->form_validation->set_rules('order_item_id', 'Order item id', 'trim|required|numeric|xss_clean');
        $this->form_validation->set_rules('status', 'Status', 'trim|required|xss_clean|in_list[cancelled,returned]');
        if (!$this->form_validation->run()) {
            $this->response['error'] = true;
            $this->response['message'] = strip_tags(validation_errors());
            $this->response['data'] = array();
        } else {
            $this->response = $this->order_model->update_order_item($_POST['order_item_id'], trim($_POST['status']));
            if (trim($_POST['status']) != 'returned' && $this->response['error'] == false) {
                process_refund($_POST['order_item_id'], trim($_POST['status']), 'order_items');
            }
            if ($this->response['error'] == false && trim($_POST['status']) == 'cancelled') {
                $data = fetch_details('order_items', ['id' => $_POST['order_item_id']], 'product_variant_id,quantity');
                update_stock($data[0]['product_variant_id'], $data[0]['quantity'], 'plus');
            }
        }
        print_r(json_encode($this->response));
    }

    public function update_order()
    {
        $this->form_validation->set_rules('order_id', 'Order id', 'trim|required|xss_clean');
        $this->form_validation->set_rules('status', 'Status', 'trim|required|xss_clean|in_list[cancelled,returned]');
        if (!$this->form_validation->run()) {
            $this->response['error'] = true;
            $this->response['message'] = validation_errors();
            $this->response['data'] = array();
            print_r(json_encode($this->response));
            return false;
        } else {
            $res = validate_order_status($_POST['order_id'], $_POST['status'], 'orders');
            if ($res['error']) {
                $this->response['error'] = (isset($res['return_request_flag'])) ? false : true;
                $this->response['message'] = $res['message'];
                $this->response['data'] = $res['data'];
                print_r(json_encode($this->response));
                return false;
            }
            if ($this->order_model->update_order(['status' => $_POST['status']], ['id' => $_POST['order_id']], true)) {
                $this->order_model->update_order(['active_status' => $_POST['status']], ['id' => $_POST['order_id']], false);
                if ($this->order_model->update_order(['status' => $_POST['status']], ['order_id' => $_POST['order_id']], true, 'order_items')) {
                    $this->order_model->update_order(['active_status' => $_POST['status']], ['order_id' => $_POST['order_id']], false, 'order_items');
                    process_refund($_POST['order_id'], $_POST['status'], 'orders');
                    if (trim($_POST['status'] == 'cancelled')) {
                        $data = fetch_details('order_items', ['order_id' => $_POST['order_id']], 'product_variant_id,quantity');
                        $product_variant_ids = [];
                        $qtns = [];
                        foreach ($data as $d) {
                            array_push($product_variant_ids, $d['product_variant_id']);
                            array_push($qtns, $d['quantity']);
                        }

                        update_stock($product_variant_ids, $qtns, 'plus');
                    }
                    $this->response['error'] = false;
                    $this->response['message'] = 'Order Updated Successfully';
                    $this->response['data'] = array();
                    print_r(json_encode($this->response));
                    return false;
                }
            }
        }
    }

    public function notifications()
    {
        if ($this->ion_auth->logged_in()) {
            $this->data['main_page'] = 'notifications';
            $this->data['title'] = 'Notification | ' . $this->data['web_settings']['site_title'];
            $this->data['keywords'] = 'Notification, ' . $this->data['web_settings']['meta_keywords'];
            $this->data['description'] = 'Notification | ' . $this->data['web_settings']['meta_description'];
            $this->load->view('front-end/' . THEME . '/template', $this->data);
        } else {
            redirect(base_url(), 'refresh');
        }
    }

    public function manage_address()
    {
        if ($this->ion_auth->logged_in()) {
            $this->data['main_page'] = 'address';
            $this->data['title'] = 'Address | ' . $this->data['web_settings']['site_title'];
            $this->data['keywords'] = 'Address, ' . $this->data['web_settings']['meta_keywords'];
            $this->data['description'] = 'Address | ' . $this->data['web_settings']['meta_description'];
            $this->data['cities'] = get_cities();
            $this->load->view('front-end/' . THEME . '/template', $this->data);
        } else {
            redirect(base_url(), 'refresh');
        }
    }

    public function wallet()
    {
        if ($this->ion_auth->logged_in()) {
            $this->data['main_page'] = 'wallet';
            $this->data['title'] = 'Wallet | ' . $this->data['web_settings']['site_title'];
            $this->data['keywords'] = 'Wallet, ' . $this->data['web_settings']['meta_keywords'];
            $this->data['description'] = 'Wallet | ' . $this->data['web_settings']['meta_description'];
            $this->load->view('front-end/' . THEME . '/template', $this->data);
        } else {
            redirect(base_url(), 'refresh');
        }
    }

    public function transactions()
    {
        if ($this->ion_auth->logged_in()) {
            $this->data['main_page'] = 'transactions';
            $this->data['title'] = 'Transactions | ' . $this->data['web_settings']['site_title'];
            $this->data['keywords'] = 'Transactions, ' . $this->data['web_settings']['meta_keywords'];
            $this->data['description'] = 'Transactions | ' . $this->data['web_settings']['meta_description'];
            $this->load->view('front-end/' . THEME . '/template', $this->data);
        } else {
            redirect(base_url(), 'refresh');
        }
    }

    public function add_address()
    {
        // print_R($_POST);
        if ($this->ion_auth->logged_in()) {
            $this->form_validation->set_rules('type', 'Type', 'trim|xss_clean');
            $this->form_validation->set_rules('country_code', 'Country Code', 'trim|xss_clean');
            $this->form_validation->set_rules('name', 'Name', 'trim|xss_clean|required');
            $this->form_validation->set_rules('mobile', 'Mobile', 'trim|numeric|xss_clean|required');
            $this->form_validation->set_rules('alternate_mobile', 'Alternative Mobile', 'trim|numeric|xss_clean');
            $this->form_validation->set_rules('address', 'Address', 'trim|xss_clean|required');
            $this->form_validation->set_rules('landmark', 'Landmark', 'trim|xss_clean');
            $this->form_validation->set_rules('city_name', 'City', 'trim|xss_clean');
            $this->form_validation->set_rules('area_name', 'Area', 'trim|xss_clean');
            $this->form_validation->set_rules('area_id', 'Area', 'trim|xss_clean');
            $this->form_validation->set_rules('city_id', 'City', 'trim|xss_clean');
            $this->form_validation->set_rules('pincode', 'Pincode', 'trim|numeric|xss_clean');
            $this->form_validation->set_rules('state', 'State', 'trim|xss_clean|required');
            $this->form_validation->set_rules('country', 'Country', 'trim|xss_clean|required');
            $this->form_validation->set_rules('latitude', 'Latitude', 'trim|xss_clean');
            $this->form_validation->set_rules('longitude', 'Longitude', 'trim|xss_clean');

            if (!$this->form_validation->run()) {
                $this->response['error'] = true;
                $this->response['message'] = validation_errors();
                $this->response['data'] = array();
                print_r(json_encode($this->response));
                return false;
            }

            $arr = $this->input->post(null, true);
            $arr['user_id'] = $this->data['user']->id;
            $this->address_model->set_address($arr);
            $res = $this->address_model->get_address($this->data['user']->id, false, true);
            $this->response['error'] = false;
            $this->response['message'] = 'Address Added Successfully';
            $this->response['data'] = $res;
            print_r(json_encode($this->response));
            return false;
        } else {
            $this->response['error'] = true;
            $this->response['message'] = 'Unauthorized access is not allowed';
            print_r(json_encode($this->response));
            return false;
        }
    }

    public function edit_address()
    {
        if ($this->ion_auth->logged_in()) {
            $this->form_validation->set_rules('id', 'Id', 'trim|required|numeric|xss_clean');
            $this->form_validation->set_rules('type', 'Type', 'trim|xss_clean');
            $this->form_validation->set_rules('country_code', 'Country Code', 'trim|xss_clean');
            $this->form_validation->set_rules('name', 'Name', 'trim|xss_clean');
            $this->form_validation->set_rules('mobile', 'Mobile', 'trim|numeric|xss_clean');
            $this->form_validation->set_rules('alternate_mobile', 'Alternative Mobile', 'trim|numeric|xss_clean');
            $this->form_validation->set_rules('address', 'Address', 'trim|xss_clean');
            $this->form_validation->set_rules('landmark', 'Landmark', 'trim|xss_clean');
            $this->form_validation->set_rules('area_id', 'Area', 'trim|xss_clean');
            $this->form_validation->set_rules('city_id', 'City', 'trim|xss_clean');
            $this->form_validation->set_rules('pincode', 'Pincode', 'trim|numeric|xss_clean');
            $this->form_validation->set_rules('state', 'State', 'trim|xss_clean');
            $this->form_validation->set_rules('country', 'Country', 'trim|xss_clean');

            if (!$this->form_validation->run()) {
                $this->response['error'] = true;
                $this->response['message'] = validation_errors();
                $this->response['data'] = array();
                print_r(json_encode($this->response));
                return false;
            }
            // print_R($_POST);
            $this->address_model->set_address($_POST);
            $res = $this->address_model->get_address(null, $_POST['id'], true);
            $this->response['error'] = false;
            $this->response['message'] = 'Address updated Successfully';
            $this->response['data'] = $res;
            print_r(json_encode($this->response));
            return false;
        } else {
            $this->response['error'] = true;
            $this->response['message'] = 'Unauthorized access is not allowed';
            print_r(json_encode($this->response));
            return false;
        }
    }

    //delete_address
    public function delete_address()
    {
        if ($this->ion_auth->logged_in()) {
            $this->form_validation->set_rules('id', 'Id', 'trim|required|numeric|xss_clean');
            if (!$this->form_validation->run()) {
                $this->response['error'] = true;
                $this->response['message'] = validation_errors();
                $this->response['data'] = array();
                print_r(json_encode($this->response));
                return false;
            }
            $this->address_model->delete_address($_POST);
            $this->response['error'] = false;
            $this->response['message'] = 'Address Deleted Successfully';
            $this->response['data'] = array();
            print_r(json_encode($this->response));
            return false;
        } else {
            $this->response['error'] = true;
            $this->response['message'] = 'Unauthorized access is not allowed';
            print_r(json_encode($this->response));
            return false;
        }
    }

    //set default_address
    public function set_default_address()
    {
        if ($this->ion_auth->logged_in()) {
            $this->form_validation->set_rules('id', 'Id', 'trim|required|numeric|xss_clean');
            if (!$this->form_validation->run()) {
                $this->response['error'] = true;
                $this->response['message'] = validation_errors();
                $this->response['data'] = array();
                print_r(json_encode($this->response));
                return false;
            }
            $_POST['is_default'] = true;
            $this->address_model->set_address($_POST);
            $this->response['error'] = false;
            $this->response['message'] = 'Set as default successfully';
            $this->response['data'] = array();
            print_r(json_encode($this->response));
            return false;
        } else {
            $this->response['error'] = true;
            $this->response['message'] = 'Unauthorized access is not allowed';
            print_r(json_encode($this->response));
            return false;
        }
    }

    //get_address
    public function get_address()
    {
        if ($this->ion_auth->logged_in()) {
            $res = $this->address_model->get_address($this->data['user']->id);
            $is_default_counter = array_count_values(array_column($res, 'is_default'));

            // if (!isset($is_default_counter['1']) && !empty($res)) {
            //     update_details(['is_default' => '1'], ['id' => $res[0]['id']], 'addresses');
            //     $res = $this->address_model->get_address($this->data['user']->id);
            // }
            if (!empty($res)) {
                $this->response['error'] = false;
                $this->response['message'] = 'Address Retrieved Successfully';
                $this->response['data'] = $res;
            } else {
                $this->response['error'] = true;
                $this->response['message'] = "No Details Found !";
                $this->response['data'] = array();
            }
            print_r(json_encode($this->response));
        } else {
            $this->response['error'] = true;
            $this->response['message'] = 'Unauthorized access is not allowed';
            print_r(json_encode($this->response));
            return false;
        }
    }
    public function get_promo_codes()
    {
        if ($this->ion_auth->logged_in()) {
            $this->form_validation->set_rules('sort', 'sort', 'trim|xss_clean');
            $this->form_validation->set_rules('limit', 'limit', 'trim|numeric|xss_clean');
            $this->form_validation->set_rules('offset', 'offset', 'trim|numeric|xss_clean');
            $this->form_validation->set_rules('order', 'order', 'trim|xss_clean');

            if (!$this->form_validation->run()) {

                $this->response['error'] = true;
                $this->response['message'] = strip_tags(validation_errors());
                $this->response['data'] = array();
                print_r(json_encode($this->response));
                return;
            } else {
                $limit = (isset($_POST['limit']) && is_numeric($_POST['limit']) && !empty(trim($_POST['limit']))) ? $this->input->post('limit', true) : 25;
                $offset = (isset($_POST['offset']) && is_numeric($_POST['offset']) && !empty(trim($_POST['offset']))) ? $this->input->post('offset', true) : 0;
                $order = (isset($_POST['order']) && !empty(trim($_POST['order']))) ? $_POST['order'] : 'DESC';
                $sort = (isset($_POST['sort']) && !empty(trim($_POST['sort']))) ? $_POST['sort'] : 'id';
                $this->response['error'] = false;
                $this->response['message'] = 'Promocodes retrived Successfully !';
                $result = $this->Promo_code_model->get_promo_codes($limit, $offset, $sort, $order);
                $this->response['total'] = $result['total'];
                $this->response['offset'] = (isset($offset) && !empty($offset)) ? $offset : '0';
                $this->response['promo_codes'] = $result['data'];
                print_r(json_encode($this->response));
                return;
            }
        } else {
            $this->response['error'] = true;
            $this->response['message'] = 'Unauthorized access is not allowed';
            print_r(json_encode($this->response));
            return false;
        }
    }


    public function get_address_list()
    {
        if ($this->ion_auth->logged_in()) {
            return $this->address_model->get_address_list($this->data['user']->id);
        } else {
            $this->response['error'] = true;
            $this->response['message'] = 'Unauthorized access is not allowed';
            print_r(json_encode($this->response));
            return false;
        }
    }

    public function get_areas()
    {
        if ($this->ion_auth->logged_in()) {
            $this->form_validation->set_rules('city_id', 'City Id', 'trim|required|xss_clean');
            if (!$this->form_validation->run()) {
                $this->response['error'] = true;
                $this->response['message'] = validation_errors();
                print_r(json_encode($this->response));
                return false;
            }

            $city_id = $this->input->post('city_id', true);
            $areas = fetch_details('areas', ['city_id' => $city_id]);
            if (empty($areas)) {
                $this->response['error'] = true;
                $this->response['message'] = "No Areas found for this City.";
                print_r(json_encode($this->response));
                return false;
            }
            $this->response['error'] = false;
            $this->response['data'] = $areas;
            print_r(json_encode($this->response));
            return false;
        } else {
            $this->response['error'] = true;
            $this->response['message'] = 'Unauthorized access is not allowed';
            print_r(json_encode($this->response));
            return false;
        }
    }

    public function favorites()
    {
        if ($this->data['is_logged_in']) {
            $this->data['main_page'] = 'favorites';
            $this->data['title'] = 'Dashboard | ' . $this->data['web_settings']['site_title'];
            $this->data['keywords'] = 'Dashboard, ' . $this->data['web_settings']['meta_keywords'];
            $this->data['description'] = 'Dashboard | ' . $this->data['web_settings']['meta_description'];
            $this->data['products'] = get_favorites($this->data['user']->id);
            $this->data['settings'] = get_settings('system_settings', true);
            $this->load->view('front-end/' . THEME . '/template', $this->data);
        } else {
            redirect(base_url(), 'refresh');
        }
    }

    public function manage_favorites()
    {
        if ($this->data['is_logged_in']) {
            $this->form_validation->set_rules('product_id', 'Product Id', 'trim|numeric|required|xss_clean');
            if (!$this->form_validation->run()) {
                $this->response['error'] = true;
                $this->response['message'] = validation_errors();
                $this->response['data'] = array();
            } else {
                $data = [
                    'user_id' => $this->data['user']->id,
                    'product_id' => $this->input->post('product_id', true),
                ];
                if (is_exist($data, 'favorites')) {
                    $this->db->delete('favorites', $data);
                    $this->response['error']   = false;
                    $this->response['message'] = "Product removed from favorite !";
                    print_r(json_encode($this->response));
                    return false;
                }
                $data = escape_array($data);
                $this->db->insert('favorites', $data);
                $this->response['error'] = false;
                $this->response['message'] = 'Product Added to favorite';
                print_r(json_encode($this->response));
                return false;
            }
        } else {
            $this->response['error'] = true;
            $this->response['message'] = "Login First to Add Products in Favorite List.";
            print_r(json_encode($this->response));
            return false;
        }
    }

    public function get_transactions()
    {
        if ($this->ion_auth->logged_in()) {
            return $this->Transaction_model->get_transactions_list($this->data['user']->id);
        } else {
            redirect(base_url(), 'refresh');
        }
    }

    public function get_wallet_transactions()
    {
        if ($this->ion_auth->logged_in()) {
            return $this->Transaction_model->get_transactions_list($this->data['user']->id);
        } else {
            redirect(base_url(), 'refresh');
        }
    }

    public function get_withdrawal_request()
    {
        if ($this->ion_auth->logged_in()) {
            return $this->Transaction_model->get_withdrawal_transactions_list($this->data['user']->id);
        } else {
            redirect(base_url(), 'refresh');
        }
    }

    public function get_zipcode()
    {
        if ($this->ion_auth->logged_in()) {
            $this->form_validation->set_rules('area_id', 'Area Id', 'trim|required|xss_clean');
            if (!$this->form_validation->run()) {
                $this->response['error'] = true;
                $this->response['message'] = validation_errors();
                print_r(json_encode($this->response));
                return false;
            }

            $area_id = $this->input->post('area_id', true);
            $areas = fetch_details('areas', ['id' => $area_id], 'zipcode_id');
            $zipcodes = fetch_details('zipcodes', ['id' => $areas[0]['zipcode_id']], 'zipcode');
            if (empty($areas)) {
                $this->response['error'] = true;
                $this->response['message'] = "No Zipcodes found for this area.";
                print_r(json_encode($this->response));
                return false;
            }
            $this->response['error'] = false;
            $this->response['data'] = $zipcodes;
            print_r(json_encode($this->response));
            return false;
        } else {
            $this->response['error'] = true;
            $this->response['message'] = 'Unauthorized access is not allowed';
            print_r(json_encode($this->response));
            return false;
        }
    }

    public function delete_user()
    {
        if ($this->ion_auth->logged_in()) {
            $_SESSION['user_id'];
            $data = fetch_details('users', ['id' => $_SESSION['user_id']],  'mobile,password');
            if ($this->ion_auth->logged_in()) {
                $this->data['main_page'] = 'delete account';
                $this->data['title'] = 'Delete account | ' . $this->data['web_settings']['site_title'];
                $this->data['keywords'] = 'Delete account, ' . $this->data['web_settings']['meta_keywords'];
                $this->data['description'] = 'Delete account | ' . $this->data['web_settings']['meta_description'];
                $this->load->view('front-end/' . THEME . '/template', $this->data);
            }
        }
    }

    public function delete_account()
    {

        if ($this->ion_auth->logged_in()) {
            $this->form_validation->set_rules('mobile', 'Mobile', 'trim|xss_clean');
            $this->form_validation->set_rules('password', 'Password', 'trim|xss_clean|required');
            if (!$this->form_validation->run()) {
                $this->response['error'] = true;
                $this->response['message'] = validation_errors();
                $this->response['data'] = array();
                print_r(json_encode($this->response));
                return false;
            } else {
                $user_id = $_SESSION['user_id'];
                $data = fetch_details('users', ['id' => $user_id],  'mobile,password');

                $user_data = fetch_details('users', ['id' => $user_id, 'mobile' => $data[0]['mobile']], 'id,username,password,active,mobile');
                if ($user_data) {

                    $login = $this->ion_auth->login($user_data[0]['mobile'], $_POST['password']);
                    if ($login) {
                        $user_group = fetch_details('users_groups', ['user_id' => $user_id], 'group_id');
                        if ($user_group[0]['group_id'] == '2') {
                            $status =   'awaiting,received,processed,shipped';
                            $multiple_status = explode(',', $status);
                            $orders = fetch_orders('', $user_id,  $multiple_status);

                            foreach ($orders['order_data'] as $order) {

                                if ($this->order_model->update_order(['status' => 'cancelled'], ['id' => $order['id']], true)) {
                                    $this->order_model->update_order(['active_status' => 'cancelled'], ['id' => $order['id']], false);
                                    if ($this->order_model->update_order(['status' => 'cancelled'], ['order_id' => $order['id']], true, 'order_items')) {
                                        $this->order_model->update_order(['active_status' => 'cancelled'], ['order_id' => $order['id']], false, 'order_items');
                                        process_refund($order['id'], 'cancelled', 'orders');
                                        $data = fetch_details('order_items', ['order_id' => $order['id']], 'product_variant_id,quantity');
                                        $product_variant_ids = [];
                                        $qtns = [];
                                        foreach ($data as $d) {
                                            array_push($product_variant_ids, $d['product_variant_id']);
                                            array_push($qtns, $d['quantity']);
                                        }

                                        update_stock($product_variant_ids, $qtns, 'plus');
                                    }
                                }
                            }
                            delete_details(['id' => $user_id], 'users');
                            delete_details(['user_id' => $user_id], 'users_groups');
                            $response['error'] = false;
                            $response['message'] = 'User Deleted Succesfully';
                            $this->session->sess_destroy();
                            // redirect(base_url());
                        } else if ($user_group[0]['group_id'] == '3') {
                            delete_details(['id' => $user_id], 'users');
                            delete_details(['user_id' => $user_id], 'users_groups');
                            $response['error'] = false;
                            $response['message'] = 'Delivery Boy  Deleted Succesfully';
                            $this->session->sess_destroy();
                        } else {
                            $response['error'] = true;
                            $response['message'] = 'Details do not match';
                        }
                    } else {
                        $response['error'] = true;
                        $response['message'] = 'Details do not match';
                    }
                } else {
                    $response['error'] = true;
                    $response['message'] = 'User Not Found';
                }
            }
            print_r(json_encode($response));
            return;
        }
    }

    public function tickets()
    {
        $limits = ($this->input->get('per-page')) ? $this->input->get('per-page', true) : 4;

        $total_row = fetch_details('tickets', ['user_id' => $_SESSION['user_id']], 'id');
        // foreach ($total_row as $total_rows) {

        $total = count($total_row);

        $config['base_url'] = base_url('my_account/tickets');
        $config['total_rows'] = $total;
        $config['per_page'] = $limits;
        $config['use_page_numbers'] = TRUE;
        $config['uri_segment'] = 3;
        $config['num_links'] = 7;
        $config['use_page_numbers'] = TRUE;
        $config['reuse_query_string'] = TRUE;
        $config['page_query_string'] = FALSE;

        $config['attributes'] = array('class' => 'page-link');
        $config['full_tag_open'] = '<ul class="pagination justify-content-center">';
        $config['full_tag_close'] = '</ul>';

        $config['first_tag_open'] = '<li class="page-item">';
        $config['first_link'] = 'First';
        $config['first_tag_close'] = '</li>';

        $config['last_tag_open'] = '<li class="page-item">';
        $config['last_link'] = 'Last';
        $config['last_tag_close'] = '</li>';

        $config['prev_tag_open'] = '<li class="page-item">';
        $config['prev_link'] = '<i class="fa fa-arrow-left"></i>';
        $config['prev_tag_close'] = '</li>';

        $config['next_tag_open'] = '<li class="page-item">';
        $config['next_link'] = '<i class="fa fa-arrow-right"></i>';
        $config['next_tag_close'] = '</li>';

        $config['cur_tag_open'] = '<li class="page-item active"><a class="page-link">';
        $config['cur_tag_close'] = '</a></li>';

        $config['num_tag_open'] = '<li class="page-item">';
        $config['num_tag_close'] = '</li>';
        $page_no = (empty($this->uri->segment(3))) ? 1 : $this->uri->segment(3);
        if (!is_numeric($page_no)) {
            redirect(base_url('my_account/tickets'));
        }
        $offset = ($page_no - 1) * $limits;
        $this->pagination->initialize($config);
        $this->data['links'] =  $this->pagination->create_links();



        // }

        $this->data['main_page'] = 'tickets';
        $this->data['title'] = 'Customer Support | ' . $this->data['web_settings']['site_title'];
        $this->data['keywords'] = 'Customer Support, ' . $this->data['web_settings']['meta_keywords'];
        $this->data['description'] = 'Customer Support | ' . $this->data['web_settings']['meta_description'];
        $this->data['meta_description'] = 'Customer Support | ' . $this->data['web_settings']['site_title'];
        $this->data['ticket_types'] = fetch_details('ticket_types');
        $this->data['tickets'] = fetch_details('tickets', ['user_id' => $_SESSION['user_id']], '*', $limits, $offset);
        $this->load->view('front-end/' . THEME . '/template', $this->data);
    }

    public function withdraw_money()
    {
        // print_r($_POST);
        // return;
        $this->form_validation->set_rules('user_id', 'User Id', 'trim|numeric|required|xss_clean');
        $this->form_validation->set_rules('payment_address', 'Payment Address', 'trim|required|xss_clean');
        $this->form_validation->set_rules('amount', 'Amount', 'trim|required|xss_clean|numeric|greater_than[0]');

        if (!$this->form_validation->run()) {
            $this->response['error'] = true;
            $this->response['message'] = strip_tags(validation_errors());
            $this->response['data'] = array();
            print_r(json_encode($this->response));
        } else {
            $user_id = $this->input->post('user_id', true);
            $payment_address = $this->input->post('payment_address', true);
            $amount = $this->input->post('amount', true);
            $userData = fetch_details('users', ['id' => $_POST['user_id']], 'balance');

            if (!empty($userData)) {

                if ($_POST['amount'] <= $userData[0]['balance']) {

                    $data = [
                        'user_id' => $user_id,
                        'payment_address' => $payment_address,
                        'payment_type' => 'customer',
                        'amount_requested' => $amount,
                    ];


                    if (insert_details($data, 'payment_requests')) {
                        $this->Customer_model->update_balance_customer($amount, $user_id, 'deduct');
                        $userData = fetch_details('users', ['id' => $_POST['user_id']], 'balance');
                        $this->response['error'] = false;
                        $this->response['message'] = 'Withdrawal Request Sent Successfully';
                        $this->response['data'] = $userData[0]['balance'];
                    } else {
                        $this->response['error'] = true;
                        $this->response['message'] = 'Cannot sent Withdrawal Request.Please Try again later.';
                        $this->response['data'] = array();
                    }
                } else {
                    $this->response['error'] = true;
                    $this->response['message'] = 'You don\'t have enough balance to sent the withdraw request.';
                    $this->response['data'] = array();
                }

                print_r(json_encode($this->response));
            }
        }
    }
}
