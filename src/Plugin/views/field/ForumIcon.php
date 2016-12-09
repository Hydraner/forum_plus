<?php

namespace Drupal\forum_plus\Plugin\views\field;

use Drupal\Core\Session\AccountInterface;
use Drupal\forum_plus\ForumPlusManagerInterface;
use Drupal\views\Plugin\views\field\FieldPluginBase;
use Drupal\views\ResultRow;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * A handler to show an icon on forums, indicating their topic's status.
 *
 * @ingroup views_field_handlers
 *
 * @ViewsField("forum_icon")
 */
class ForumIcon extends FieldPluginBase {

  /**
   * The forum manager service.
   *
   * @var \Drupal\forum_plus\ForumPlusManagerInterface
   */
  protected $forumPlusManager;

  /**
   * The current user.
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
   * @param \Drupal\Core\Session\AccountInterface $current_user
   *   The current user.
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
    $id = $values->id;

    $new_topics = FALSE;
    if ($this->currentUser->isAuthenticated()) {
      $forum = $values->_entity;
      $new_topics = $this->forumPlusManager->unreadTopics($forum->id(), $this->currentUser->id());
    }

    return [
      '#theme' => 'forum_icon',
      '#new_posts' => $new_topics,
      '#num_posts' => $this->forumPlusManager->getPostCount($id),
      '#comment_mode' => 2,
      '#sticky' => FALSE,
      '#first_new' => TRUE,
      '#type' => 'forum',
      '#attached' => [
        'library' => ['classy/forum']
      ]
    ];
  }

}
