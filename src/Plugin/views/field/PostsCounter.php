<?php

namespace Drupal\forum_plus\Plugin\views\field;

use Drupal\forum_plus\ForumPlusManagerInterface;
use Drupal\views\Plugin\views\field\FieldPluginBase;
use Drupal\views\ResultRow;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * A handler to count posts.
 *
 * @ingroup views_field_handlers
 *
 * @ViewsField("posts_counter")
 */
class PostsCounter extends FieldPluginBase {

  /**
   * The forum plus manager service.
   *
   * @var \Drupal\forum_plus\ForumPlusManagerInterface
   */
  protected $forumPlusManager;

  /**
   * Constructs a PluginBase object.
   *
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin_id for the plugin instance.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   * @param \Drupal\forum_plus\ForumPlusManagerInterface $forum_plus_manager
   *   The forum plus manager service.
   */
  public function __construct(
    array $configuration,
    $plugin_id,
    $plugin_definition,
    ForumPlusManagerInterface $forum_plus_manager
  ) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);

    $this->forumPlusManager = $forum_plus_manager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(
    ContainerInterface $container,
    array $configuration,
    $plugin_id,
    $plugin_definition
  ) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('forum_plus_manager')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function query() {
    // do nothing -- to override the parent query.
  }

  /**
   * {@inheritdoc}
   */
  public function render(ResultRow $values) {
    return $this->forumPlusManager->getPostCount($values->tid);
  }

}
