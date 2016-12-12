<?php

namespace Drupal\forum_plus\Plugin\views\field;

use Drupal\Core\Session\AccountInterface;
use Drupal\forum_plus\ForumPlusManagerInterface;
use Drupal\views\Plugin\views\field\FieldPluginBase;
use Drupal\views\ResultRow;
use Drupal\comment\CommentManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;


/**
 * A handler to show an icon.
 *
 * @ingroup views_field_handlers
 *
 * @ViewsField("topic_icon")
 */
class TopicIcon extends FieldPluginBase {

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
   * The comment manager service.
   *
   * @var \Drupal\comment\CommentManagerInterface
   */
  protected $commentManager;

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
   * @param \Drupal\Core\Session\AccountInterface $current_user.
   *   The current user.
   * @param \Drupal\comment\CommentManagerInterface $comment_manager.
   *   The comment manager service.
   */
  public function __construct(
    array $configuration,
    $plugin_id,
    $plugin_definition,
    ForumPlusManagerInterface $forum_plus_manager,
    AccountInterface $current_user,
    CommentManagerInterface $comment_manager
  ) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);

    $this->forumPlusManager = $forum_plus_manager;
    $this->currentUser = $current_user;
    $this->commentManager = $comment_manager;
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
      $container->get('current_user'),
      $container->get('comment.manager')
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
    $topic = $values->_entity;

    // A forum is new if the topic is new, or if there are new comments since
    // the user's last visit.
    $topic->new = 0;
    $topic->last_comment_timestamp = $values->comment_entity_statistics_comment_count;
    $history = $this->forumPlusManager->lastVisit($topic->id(), $this->currentUser);
    $topic->new_replies = $this->commentManager->getCountNewComments($topic, 'comment_forum', $history);
    if ($this->currentUser->isAuthenticated()) {
      $topic->new = $topic->new_replies || ($topic->last_comment_timestamp > $history);
    }

    return [
      '#theme' => 'forum_icon',
      '#new_posts' => $topic->new,
      '#num_posts' => $topic->last_comment_timestamp,
      '#comment_mode' => $topic->comment_forum->status,
      '#sticky' => $topic->isSticky(),
      '#type' => 'topic',
      '#first_new' => TRUE,
      '#attached' => [
        'library' => ['classy/forum']
      ]
    ];
  }

}
