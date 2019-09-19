<?php

namespace Drupal\uc_bitpaycheckout\Controller;
use Drupal\uc_order\OrderInterface;
use Drupal\uc_payment\PaymentMethodPluginBase;
use Drupal\Core\Controller\ControllerBase;


use Drupal\uc_bitpaycheckout\BPC_Configuration;
use Drupal\uc_bitpaycheckout\BPC_Invoice;
use Drupal\uc_bitpaycheckout\BPC_Item;


class BitpayController extends ControllerBase
{
    public function content()
    {
        $all_data = json_decode(file_get_contents("php://input"), true);
        $data = $all_data['data'];

        $orderid = $data['orderId'];

        $order_status = $data['status'];
        $invoiceID = $data['id'];
        
        $result = db_query("SELECT * FROM {uc_orders} WHERE order_id = '" . $orderid . "' LIMIT 1");

        foreach ($result as $order_row) {
        #  print_r($order_row);

        }

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
        $params->invoiceID = $invoiceID;

        $item = new BPC_Item($config, $params);
        

        $invoice = new BPC_Invoice($item);
       
        $orderStatus = json_decode($invoice->BPC_checkInvoiceStatus($invoiceID));
        

        switch($orderStatus->data->status){
          case 'paid':
          $result = db_query("UPDATE {uc_orders} SET order_status = 'pending'  WHERE order_id = '" . $orderid . "'");
          
          $comment = 'BitPay Invoice ID: <a target = "_blank" href = "' . $this->BPC_getBitPayDashboardLink($env, $invoiceID) . '">' . $invoiceID . '</a> is processing.';

          $comment_sql = "INSERT INTO `uc_order_admin_comments` (`comment_id`, `order_id`, `uid`, `message`, `created`) VALUES (NULL, '$orderid', '0', '$comment', '0')";
         
          $comment_result = db_query($comment_sql);


          break;
          case 'confirmed':
          $result = db_query("UPDATE {uc_orders} SET order_status = 'processing'  WHERE order_id = '" . $orderid . "'");
          
          $comment = 'BitPay Invoice ID: <a target = "_blank" href = "' . $this->BPC_getBitPayDashboardLink($env, $invoiceID) . '">' . $invoiceID . '</a> is processing.';

          $comment_sql = "INSERT INTO `uc_order_admin_comments` (`comment_id`, `order_id`, `uid`, `message`, `created`) VALUES (NULL, '$orderid', '0', '$comment', '0')";
         
          $comment_result = db_query($comment_sql);
          break;
          case 'expired':
          $result = db_query("UPDATE {uc_orders} SET order_status = 'canceled'  WHERE order_id = '" . $orderid . "'");
          
          $comment = 'BitPay Invoice ID: <a target = "_blank" href = "' . $this->BPC_getBitPayDashboardLink($env, $invoiceID) . '">' . $invoiceID . '</a> is processing.';

          $comment_sql = "INSERT INTO `uc_order_admin_comments` (`comment_id`, `order_id`, `uid`, `message`, `created`) VALUES (NULL, '$orderid', '0', '$comment', '0')";
         
          $comment_result = db_query($comment_sql);

          break;
          case 'invalid':
          $result = db_query("UPDATE {uc_orders} SET order_status = 'canceled'  WHERE order_id = '" . $orderid . "'");

          $coment = 'BitPay Invoice ID: <a target = "_blank" href = "' . $this->BPC_getBitPayDashboardLink($env, $invoiceID) . '">' . $invoiceID . '</a> is processing.';

           $comment_sql = "INSERT INTO `uc_order_admin_comments` (`comment_id`, `order_id`, `uid`, `message`, `created`) VALUES (NULL, '$orderid', '0', '$comment', '0')";
         
          $comment_result = db_query($comment_sql);
          break;
          

        }
        #clear the caches
        drupal_flush_all_caches() ;

       


        die();
    }

    public function BPC_getBitPayDashboardLink($endpoint, $invoiceID)
    { //dev or prod token
        switch ($endpoint) {
            case 'test':
            default:
                return '//test.bitpay.com/dashboard/payments/' . $invoiceID;
                break;
            case 'production':
                return '//bitpay.com/dashboard/payments/' . $invoiceID;
                break;
        }
    }

}
