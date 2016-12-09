<?php

namespace Drupal\forum_plus\Breadcrumb;

use Drupal\Core\Breadcrumb\BreadcrumbBuilderInterface;
use Drupal\Core\Breadcrumb\Breadcrumb;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Entity\EntityManagerInterface;
use Drupal\Core\Link;
use Drupal\Core\Routing\RouteMatchInterface;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\Core\StringTranslation\TranslationInterface;
use Drupal\forum_plus\ForumPlusManagerInterface;

/**
 * Provides a forum breadcrumb base class.
 *
 * This just holds the dependency-injected config, entity manager, and forum
 * manager objects.
 */
abstract class ForumPlusBreadcrumbBuilderBase implements BreadcrumbBuilderInterface {

  use StringTranslationTrait;

  /**
   * Configuration object for this builder.
   *
   * @var \Drupal\Core\Config\Config
   */
  protected $config;

  /**
   * The entity manager.
   *
   * @var \Drupal\Core\Entity\EntityManagerInterface
   */
  protected $entityManager;

  /**
   * The forum plus manager service.
   *
   * @var \Drupal\forum_plus\ForumPlusManagerInterface
   */
  protected $forumPlusManager;

  /**
   * Constructs a forum breadcrumb builder object.
   *
   * @param \Drupal\Core\Entity\EntityManagerInterface $entity_manager
   *   The entity manager.
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   The configuration factory.
   * @param \Drupal\forum_plus\ForumPlusManagerInterface $forum_plus_manager
   *   The forum plus manager service.
   * @param \Drupal\Core\StringTranslation\TranslationInterface $string_translation
   *   The string translation service.
   */
  public function __construct(EntityManagerInterface $entity_manager, ConfigFactoryInterface $config_factory, ForumPlusManagerInterface $forum_plus_manager, TranslationInterface $string_translation) {
    $this->entityManager = $entity_manager;
    $this->config = $config_factory->get('forum.settings');
    $this->forumPlusManager = $forum_plus_manager;
    $this->setStringTranslation($string_translation);
  }

  /**
   * {@inheritdoc}
   */
  public function build(RouteMatchInterface $route_match) {
    $breadcrumb = new Breadcrumb();
    $breadcrumb->addCacheContexts(['route']);

    $links[] = Link::createFromRoute($this->t('Home'), '<front>');

    $group = $this->entityManager
      ->getStorage('group_type')
      ->load($this->config->get('vocabulary'));
    $breadcrumb->addCacheableDependency($group);
    $links[] = Link::createFromRoute($group->label(), 'forum.index');

    return $breadcrumb->setLinks($links);
  }

}
