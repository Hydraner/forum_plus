<?php

namespace Drupal\forum_plus\Controller;

use Drupal\Core\Cache\Cache;
use Drupal\forum\Controller\ForumController;
use Drupal\taxonomy\TermInterface;

class ForumPlusController extends ForumController {

  /**
   * {@inheritdoc}
   */
  public function forumPage(TermInterface $taxonomy_term) {
    $config = $this->config('forum.settings');

    return [
      'action' => $this->buildActionLinks($config->get('vocabulary'), $taxonomy_term),
      'forum' => views_embed_view('forum_plus_topics', 'overview'),
      '#cache' => [
        'tags' => Cache::mergeTags($this->nodeEntityTypeDefinition->getListCacheTags(), $this->commentEntityTypeDefinition->getListCacheTags()),
      ],
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function forumIndex() {
    $vocabulary = $this->vocabularyStorage->load($this->config('forum.settings')->get('vocabulary'));
    $index = $this->forumManager->getIndex();
    $config = $this->config('forum.settings');

    if (empty($index->forums)) {
      $build['#title'] = $this->t('No forums defined');
    }
    else {
      $build['#title'] = $vocabulary->label();
      $this->renderer->addCacheableDependency($build, $vocabulary);
    }
    return [
      'action' => $this->buildActionLinks($config->get('vocabulary'), $this->forumManager->getIndex()),
      'forum' => views_embed_view('forum_plus_forums', 'overview'),
      '#cache' => [
        'tags' => Cache::mergeTags($this->nodeEntityTypeDefinition->getListCacheTags(), $this->commentEntityTypeDefinition->getListCacheTags()),
      ],
    ];
  }

}
