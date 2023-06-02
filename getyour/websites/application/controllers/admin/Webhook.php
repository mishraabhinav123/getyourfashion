<?php defined('BASEPATH') or exit('No direct script access allowed');
class Webhook extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function razorpay()
    {
        //Debug in server first
        $request = file_get_contents('php://input');
        $request = json_decode($request, true);
        // $request = $_POST;        
        log_message('error', 'Razorpay IPN POST --> ' . var_export($request, true));
        $settings = get_settings('payment_method', true);
        $key = $settings['refund_webhook_secret_key'];
        if ($request['event'] == "refund.processed") {
            //Refund Successfully

            $transaction = fetch_details('transactions', ['txn_id' => $request['payload']['refund']['entity']['payment_id']]);
            if (empty($transaction)) {
                return false;
            }
            process_refund($transaction[0]['id'], $transaction[0]['status']);
        } elseif ($request['event'] == "refund.failed") {
            die("payment Failed");
        }
        die("IPN OK");
    }

    public function paystack()
    {
        print_r($_SERVER);
        if ((strtoupper($_SERVER['REQUEST_METHOD']) != 'POST') || !array_key_exists('x-paystack-signature', $_SERVER))
            $this->load->library(['paystack']);
        $system_settings = get_settings('system_settings', true);
        $credentials = $this->paystack->get_credentials();
        $input = file_get_contents('php://input');
        $event = json_decode($input, FALSE);
        log_message('error', 'paystack Webhook --> ' . var_export($event, true));
        log_message('error', 'paystack Webhook SERVER Variable --> ' . var_export($_SERVER, true));

        if (!empty($event->data->object->payment_intent)) {
            $txn_id = (isset($event->data->object->payment_intent)) ? $event->data->object->payment_intent : "";
           echo "tax" .$txn_id;
            print_r($txn_id);
            if (!empty($txn_id)) {
                $transaction = fetch_details('transactions', ['txn_id' => $txn_id], '*');
                if (!empty($transaction)) {
                    $order_id = $transaction[0]['order_id'];
                    $user_id = $transaction[0]['user_id'];
                } else {
                    $order_id = 0;
                }
            }
            $amount = $event->data->object->amount;
            echo "account" .$amount;
            print_r($amount);
            $currency = $event->data->object->currency;
            $balance_transaction = $event->data->object->balance_transaction;
        } else {
            $order_id = 0;
            $amount = 0;
            $currency = (isset($event->data->object->currency)) ? $event->data->object->currency : "";
            $balance_transaction = 0;
        }
        /* Wallet refill has unique format for order ID - wallet-refill-user-{user_id}-{system_time}-{3 random_number}  */
        if (empty($order_id)) {
            $order_id = (!empty($event->data->object->metadata) && isset($event->data->object->metadata->order_id)) ? $event->data->object->metadata->order_id : 0;
        }

        if (!is_numeric($order_id) && strpos($order_id, "wallet-refill-user") !== false) {
            $temp = explode("-", $order_id);
            if (isset($temp[3]) && is_numeric($temp[3]) && !empty($temp[3] && $temp[3] != '')) {
                $user_id = $temp[3];
            } else {
                $user_id = 0;
            }
        }
        $signature = (isset($_SERVER['HTTP_X_PAYSTACK_SIGNATURE']) ? $_SERVER['HTTP_X_PAYSTACK_SIGNATURE'] : '');
        $result = $this->paystack->construct_event($input, $signature, $credentials['webhook_key']);
        print_r($result);
        return false;
        if ($result == "Matched") {
            if ($event->type == 'charge.succeeded') {
                if (!empty($order_id)) {
                    /* To do the wallet recharge if the order id is set in the patter */
                    if (strpos($order_id, "wallet-refill-user") !== false) {
                        $data['transaction_type'] = "wallet";
                        $data['user_id'] = $user_id;
                        $data['order_id'] = $order_id;
                        $data['type'] = "credit";
                        $data['txn_id'] = $txn_id;
                        $data['amount'] = $amount / 100;
                        $data['status'] = "success";
                        $data['message'] = "Wallet refill successful";
                        $this->transaction_model->add_transaction($data);

                        $this->load->model('customer_model');
                        if ($this->customer_model->update_balance($amount / 100, $user_id, 'add')) {
                            $response['error'] = false;
                            $response['transaction_status'] = $event->type;
                            $response['message'] = "Wallet recharged successfully!";
                        } else {
                            $response['error'] = true;
                            $response['transaction_status'] = $event->type;
                            $response['message'] = "Wallet could not be recharged!";
                            log_message('error', 'paystack Webhook | wallet recharge failure --> ' . var_export($event, true));
                        }
                        echo json_encode($response);
                        return false;
                    } else {

                        /* process the order and mark it as received */
                        $order = fetch_orders($order_id, false, false, false, false, false, false, false);
                        if (isset($order['order_data'][0]['user_id'])) {
                            $user = fetch_details('users', ['id' => $order['order_data'][0]['user_id']]);
                            $overall_total = array(
                                'total_amount' => $order['order_data'][0]['total'],
                                'delivery_charge' => $order['order_data'][0]['delivery_charge'],
                                'tax_amount' => $order['order_data'][0]['total_tax_amount'],
                                'tax_percentage' => $order['order_data'][0]['total_tax_percent'],
                                'discount' =>  $order['order_data'][0]['promo_discount'],
                                'wallet' =>  $order['order_data'][0]['wallet_balance'],
                                'final_total' =>  $order['order_data'][0]['final_total'],
                                'otp' => $order['order_data'][0]['otp'],
                                'address' =>  $order['order_data'][0]['address'],
                                'payment_method' => $order['order_data'][0]['payment_method']
                            );

                            $overall_order_data = array(
                                'cart_data' => $order['order_data'][0]['order_items'],
                                'order_data' => $overall_total,
                                'subject' => 'Order received successfully',
                                'user_data' => $user[0],
                                'system_settings' => $system_settings,
                                'user_msg' => 'Hello, Dear ' . ucfirst($user[0]['username']) . ', We have received your order successfully. Your order summaries are as followed',
                                'otp_msg' => 'Here is your OTP. Please, give it to delivery boy only while getting your order.',
                            );
                            if (isset($user[0]['email']) && !empty($user[0]['email'])) {
                                send_mail($user[0]['email'], 'Order received successfully', $this->load->view('admin/pages/view/email-template.php', $overall_order_data, TRUE));
                            }
                            /* No need to add because the transaction is already added just update the transaction status */
                            if (!empty($transaction)) {
                                $transaction_id = $transaction[0]['id'];
                                update_details(['status' => 'success'], ['id' => $transaction_id], 'transactions');
                            }

                            /* add transaction of the payment */

                            update_details(['active_status' => 'received'], ['id' => $order_id], 'orders');
                            update_details(['active_status' => 'received'], ['order_id' => $order_id], 'order_items');

                            $status = json_encode(array(array('received', date("d-m-Y h:i:sa"))));
                            update_details(['status' => $status], ['id' => $order_id], 'orders', false);
                            update_details(['status' => $status], ['order_id' => $order_id], 'order_items', false);
                        }
                    }
                } else {
                    /* No order ID found */
                }

                $response['error'] = false;
                $response['transaction_status'] = $event->type;
                $response['message'] = "Transaction successfully done";
                echo json_encode($response);
                return false;
            } elseif ($event->type == 'charge.failed') {
                if (!empty($order_id)) {
                    update_details(['active_status' => 'cancelled'], ['id' => $order_id], 'orders');
                    update_details(['active_status' => 'cancelled'], ['order_id' => $order_id], 'order_items');
                }
                /* No need to add because the transaction is already added just update the transaction status */
                if (!empty($transaction)) {
                    $transaction_id = $transaction[0]['id'];
                    update_details(['status' => 'failed'], ['id' => $transaction_id], 'transactions');
                }
                $response['error'] = true;
                $response['transaction_status'] = $event->type;
                $response['message'] = "Transaction is failed. ";
                // log_message('error', 'paystack Webhook | Transaction is failed --> ' . var_export($event, true));
                echo json_encode($response);
                return false;
            } elseif ($event->type == 'charge.pending') {
                $response['error'] = false;
                $response['transaction_status'] = $event->type;
                $response['message'] = "Waiting customer to finish transaction ";
                // log_message('error', 'paystack Webhook | Waiting customer to finish transaction --> ' . var_export($event, true));
                echo json_encode($response);
                return false;
            } elseif ($event->type == 'charge.expired') {
                if (!empty($order_id)) {
                    update_details(['active_status' => 'cancelled'], ['id' => $order_id], 'orders');
                    update_details(['active_status' => 'cancelled'], ['order_id' => $order_id], 'order_items');
                }
                /* No need to add because the transaction is already added just update the transaction status */
                if (!empty($transaction)) {
                    $transaction_id = $transaction[0]['id'];
                    update_details(['status' => 'expired'], ['id' => $transaction_id], 'transactions');
                }
                $response['error'] = true;
                $response['transaction_status'] = $event->type;
                $response['message'] = "Transaction is expired.";
                // log_message('error', 'paystack Webhook | Transaction is expired --> ' . var_export($event, true));
                echo json_encode($response);
                return false;
            } elseif ($event->type == 'charge.refunded') {
                if (!empty($order_id)) {
                    update_details(['active_status' => 'cancelled'], ['id' => $order_id], 'orders');
                    update_details(['active_status' => 'cancelled'], ['order_id' => $order_id], 'order_items');
                }
                /* No need to add because the transaction is already added just update the transaction status */
                if (!empty($transaction)) {
                    $transaction_id = $transaction[0]['id'];
                    update_details(['status' => 'refunded'], ['id' => $transaction_id], 'transactions');
                }
                $response['error'] = true;
                $response['transaction_status'] = $event->type;
                $response['message'] = "Transaction is refunded.";
                // log_message('error', 'paystack Webhook | Transaction is refunded --> ' . var_export($event, true));
                echo json_encode($response);
                return false;
            } else {
                $response['error'] = true;
                $response['transaction_status'] = $event->type;
                $response['message'] = "Transaction could not be detected.";
                // log_message('error', 'paystack Webhook | Transaction could not be detected --> ' . var_export($event, true));
                echo json_encode($response);
                return false;
            }
        } else {
            log_message('error', 'paystack Webhook | Invalid Server Signature  --> ' . var_export($result, true));
            return false;
        }
    }

    public function flutterwave()
    {

        $this->config->load('flutterwave');
        $local_secret_hash = $this->config->item('secret_hash');

        $body = @file_get_contents("php://input");
        $response = json_decode($body, 1);
        log_message('debug', 'Flutter Wave Webhook - Normal Response - JSON DATA --> ' . var_export($response, true));
        log_message('debug', 'Server Variable --> ' . var_export($_SERVER, true));
        /* Reading the signature sent by flutter wave webhook */
        $signature = (isset($_SERVER['HTTP_VERIF_HASH'])) ? $_SERVER['HTTP_VERIF_HASH'] : '';
        /* comparing our local signature with received signature */
        if (empty($signature) || $signature != $local_secret_hash) {
            log_message('error', 'Flutter Wave Webhook - Invalid Signature - JSON DATA --> ' . var_export($response, true));
            log_message('error', 'Server Variable --> ' . var_export($_SERVER, true));
            if(strtolower($response['status']) == 'successful') {
                // TIP: you may still verify the transaction
                // before giving value.
                $response = $this->flutterwave->verify_transaction($response['txRef']);
                
                $response = json_decode($response,1);
                
                if(!empty($response) && isset($response['data']['status']) && strtolower($response['data']['status']) == 'successful' 
                    && isset($response['data']['chargecode']) && ( $response['data']['chargecode'] == '00' || $response['data']['chargecode'] == '0')
                ){
                    print_r($response['data']['status']);
                    return false;
                    $payer_email = $response['data']['custemail'];
                    $paymentplan = $response['data']['paymentplan'];
                    
                    $response['error'] = false;
                    $response['transaction_status'] = $response['data']['status'];
                    $response['message'] = "Transaction successfully done";
                    echo json_encode($response);
                    return false;
                    
                }else{
                    /* Transaction failed */
                    log_message('error', 'Flutter Wave Webhook - Inner Verification Failed --> ' . var_export($response, true));
                    log_message('error', 'Server Variable -->  '.var_export($_SERVER,true));
                }
                
            }else{
                /* Transaction failed */
                log_message('error', 'Flutter Wave Webhook - Outter Verification Failed --> ' . var_export($response, true));
                log_message('error', 'Server Variable -->  '.var_export($_SERVER,true));
            }
            
        }
    }
}