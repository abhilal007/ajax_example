<?php

/**
 * @file
 * Contains \Drupal\ajax_example\Controller\AjaxExampleController.
 */

namespace Drupal\ajax_example\Controller;

use \Drupal\Core\Url;

/**
 * Controller routines for block example routes.
 */
class AjaxExampleController {

  /**
   * A simple controller method to explain what the block example is about.
   */
  public function description() {
    $output['intro']['#markup'] = t('The AJAX example module provides many examples of AJAX including forms, links, and AJAX commands.');
    $output['list']['#theme'] = 'item_list';
    $output['list']['#items'][] = \Drupal::l(t('Simplest AJAX Example'), Url::fromRoute('ajax_example.simplest'));
    return $output;
  }

}
