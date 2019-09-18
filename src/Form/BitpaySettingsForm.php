<?php  
/**  
 * @file  
 * Contains Drupal\uc_bitpaycheckout\Form\BitpaySettingsForm.  
 */  
namespace Drupal\uc_bitpaycheckout\Form;  
use Drupal\Core\Form\ConfigFormBase;  
use Drupal\Core\Form\FormStateInterface;  

class BitpaySettingsForm extends ConfigFormBase {  

    /**  
   * {@inheritdoc}  
   */  
  protected function getEditableConfigNames() {  
    return [  
      'bitpaycheckout.adminsettings',  
    ];  
  }  

  /**  
   * {@inheritdoc}  
   */  
  public function getFormId() {  
    return 'bitpaycheckout';  
  } 

  public function buildForm(array $form, FormStateInterface $form_state) {  
    $config = $this->config('bitpaycheckout.adminsettings');  

    $form['bitpaycheckout_devtoken'] = [  
      '#type' => 'textfield',  
      '#title' => $this->t('Developer Token'),  
      '#description' => $this->t('If you have not created a BitPay Merchant Token, you can create one on your BitPay Dashboard.<br><a href = "https://test.bitpay.com/dashboard/merchant/api-tokens" target = "_blank">(Test)</a></p>'),  
      '#default_value' => $config->get('bitpaycheckout_devtoken'),  
    ];  

    $form['bitpaycheckout_prodtoken'] = [  
        '#type' => 'textfield',  
        '#title' => $this->t('Production Token'),  
        '#description' => $this->t('If you have not created a BitPay Merchant Token, you can create one on your BitPay Dashboard.<br><a href= "https://www.bitpay.com/dashboard/merchant/api-tokens" target = "_blank">(Production)</a> </p>'),  
        '#default_value' => $config->get('bitpaycheckout_prodtoken'),  
      ];  

      $form['bitpaycheckout']['bitpaycheckout_environment'] = array(
        '#type' => 'radios',
        '#title' => t('Are you using a Sandbox or Production?'),
        '#default_value' => $config->get('bitpaycheckout_environment', 1),
        '#options' => array(t('Sandbox'), t('Production')),
      );


    return parent::buildForm($form, $form_state);  

}  

/**  
   * {@inheritdoc}  
   */  
  public function submitForm(array &$form, FormStateInterface $form_state) {  
    parent::submitForm($form, $form_state);  

    $this->config('bitpaycheckout.adminsettings')  
      ->set('bitpaycheckout_devtoken', $form_state->getValue('bitpaycheckout_devtoken'))  
      ->set('bitpaycheckout_prodtoken', $form_state->getValue('bitpaycheckout_prodtoken'))  
      ->set('bitpaycheckout_environment', $form_state->getValue('bitpaycheckout_environment'))  
      ->save();  
  }  
}
