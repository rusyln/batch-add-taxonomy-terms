<?php

namespace Drupal\taxonomy_batch_add\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\taxonomy\Entity\Term;
use Drupal\taxonomy\TermStorageInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class BatchAddTermsForm extends FormBase {

  /**
   * The term storage.
   *
   * @var \Drupal\taxonomy\TermStorageInterface
   */
  protected $termStorage;

  /**
   * Constructs a new BatchAddTermsForm.
   *
   * @param \Drupal\taxonomy\TermStorageInterface $term_storage
   *   The term storage.
   */
  public function __construct(TermStorageInterface $term_storage) {
    $this->termStorage = $term_storage;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('entity_type.manager')->getStorage('taxonomy_term')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'taxonomy_batch_add_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    // Load all vocabularies.
    $vocabularies = \Drupal\taxonomy\Entity\Vocabulary::loadMultiple();
    $options = [];
    foreach ($vocabularies as $vocabulary) {
      $options[$vocabulary->id()] = $vocabulary->label();
    }

    // Add a dropdown to select the vocabulary.
    $form['vocabulary'] = [
      '#type' => 'select',
      '#title' => $this->t('Select Vocabulary'),
      '#options' => $options,
      '#required' => TRUE,
    ];

    // Add a textarea for entering terms.
    $form['terms'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Terms'),
      '#description' => $this->t('Enter one term per line.'),
      '#required' => TRUE,
    ];

    // Submit button.
    $form['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Add Terms'),
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    $vocabulary = $form_state->getValue('vocabulary');
    $terms = array_filter(array_map('trim', explode("\n", $form_state->getValue('terms'))));
    $existing_terms = [];

    // Fetch all existing terms in the selected vocabulary.
    $terms_in_vocabulary = $this->termStorage->loadTree($vocabulary, 0, NULL, TRUE);
    foreach ($terms_in_vocabulary as $term) {
      $existing_terms[$term->getName()] = $term->id();
    }

    $duplicates = [];
    foreach ($terms as $term) {
      if (isset($existing_terms[$term])) {
        $duplicates[] = $term;
      }
    }

    // Set error if duplicates are found.
    if (!empty($duplicates)) {
      $form_state->setErrorByName('terms', $this->t('The following terms already exist in the selected vocabulary: @terms', ['@terms' => implode(', ', $duplicates)]));
    }
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $vocabulary = $form_state->getValue('vocabulary');
    $terms = array_filter(array_map('trim', explode("\n", $form_state->getValue('terms'))));

    $batch = [
      'title' => $this->t('Adding Taxonomy Terms'),
      'operations' => [],
      'finished' => 'taxonomy_batch_add_finished',
    ];

    foreach ($terms as $term) {
      $batch['operations'][] = ['taxonomy_batch_add_process_term', [['vocabulary' => $vocabulary, 'name' => $term]]];
    }

    batch_set($batch);
  }

}
