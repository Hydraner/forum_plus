<?php

namespace Drupal\forum_plus;

use Drupal\forum\ForumManagerInterface;

/**
 * Provides forum plus manager interface.
 */
interface ForumPlusManagerInterface extends ForumManagerInterface {

  /**
   * Provides statistics for a forum.
   *
   * @param int $tid
   *   The forum tid.
   *
   * @return \stdClass|null
   *   Statistics for the given forum if statistics exist, else NULL.
   */
  public function getForumStatistics($tid);

  /**
   * @param int $tid
   *  The forum tid.
   *
   * @return \stdClass|null
   *  Calculates post count using forum statistics.
   */
  public function getPostCount($tid);

  /**
   * @param int $tid
   *  The forum tid.
   *
   * @return \stdClass|null
   *  Calculates topic count using forum statistics.
   */
  public function getTopicCount($tid);

  /**
   * @param int $tid
   *  The forum tid.
   *
   * @return \stdClass|null
   *  Returns last post written in the forum with tid $tid.
   */
  public function getLastPost($tid);

  /**
   * @param $tid
   *  The forum tid.
   *
   * @return boolean
   *  Returns if the entity is a forum container.
   */
  public function isContainer($tid);
}
