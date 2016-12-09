<?php

namespace Drupal\forum_plus;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Entity\EntityManagerInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\forum\ForumSettingsForm;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Configure forum settings for this site.
 */
class ForumPlusSettingsForm extends ForumSettingsForm {

  /**
   * The entity manager.
   *
   * @var \Drupal\Core\Entity\EntityManagerInterface
   */
  protected $entityManager;

  /**
   * Constructs a \Drupal\system\ConfigFormBase object.
   *
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   The factory for configuration objects.
   * @param \Drupal\Core\Entity\EntityManagerInterface $entity_manager
   *   The entity manager.
   */
  public function __construct(ConfigFactoryInterface $config_factory, EntityManagerInterface $entity_manager) {
    $this->setConfigFactory($config_factory);
    $this->entityManager = $entity_manager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('config.factory'),
      $container->get('entity.manager')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form = parent::buildForm($form, $form_state);
    $config = $this->config('forum.settings');

    $options = [];
    foreach ($this->entityManager->getBundleInfo('group') as $bundle_key => $bundle) {
      $options[$bundle_key] = $bundle['label'];
    }
    // Add a setting to choose from a group type for the forum. This setting
    // is sadly used all over the place, so we need to make sure it exists.
    $form['group_type'] = array(
      '#type' => 'select',
      '#title' => $this->t('Group type'),
      '#default_value' => $config->get('topics.vocabulary'),
      '#options' => $options,
      '#description' => $this->t('Group type that is used as a forum.'),
    );

    // For now, we don't want the default settings.
    unset($form['forum_hot_topic']);
    unset($form['forum_per_page']);
    unset($form['forum_order']);

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $this->config('forum.settings')
      ->set('vocabulary', $form_state->getValue('group_type'))
      ->save();

    parent::submitForm($form, $form_state);
  }

}
