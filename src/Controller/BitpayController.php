<?php

namespace Drupal\uc_bitpaycheckout\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Provides a resource to get view modes by entity and bundle.
 *
 * @RestResource(
 *   id = "uc_bitpaycheckout",
 *   label = @Translation("Rest example"),
 *   uri_paths = {
 *     "canonical" = "/rest/api/get/node/{type}"
 *   }
 * )
 */
class BitpayController extends ControllerBase
{
    public function content()
    {
        $all_data = json_decode(file_get_contents("php://input"), true);
        $data = $all_data['data'];
        $event = $all_data['event'];

        $orderid = $data['orderId'];

        $order_status = $data['status'];
        $order_invoice = $data['id'];

        
       

        die();
    }

}
