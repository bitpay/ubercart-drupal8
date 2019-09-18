<?php

namespace Drupal\uc_bitpaycheckout\Plugin\Ubercart\PaymentMethod;

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
class Bitpay extends PaymentMethodPluginBase {

  /**
   * {@inheritdoc}
   */
  public function orderView(OrderInterface $order) {
  
    #if ($description = db_query('SELECT description FROM {uc_bitpaycheckout} WHERE order_id = :id', [':id' => $order->id()])->fetchField()) {
   #   return array('#markup' => $this->t('Description: @desc', ['@desc' => $description]));
   # }
  }

  /**
   * {@inheritdoc}
   */
  public function orderEditDetails(OrderInterface $order) {
  
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
  public function orderLoad(OrderInterface $order) {
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
  public function orderSave(OrderInterface $order) {
  
    error_log("BITPAY STUFF GOES HERE");
    error_log($_SERVER['REQUEST_URI']);

    if($_SERVER['REQUEST_URI'] == '/cart/checkout/complete'){
      header("Location: http://www.google.com/");
      #invoice stuff goes here
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
