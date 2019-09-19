<?php

namespace Drupal\uc_bitpaycheckout\Plugin\Ubercart\PaymentMethod;

use Drupal\uc_bitpaycheckout\BPC_Configuration;
use Drupal\uc_bitpaycheckout\BPC_Invoice;
use Drupal\uc_bitpaycheckout\BPC_Item;
use Drupal\uc_order\OrderInterface;
use Drupal\uc_payment\PaymentMethodPluginBase;

/**
 * Defines a generic payment method.
 *
 * @UbercartPaymentMethod(
 *   id = "bitpaycheckout",
 *   name = @Translation("Bitpay"),
 * )
 */
class Bitpay extends PaymentMethodPluginBase
{

    /**
     * {@inheritdoc}
     */
    public function orderView(OrderInterface $order)
    {

        #if ($description = db_query('SELECT description FROM {uc_bitpaycheckout} WHERE order_id = :id', [':id' => $order->id()])->fetchField()) {
        #   return array('#markup' => $this->t('Description: @desc', ['@desc' => $description]));
        # }
    }

    /**
     * {@inheritdoc}
     */
    public function orderEditDetails(OrderInterface $order)
    {

        $form['description'] = array(
            '#type' => 'textfield',
            '#title' => $this->t('Description'),
            '#default_value' => isset($order->payment_details['description']) ? $order->payment_details['description'] : '',
            '#size' => 32,
            '#maxlength' => 64,
        );
        return $form;
    }

    /**
     * {@inheritdoc}
     */
    public function orderLoad(OrderInterface $order)
    {
        #error_log("gggggg");
        # $description = '';
        #$description = db_query('SELECT description FROM {uc_bitpaycheckout} WHERE order_id = :id', [':id' => $order->id()])->fetchField();
        # if (isset($description)) {
        #   $order->payment_details['description'] = $description;
        # }
    }

    /**
     * {@inheritdoc}
     */

    public function isSecure()
    {
        return
            (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
            || $_SERVER['SERVER_PORT'] == 443;
    }

    public function orderSave(OrderInterface $order)
    {

        $http_host = $_SERVER['HTTP_HOST'];
        if ($this->isSecure()):
          $http_host = 'https://'.$http_host;
        else:
          $http_host = 'http://'.$http_host;
        endif;

        if ($_SERVER['REQUEST_URI'] == '/cart/checkout/complete' && strpos($_SERVER['HTTP_REFERER'], '/cart/checkout/review') != '') {

            $env_setting = \Drupal::config('bitpaycheckout.adminsettings')->get('bitpaycheckout_environment');

            if ($env_setting == 1):
                $env = 'prod';
                $bitpay_checkout_token = \Drupal::config('bitpaycheckout.adminsettings')->get('bitpaycheckout_prodtoken');
            else:
                $env = 'test';
                $bitpay_checkout_token = \Drupal::config('bitpaycheckout.adminsettings')->get('bitpaycheckout_devtoken');
            endif;

            $config = new BPC_Configuration($bitpay_checkout_token, $env);
            $params = new \stdClass();
            $params->extendedNotifications = 'true';
            $params->extension_version = 'BitPay_Checkout_Ubercart_3.0';

            $result = db_query("SELECT * FROM {uc_orders} WHERE order_id = '" . $order->id() . "'");

            foreach ($result as $order_row) {

            }

            $params->orderId = trim($order_row->order_id);
            $params->price = $order_row->order_total;
            $params->currency = $order_row->currency; //set as needed

            $params->redirectURL = $http_host . '/user/' . $order_row->uid . '/orders/' . $order_row->order_id;

            if ($order_row->primary_email != ''):
                $buyerInfo = new \stdClass();
                $buyerInfo->name = $order_row->billing_first_name . ' ' . $order_row->billing_last_name;
                $buyerInfo->email = $order_row->primary_email;
                $params->buyer = $buyerInfo;
            endif;

            $item = new BPC_Item($config, $params);

            $invoice = new BPC_Invoice($item);
            //this creates the invoice with all of the config params from the item
            $invoice->BPC_createInvoice();

            $invoiceData = json_decode($invoice->BPC_getInvoiceData());
            //now we have to append the invoice transaction id for the callback verification
            $invoiceID = $invoiceData->data->id;

            #redirect
            header("Location: " . $invoice->BPC_getInvoiceURL());
            die();

        }
        /*
    if (empty($order->payment_details['description'])) {
    db_delete('uc_bitpaycheckout')
    ->condition('order_id', $order->id())
    ->execute();
    $order->payment_details['description'] = 'BitPay Checkout Order';
    }
    else {
    db_merge('uc_bitpaycheckout')
    ->key(array(
    'order_id' => $order->id(),
    ))
    ->fields(array(
    'description' => $order->payment_details['description'],
    ))
    ->execute();
    }
     */

    }

}
