<?php

namespace NuvoleWeb\Drupal\Driver\Objects\Drupal7;

use NuvoleWeb\Drupal\Driver\Objects\EditableConfigInterface;

/**
 * Class EditableConfig
 *
 * @package NuvoleWeb\Drupal\Driver\Objects\Drupal7
 */
class EditableConfig implements EditableConfigInterface {

  protected $config;
  protected $name;

  /**
   * EditableConfig constructor.
   *
   * @param string $name
   *   The config name.
   */
  public function __construct($name) {
    // Code from Drupal 8.
    //$this->config = \Drupal::configFactory()->getEditable($name);

    // Convert same variable name to appropriate Drupal 7 names.
    switch ($name) {
      case 'mailsystem.settings':
        $name = 'mail_system';
    }

    // Save configuration name for later.
    $this->name = $name;

    // Get data from database.
    $this->config = variable_get($name, array());
  }

  /**
   * {@inheritdoc}
   */
  public function get($key = '') {
    //return $this->config->get($key);
    return (!empty($this->config[$key])) ? $this->config[$key] : NULL;
  }

  /**
   * {@inheritdoc}
   */
  public function set($key, $value) {
    // Convert same variable name to appropriate Drupal 7 names.
    if ($this->name == 'mail_system') {
      if ($key == 'defaults.sender') {
        if (empty($value)) {
          $this->config['default-system'] = 'DefaultMailSystem';
        }
        elseif ($value == 'test_mail_collector') {
          $this->config['default-system'] = 'TestingMailSystem';
        }
      }
    }
    else {
      $this->config[$key] = $value;
    }

    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function setData(array $data) {
    return $this->config->setData($data);
  }

  /**
   * {@inheritdoc}
   */
  public function getData() {
    return $this->config->getRawData();
  }

  /**
   * {@inheritdoc}
   */
  public function save() {
    variable_set($this->name, $this->config);
  }

}
