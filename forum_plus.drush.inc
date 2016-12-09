<?php

/**
 * Implements hook_drush_command().
 */
function forum_plus_drush_command() {
  $items = [];
  $items['post-count-rebuild'] = [
    'description' => 'Rebuild forum post count (topic + comment) of users.',
    'aliases' => ['pcr'],
  ];

  return $items;
}

/**
 * @command: post-count-rebuild
 *
 * @param: none
 */
function drush_forum_plus_post_count_rebuild() {
  // Get list of all users currently in the system.
  $query = \Drupal::database()->select('users', 'u');
  $query->addField('u', 'uid');
  $result = $query->execute()->fetchAll();

  // Get Post count of each user and update statistics table.
  // Post count is the sum of topics created and comments posted.
  foreach ($result as $user) {
    // Calculate topics created count.
    $query = \Drupal::database()->select('node_field_data', 'nfd');
    $query->condition('uid', $user->uid, '=');
    $query->condition('status', 1, '=');
    $query->condition('type', 'forum', '=');
    $topic_count = $query->countQuery()->execute()->fetchField();

    // Calculate posts created count.
    $query = \Drupal::database()->select('comment_field_data', 'cfd');
    $query->condition('uid', $user->uid, '=');
    $query->condition('status', 1, '=');
    $query->condition('comment_type', 'comment_forum');
    $comment_count = $query->countQuery()->execute()->fetchField();

    // Update statistics table.
    // Insert if entry is not present yet.
    $query = \Drupal::database()->upsert('forum_user_statistics');
    $query->fields(['uid', 'post_count']);
    $query->values([$user->uid, $topic_count + $comment_count]);
    $query->key('uid');
    $query->execute();
  }
}
