<?php

namespace NuvoleWeb\Drupal\Driver\Objects\Drupal7;

use NuvoleWeb\Drupal\Driver\Objects\StateInterface;
use NuvoleWeb\Drupal\Driver\Cores\Drupal7 as Drupal;

/**
 * Class State
 *
 * @package NuvoleWeb\Drupal\Driver\Objects\Drupal7
 */
class State implements StateInterface {

  /**
   * {@inheritdoc}
   */
  public function get($key, $default = NULL) {
    // Code from Drupal 8.
    //Drupal::state()->get($key, $default);

    // Convert same variable name to appropriate Drupal 7 names.
    switch ($key) {
      case 'system.test_mail_collector':
        $key = 'drupal_test_email_collector';
    }

    // We need directly to access database due the cached values.
    // See comment #1 at https://www.drupal.org/project/drupalextension/issues/1935598
    $value = db_query('SELECT value FROM {variable} WHERE name=:name', array(':name' => $key))
      ->fetchField();

    if (!empty($value)) {
      $variable = unserialize($value);

      return $variable;
    }
    else {
      return variable_get($key, $default);
    }
  }

  /**
   * {@inheritdoc}
   */
  public function set($key, $value) {
    // Code from Drupal 8.
    //Drupal::state()->set($key, $value);

    // Convert same variable name to appropriate Drupal 7 names.
    switch ($key) {
      case 'system.test_mail_collector':
        $key = 'drupal_test_email_collector';
    }
    variable_set($key, $value);
  }

  /**
   * {@inheritdoc}
   */
  public function delete($key) {
    //Drupal::state()->delete($key);
    variable_del($key);
  }

  /**
   * {@inheritdoc}
   */
  public function resetCache() {
    drupal_flush_all_caches();
  }

}
