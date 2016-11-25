<?php

namespace Drupal\forum_plus\Plugin\views\field;

use Drupal\forum_plus\ForumPlusManagerInterface;
use Drupal\views\Plugin\views\field\FieldPluginBase;
use Drupal\views\ResultRow;
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
    return 'Icon';
//    $tid = $values->tid;
//    $debug = 1;
////    $topic = \Drupal::getContainer()->get('entity_type.manager')->getStorage('taxonomy_term')->load($tid);
//
//
//
////
////    $variables['forums'][$id]->new_text = '';
////    $variables['forums'][$id]->new_url = '';
////    $variables['forums'][$id]->new_topics = 0;
////    $variables['forums'][$id]->old_topics = $forum->num_topics;
////    $variables['forums'][$id]->icon_class = 'default';
////    $variables['forums'][$id]->icon_title = t('No new posts');
////    if ($user->isAuthenticated()) {
////      $variables['forums'][$id]->new_topics = \Drupal::service('forum_manager')->unreadTopics($forum->id(), $user->id());
////      if ($variables['forums'][$id]->new_topics) {
////        $variables['forums'][$id]->new_text = \Drupal::translation()->formatPlural($variables['forums'][$id]->new_topics, '1 new post<span class="visually-hidden"> in forum %title</span>', '@count new posts<span class="visually-hidden"> in forum %title</span>', array('%title' => $variables['forums'][$id]->label()));
////        $variables['forums'][$id]->new_url = \Drupal::url('forum.page', ['taxonomy_term' => $forum->id()], ['fragment' => 'new']);
////        $variables['forums'][$id]->icon_class = 'new';
////        $variables['forums'][$id]->icon_title = t('New posts');
////      }
////      $variables['forums'][$id]->old_topics = $forum->num_topics - $variables['forums'][$id]->new_topics;
////    }
////    $forum_submitted = array('#theme' => 'forum_submitted', '#topic' => $forum->last_post);
////    $variables['forums'][$id]->last_reply = drupal_render($forum_submitted);
////
//
//    $new_topics = FALSE;
//    $user = \Drupal::currentUser();
//    $forum = $values->_entity;
//    if ($user->isAuthenticated()) {
//      $new_topics = \Drupal::service('forum_manager')->unreadTopics($forum->id(), $user->id());
//      if ($new_topics) {
//
//      }
//    }
//
//    // @todo: Make this work.
//    // @todo: Make a difference between a topic and Forum (term / node).
//    // @todo: Think about the container.
//    return [
//      '#theme' => 'forum_icon',
//      '#new_posts' => $new_topics,
//      '#num_posts' => $this->forumPlusManager->getPostCount($tid),
//      '#comment_mode' => 2,
//      '#sticky' => false,
//      '#first_new' => true,
//      '#type' => 'forum',
//      '#attached' => [
//        'library' => ['classy/forum']
//      ]
//    ];

    // @todo:
    // Node icons.
//    return [
//      '#theme' => 'forum_icon',
//      '#new_posts' => $topic->new,
//      '#num_posts' => $this->forumPlusManager->getPostCount($tid),
//      '#comment_mode' => $topic->comment_mode,
//      '#sticky' => $topic->isSticky(),
//      '#first_new' => $topic->first_new,
//    ];
  }

}
