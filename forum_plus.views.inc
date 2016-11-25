<?php
/**
 * @file
 * Provide views data for the forum_plus module.
 */

/**
 * Implements hook_views_data_alter().
 */
function forum_plus_views_data_alter(array &$data) {
  $data['taxonomy_term_data'] += array(
    'posts_counter' => array(
      'title' => t('Posts Counter'),
      'field' => array(
        'title' => t('Posts Counter'),
        'help' => t('Counter of posts written in certain forum.'),
        'id' => 'posts_counter',
      ),
    ),
    'topics_counter' => array(
      'title' => t('Topics Counter'),
      'field' => array(
        'title' => t('Topics Counter'),
        'help' => t('Counter of topics written in certain forum.'),
        'id' => 'topics_counter',
      ),
    ),
    'last_post' => array(
      'title' => t('Last Post'),
      'field' => array(
        'title' => t('Last Post'),
        'help' => t('Last Post written in forum.'),
        'id' => 'last_post',
      ),
    ),
    'icon' => array(
      'title' => t('Icon'),
      'field' => array(
        'title' => t('Icon'),
        'help' => t('Icon which displays before a forum, container or topic.'),
        'id' => 'icon',
      ),
    ),
  );
}
