<?php

namespace Drupal\uc_bitpaycheckout\Controller;
use Drupal\uc_order\OrderInterface;
use Drupal\uc_payment\PaymentMethodPluginBase;
use Drupal\Core\Controller\ControllerBase;


class BitpayController extends ControllerBase
{
    public function content()
    {
        $all_data = json_decode(file_get_contents("php://input"), true);
        $data = $all_data['data'];

        $orderid = $data['orderId'];

        $order_status = $data['status'];
        $order_invoice = $data['id'];
        
        $result = db_query("SELECT * FROM {uc_orders} WHERE order_id = '" . $orderid . "' LIMIT 1");

        foreach ($result as $order_row) {
        #  print_r($order_row);

        }

        switch($data['status']){
          case 'paid':
          $result = db_query("UPDATE {uc_orders} SET order_status = 'pending'  WHERE order_id = '" . $orderid . "'");
          break;
          case 'confirmed':
          $result = db_query("UPDATE {uc_orders} SET order_status = 'processing'  WHERE order_id = '" . $orderid . "'");
          break;
          case 'expired':
          $result = db_query("UPDATE {uc_orders} SET order_status = 'canceled'  WHERE order_id = '" . $orderid . "'");
          break;
          case 'invalid':
          $result = db_query("UPDATE {uc_orders} SET order_status = 'canceled'  WHERE order_id = '" . $orderid . "'");
          break;
          

        }
        #clear the caches
        drupal_flush_all_caches() ;

       


        die();
    }

}
