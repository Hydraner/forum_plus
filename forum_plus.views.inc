<?php
/**
 * @file
 * Provide views data for the forum_plus module.
 */

/**
 * Implements hook_views_data_alter().
 */
function forum_plus_views_data_alter(array &$data) {
  $data['node'] += [
    'topic_icon' => [
      'title' => t('Icon'),
      'field' => [
        'title' => t('Icon'),
        'help' => t('Icon which displays before a topic.'),
        'id' => 'topic_icon',
      ],
    ],
  ];

  $data['comment_entity_statistics'] += [
    'last_reply' => [
      'title' => t('Last Reply'),
      'field' => [
        'title' => t('Last Reply'),
        'help' => t('Last Reply in a topic.'),
        'id' => 'last_reply',
      ],
    ],
  ];

  $data['taxonomy_term_data'] += [
    'posts_counter' => [
      'title' => t('Posts Counter'),
      'field' => [
        'title' => t('Posts Counter'),
        'help' => t('Counter of posts written in certain forum.'),
        'id' => 'posts_counter',
      ],
    ],
    'topics_counter' => [
      'title' => t('Topics Counter'),
      'field' => [
        'title' => t('Topics Counter'),
        'help' => t('Counter of topics written in certain forum.'),
        'id' => 'topics_counter',
      ],
    ],
    'last_post' => [
      'title' => t('Last Post'),
      'field' => [
        'title' => t('Last Post'),
        'help' => t('Last Post written in forum.'),
        'id' => 'last_post',
      ],
    ],
    'forum_icon' => [
      'title' => t('Icon'),
      'field' => [
        'title' => t('Icon'),
        'help' => t('Icon which displays before a forum.'),
        'id' => 'forum_icon',
      ],
    ],
  ];
}
