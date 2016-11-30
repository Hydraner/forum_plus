<?php

namespace Drupal\forum_plus\Form;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityForm;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Entity\Query\QueryFactory;
use Drupal\Core\Form\FormStateInterface;

class RankingForm extends EntityForm {

  /**
  * @param \Drupal\Core\Entity\Query\QueryFactory $entity_query
  *   The entity query.
  */
  public function __construct(QueryFactory $entity_query) {
    $this->entityQuery = $entity_query;
  }

  /**
  * {@inheritdoc}
  */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('entity.query')
    );
  }

  /**
  * {@inheritdoc}
  */
  public function form(array $form, FormStateInterface $form_state) {
    $form = parent::form($form, $form_state);

    $ranking = $this->entity;

    $form['label'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Label'),
      '#maxlength' => 255,
      '#default_value' => $ranking->label(),
      '#description' => $this->t("Label for the Ranking."),
      '#required' => TRUE,
    ];

    $form['start'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Start'),
      '#default_value' => 0,
      '#description' => $this->t("Start value (Post Count)."),
      '#required' => TRUE,
    ];

    $form['end'] = [
      '#type' => 'textfield',
      '#title' => $this->t('End'),
      '#default_value' => 100,
      '#description' => $this->t("End value (Post Count)."),
      '#required' => TRUE,
    ];

    $form['id'] = [
        '#type' => 'machine_name',
        '#default_value' => $ranking->id(),
        '#machine_name' => [
          'exists' => [$this, 'exist'],
        ],
        '#disabled' => !$ranking->isNew(),
    ];

    return $form;
  }

  /**
  * {@inheritdoc}
  */
  public function save(array $form, FormStateInterface $form_state) {
    $ranking = $this->entity;
    $status = $ranking->save();

    if ($status) {
      drupal_set_message($this->t('Saved the %label Ranking.', [
      '%label' => $ranking->label(),
      ]));
    }
    else {
      drupal_set_message($this->t('The %label Ranking was not saved.', [
      '%label' => $ranking->label(),
      ]));
    }

    $form_state->setRedirect('entity.ranking.collection');
  }

  public function exist($id) {
    $entity = $this->entityQuery->get('ranking')
      ->condition('id', $id)
      ->execute();
    return (bool) $entity;
  }
}
