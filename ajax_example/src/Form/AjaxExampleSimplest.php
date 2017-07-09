<?php

/**
 * @file
 * Contains \Drupal\ajax_example\Form\AjaxExampleSimplest.
 */

namespace Drupal\ajax_example\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

class AjaxExampleSimplest extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormID() {
    return 'ajax_example_simplest';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['changethis'] = array(
      '#title' => $this->t("Choose something and explain why"),
      '#type' => 'select',
      '#options' => array(
        'one' => 'one',
        'two' => 'two',
        'three' => 'three',
      ),
      '#ajax' => array(
        // #ajax has two required keys: callback and wrapper.
        // 'callback' is a function that will be called when this element changes.
        'callback' => '::prompt',
        // 'wrapper' is the HTML id of the page element that will be replaced.
        'wrapper' => 'replace_textfield_div',
        // There are also several optional keys - see AjaxExampleAutoCheckboxes
        // below for details on 'method', 'effect' and 'speed' and
        // AjaxExampleDependentDropDown for 'event'.
      ),
    );

    // This entire form element will be replaced whenever 'changethis' is updated.
    $form['replace_textfield'] = array(
      '#type' => 'textfield',
      '#title' => $this->t("Why"),
      // The prefix/suffix provide the div that we're replacing, named by
      // #ajax['wrapper'] above.
      '#prefix' => '<div id="replace_textfield_div">',
      '#suffix' => '</div>',
    );

    // An AJAX request calls the form builder function for every change.
    // We can change how we build the form based on $form_state.
    $value = $form_state->getValue('changethis');
    if (!empty($value)) {
      $form['replace_textfield']['#description'] = $this->t("Say why you chose '@value'", array('@value' => $value));
    }
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
  }

  /**
   * Handles switching the available regions based on the selected theme.
   */
  public function prompt($form, FormStateInterface $form_state) {
    return $form['replace_textfield'];
  }

}
