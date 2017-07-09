<?php

/**
 * @file
 * Contains \Drupal\ajax_example\Form\AjaxExampleProgressBar.
 */

namespace Drupal\ajax_example\Form;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\ChangedCommand;
use Drupal\Core\Ajax\CssCommand;
use Drupal\Core\Ajax\HtmlCommand;
use Drupal\Core\Ajax\InvokeCommand;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

class AjaxExampleProgressBar extends FormBase {

   /**
   * {@inheritdoc}
   */
  public function getFormID() {
    return 'ajax_example_progressbar';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form_state['time'] =  \Drupal::time()->getRequestTime();
  // We make a DIV which the progress bar can occupy. You can see this in use
  // in ajax_example_progressbar_callback().
  $form['status'] = array(
    '#title' => $this->t("progress-status"),
  );

  $form['submit'] = array(
    '#type' => 'submit',
    '#value' => $this->t('Submit'),
    '#ajax' => array(
      // Here we set up our AJAX callback handler.
      'callback' => 'ajax_example_progressbar_callback',
      // Tell FormAPI about our progress bar.
      'progress' => array(
        'type' => 'bar',
        'message' => $this->t('Execute..'),
        // Have the progress bar access this URL path.
        'url' => url('examples/ajax_example/progressbar/progress/' .  $form_state['time'] ),
        // The time interval for the progress bar to check for updates.
        'interval' => 1000,
      ),
    ),
  );

  return $form;

}

/**
 * Get the progress bar execution status, as JSON.
 *
 * This is the menu handler for
 * examples/ajax_example/progressbar/progress/$time.
 *
 * This function is our wholly arbitrary job that we're checking the status for.
 * In this case, we're reading a system variable that is being updated by
 * ajax_example_progressbar_callback().
 *
 * We set up the AJAX progress bar to check the status every second, so this
 * will execute about once every second.
 *
 * The progress bar JavaScript accepts two values: message and percentage. We
 * set those in an array and in the end convert it JSON for sending back to the
 * client-side JavaScript.
 *
 * @param int $time
 *   Timestamp.
 *
 * @see ajax_example_progressbar_callback()
 */
public function ajax_example_progressbar_progress($time) {
  $progress = array(
    'message' => $this->t('Starting execute...'),
    'percentage' => -1,
  );

  $completed_percentage = variable_get('example_progressbar_' . $time, 0);

  if ($completed_percentage) {
    $progress['message'] = $this->t('Executing...');
    $progress['percentage'] = $completed_percentage;
  }

  JsonResponse($progress);
}

/**
 * Our submit handler.
 *
 * This handler spends some time changing a variable and sleeping, and then
 * finally returns a form element which marks the #progress-status DIV as
 * completed.
 *
 * While this is occurring, ajax_example_progressbar_progress() will be called
 * a number of times by the client-sid JavaScript, which will poll the variable
 * being set here.
 *
 * @see ajax_example_progressbar_progress()
 */
public function ajax_example_progressbar_callback($form, &$form_state) {
  $variable_name = 'example_progressbar_' . $form_state['time'];
  $commands = array();

  variable_set($variable_name, 10);
  sleep(2);
  variable_set($variable_name, 40);
  sleep(2);
  variable_set($variable_name, 70);
  sleep(2);
  variable_set($variable_name, 90);
  sleep(2);
  variable_del($variable_name);

  $commands[] = HtmlCommand('#progress-status', $this->t('Executed.'));

  return array(
    '#type' => 'ajax',
    '#commands' => $commands,
  );
}

public function submitForm(array &$form, FormStateInterface $form_state) {
  }
}
