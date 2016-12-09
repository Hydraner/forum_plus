<?php

namespace Drupal\forum_plus;

use Drupal\comment\CommentManagerInterface;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Database\Connection;
use Drupal\Core\Entity\EntityManagerInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\StringTranslation\TranslationInterface;
use Drupal\forum\ForumManager;

/**
 * Provides forum manager service.
 */
class ForumPlusManager extends ForumManager implements ForumPlusManagerInterface {

  /**
   * The entity_type manager service.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * Constructs the forum manager service.
   *
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   The config factory service.
   * @param \Drupal\Core\Entity\EntityManagerInterface $entity_manager
   *   The entity manager service.
   * @param \Drupal\Core\Database\Connection $connection
   *   The current database connection.
   * @param \Drupal\Core\StringTranslation\TranslationInterface $string_translation
   *   The translation manager service.
   * @param \Drupal\comment\CommentManagerInterface $comment_manager
   *   The comment manager service.
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The entity_type manager service.
   */
  public function __construct(
    ConfigFactoryInterface $config_factory,
    EntityManagerInterface $entity_manager,
    Connection $connection,
    TranslationInterface $string_translation,
    CommentManagerInterface $comment_manager,
    EntityTypeManagerInterface $entity_type_manager
  ) {
    parent::__construct(
      $config_factory,
      $entity_manager,
      $connection,
      $string_translation,
      $comment_manager
    );

    $this->entityTypeManager = $entity_type_manager;
  }

  /**
   * Get count of posts.
   *
   * @param int $tid
   *   The term_id of the forum.
   * @return int|string
   *   The number of posts if not a container.
   */
  public function getPostCount($tid) {
    $statistics = $this->getForumStatistics($tid);
    if (!$this->isContainer($tid)) {
      return isset($statistics->topic_count) ? $statistics->topic_count + $statistics->comment_count : 0;
    }
    return '';
  }

  /**
   * Get count of topics.
   *
   * @param int $tid
   *   The term_id of the topic.
   * @return int|string
   *   The number of topics if not a container.
   */
  public function getTopicCount($tid) {
    $statistics = $this->getForumStatistics($tid);
    if (!$this->isContainer($tid)) {
      return isset($statistics->topic_count) ? $statistics->topic_count : 0;
    }
    return '';
  }

  /**
   * Check if a forum is a container.
   *
   * @param int $tid
   *   The taxonomy_term id.
   * @return boolean.
   *   TRUE if it is a container.
   */
  public function isContainer($tid) {
    $forum = $this->entityTypeManager->getStorage('group')->load($tid);
    return $forum->get('forum_container')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function getLastPost($tid) {
    return parent::getLastPost($tid);
  }

  /**
   * {@inheritdoc}
   *
   * @todo: We should think about replacing this later with either a caching
   *        mechanism or a separate table. The difference lies in updating the
   *        table via node update hook or purging the cache on the same
   *        occasion.
   */
  public function getForumStatistics($tid) {
    return parent::getForumStatistics($tid);
  }

  /**
   * {@inheritdoc}
   */
  public function lastVisit($tid, AccountInterface $account) {
    return parent::lastVisit($tid, $account);
  }

  /**
   * {@inheritdoc}
   */
  public function getParents($id, $parents = []) {
    $group = $this->entityManager->getStorage('group')->load($id);
    // Add current forum.
    if ($id == $group->id()) {
      $parents[$id] = $group;
    }
    // Iterate through parent forums.
    if ($id = $group->get('field_forum_parent')->target_id) {
      $parents[$id] = $this->entityManager->getStorage('group')->load($id);
      return $this->getParents($id, $parents);
    }

    return $parents;
  }

}
