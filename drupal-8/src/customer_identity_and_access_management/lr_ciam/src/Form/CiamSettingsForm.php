<?php

/**
 * @file
 * Contains \Drupal\captcha\Form\CaptchaSettingsForm.
 */

namespace Drupal\lr_ciam\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Cache\Cache;

/**
 * Displays the ciam settings form.
 */
class CiamSettingsForm extends ConfigFormBase {


  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return ['ciam.settings'];
  }

  /**
   * Implements \Drupal\Core\Form\FormInterface::getFormID().
   */
  public function getFormId() {
    return 'ciam_settings';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('ciam.settings');
        $form['#attached']['library'][] = 'user/drupal.user.admin';
    // Configuration of which forms to protect, with what challenge.
      
    $thanks_text = t('To activate the module, you will need to first configure it (manage your desired social networks, etc.) from your LoginRadius account. If you do not have an account, click <a href="@loginradius" target="_blank">here</a>.<br/>We also offer Social Plugins for
  <a href="@wordpress" target="_blank">Wordpress</a>,
  <a href="@drupal" target="_blank">Drupal</a>,
  <a href="@joomla" target="_blank">Joomla</a>,
  <a href="@magento" target="_blank">Magento</a>,
  <a href="@prestashop" target="_blank">Prestashop</a>,
  <a href="@vbulletin" target="_blank">vBulletin</a>,
  <a href="@vanillaforum" target="_blank">VanillaForum</a> and
  <a href="@dotnetnuke" target="_blank">DotNetNuke</a>  <br/><a href="@loginradius" target="_blank"><br/><input class="form-submit" type="button" value="Set up my account!"></a> (<a href="@get_sociallogin" target="_blank">How to set up an account?</a>)', array(
          '@loginradius' => 'http://ish.re/4',
          '@wordpress' => 'http://ish.re/10E78',
          '@drupal' => 'http://ish.re/TRXK',
          '@joomla' => 'http://ish.re/12B23',
          '@magento' => 'http://ish.re/UF5L',
          '@prestashop' => 'http://ish.re/TRXU',
          '@vbulletin' => 'http://ish.re/TRXM',
          '@vanillaforum' => 'http://ish.re/TRXR',
          '@dotnetnuke' => 'http://ish.re/TRY1',
          '@get_sociallogin' => 'http://ish.re/1EVFR',
        ));
        $form['thanks_block'] = [
          '#type' => 'fieldset',
          '#title' => $this->t('Thank you for installing the LoginRadius CIAM Module!'),
          '#description' => $thanks_text,
        ];

        $form['lr_settings'] = [
      '#type' => 'details',
      '#title' => $this->t('LoginRadius API Settings'),
      '#description' => $this->t("You need to first create LoginRadius Site at LoginRadius "),
      '#open' => TRUE,
    ];

    $form['lr_settings']['sso_site_name'] = [
      '#type' => 'textfield',       
      '#title' => t('LoginRadius Site Name'),
      '#default_value' => $config->get('sso_site_name'),
      '#weight' => -10,
      '#required' => TRUE,
      '#description' => t('You can find the Site Name into your LoginRadius user account'),
    ];
    
    $form['lr_settings']['api_key'] = [
      '#type' => 'textfield',
      '#title' => $this->t('API Key'),
      '#default_value' => $config->get('api_key'),
      '#required' => TRUE,
      '#description' => $this->t('To activate the module, insert LoginRadius API Key ( <a href="http://ish.re/1EVFR" target="_blank">How to get it?</a> )'),
    ];

    $form['lr_settings']['api_secret'] = [
      '#type' => 'textfield',
      '#title' => $this->t('API Secret'),
      '#default_value' => $config->get('api_secret'),
      '#required' => TRUE,
      '#description' => $this->t('To activate the module, insert LoginRadius API Secret ( <a href="http://ish.re/1EVFR" target="_blank">How to get it?</a> )'),
    ];

    $form['lr_basic_settings'] = [
      '#type' => 'details',
      '#title' => $this->t('CIAM basic settings'),
    ];

    $form['lr_basic_settings']['username_option'] = [
      '#type' => 'radios',
      '#title' => t('How would you like username to be created? Select the username syntax as per your preference.'),
      '#default_value' => $config->get('username_option') ? $config->get('username_option') : 0,
      '#options' => [
        0 => $this->t('Firstname Lastname [Ex: John Doe]'),
        1 => $this->t('Firstname-Lastname [Ex: John-Doe]'),
        2 => $this->t('Email Address [Ex: johndoe@abc.com]'),
      ] 
    ];

    $form['lr_basic_settings']['login_redirection'] = [
      '#type' => 'radios',
      '#title' => t('Redirection settings after login'),
      '#default_value' => $config->get('login_redirection') ? $config->get('login_redirection') : 0,
      '#options' => [
        0 => $this->t('Redirect to same page of site'),
        1 => $this->t('Redirect to profile page of site'),
        2 => $this->t('Redirect to custom page of site (If you want user to be redirected to specific URL after login)'),
      ]      
    ];
    $form['lr_basic_settings']['login_redirection']['custom_login_url'] = [
      '#type' => 'textfield',
      '#weight' => 50,
      '#default_value' => $config->get('custom_login_url'),
    ];
    $form['lr_basic_settings']['register_redirection'] = [
      '#type' => 'radios',
      '#title' => t('Redirection settings after registration'),
      '#default_value' => $config->get('register_redirection') ? $config->get('register_redirection') : 0,
      '#options' => [
        0 => $this->t('Redirect to same page of site'),
        1 => $this->t('Redirect to profile page of site'),
        2 => $this->t('Redirect to custom page of site (If you want user to be redirected to specific URL after registration)'),
      ]       
    ];
    $form['lr_basic_settings']['register_redirection']['custom_register_url'] = [
      '#type' => 'textfield',
      '#weight' => 50,
      '#default_value' => $config->get('custom_register_url'),
    ];
    $form['lr_linking_settings'] = [
      '#type' => 'details',
      '#title' => $this->t('CIAM Account Linking Settings'),   
    ];
    $form['lr_linking_settings']['enable_linking'] = [
      '#type' => 'radios',
      '#title' => t('Do you want to enable social account linking at user profile page?'),
      '#default_value' => $config->get('enable_linking') ? $config->get('enable_linking') : 0,
      '#options' => [
        1 => $this->t('Yes'),
        0 => $this->t('No'),
      ]   
    ];

    $form['lr_linking_settings']['linking_text'] = [
      '#type' => 'textfield',
      '#title' => $this->t('What heading do you want to display to the users to link multiple social networks to one account?'),
      '#default_value' => $config->get('linking_text'),
      '#description' => $this->t('This text will be displayed just above social login add more identities interface.'),
    ];
    // Submit button.
    $form['actions'] = ['#type' => 'actions'];
    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => t('Save configuration'),
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    
    module_load_include('inc', 'lr_ciam');
    $data = lr_ciam_get_authentication($form_state->getValue('api_key'), $form_state->getValue('api_secret'));
    if (isset($data['status']) && $data['status'] != 'status') {
      drupal_set_message($data['message'], $data['status']);
      return FALSE;
    }
    parent::SubmitForm($form, $form_state);
    $this->config('ciam.settings')
      ->set('sso_site_name', $form_state->getValue('sso_site_name'))
      ->set('api_key', $form_state->getValue('api_key'))
      ->set('api_secret', $form_state->getValue('api_secret'))
      ->set('username_option', $form_state->getValue('username_option'))
      ->set('login_redirection', $form_state->getValue('login_redirection'))
      ->set('register_redirection', $form_state->getValue('register_redirection'))
      ->set('custom_login_url', $form_state->getValue('custom_login_url'))
      ->set('custom_register_url', $form_state->getValue('custom_register_url'))
      ->set('enable_linking', $form_state->getValue('enable_linking'))
      ->set('linking_text', $form_state->getValue('linking_text'))
      ->save();       

    //Clear page cache
    foreach (Cache::getBins() as $service_id => $cache_backend) {
      if ($service_id == 'dynamic_page_cache') {
        $cache_backend->deleteAll();
      }
    }
  }
}
