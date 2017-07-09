<?php

/**
 * @file
 * Definition of Drupal\ajax_example\Tests\AjaxExampleTest.
 */

namespace Drupal\ajax_example\Tests;

use Drupal\simpletest\WebTestBase;

/**
 * Functional tests for AJAX Example module.
 *
 * @ingroup ajax_example
 * @group ajax_example
 * @group examples
 */

class AjaxExampleTest extends WebTestBase {

  /**
   * Modules to enable.
   *
   * @var array
   */
  public static $modules = array('ajax_example');

  /**
   * Tests presence of ajax_example landing page.
   */
  public function testAjaxExamplePage() {
    $this->drupalGet('examples/ajax-example');
    $this->assertText(t('AJAX Example'), '"AJAX Example" found.');
  }

}
