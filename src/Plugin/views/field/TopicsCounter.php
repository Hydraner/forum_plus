<?php

namespace Drupal\forum_plus\Plugin\views\field;

use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Url;
use Drupal\forum_plus\ForumPlusManagerInterface;
use Drupal\views\Plugin\views\field\FieldPluginBase;
use Drupal\views\ResultRow;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * A handler to count topics.
 *
 * @ingroup views_field_handlers
 *
 * @ViewsField("topics_counter")
 */
class TopicsCounter extends FieldPluginBase {

  /**
   * The forum manager service.
   *
   * @var \Drupal\forum_plus\ForumPlusManagerInterface
   */
  protected $forumPlusManager;

  /**
   * Current user.
   *
   * @var \Drupal\Core\Session\AccountInterface
   */
  protected $currentUser;

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
   *   The forum manager service.
   * @param AccountInterface $current_user
   *   Current user.
   */
  public function __construct(
    array $configuration,
    $plugin_id,
    $plugin_definition,
    ForumPlusManagerInterface $forum_plus_manager,
    AccountInterface $current_user
  ) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);

    $this->forumPlusManager = $forum_plus_manager;
    $this->currentUser = $current_user;
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
      $container->get('forum_plus_manager'),
      $container->get('current_user')
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
    $output = $this->forumPlusManager->getTopicCount($values->tid);

    $forum = $values->_entity;
    if ($this->currentUser->isAuthenticated()) {
      $new_topics = $this->forumPlusManager->unreadTopics($forum->id(), $this->currentUser->id());
      if ($new_topics) {
        $url = new Url('forum.page', ['taxonomy_term' => $forum->id()], ['fragment' => 'new']);
        $link_title = $this->formatPlural(
          $this->forumPlusManager->getTopicCount($values->tid),
          '1 new post<span class="visually-hidden"> in forum %title</span>',
          '@count new posts<span class="visually-hidden"> in forum %title</span>',
          ['%title' => $values->_entity->label()]
        );
        $output .= $this->linkGenerator()->generate($link_title, $url);
      }
    }

    return ['#markup' => $output];
  }

}
