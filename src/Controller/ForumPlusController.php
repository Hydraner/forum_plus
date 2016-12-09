<?php

namespace Drupal\forum_plus\Controller;

use Drupal\Core\Cache\Cache;
use Drupal\Core\Url;
use Drupal\forum\Controller\ForumController;
use Drupal\group\Entity\GroupInterface;

class ForumPlusController extends ForumController {

  /**
   * @todo: Implement something usefull here.
   */
  public function overview() {
    return [];
  }

  /**
   * {@inheritdoc}
   */
  public function groupTitle(GroupInterface $group) {
    return $group->label();
  }

  /**
   * {@inheritdoc}
   */
  public function forumPage(GroupInterface $group) {
    $config = $this->config('forum.settings');

    return [
      'action' => $this->buildActionLinks($config->get('vocabulary'), $group),
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
//      'action' => $this->buildActionLinks($config->get('vocabulary'), $this->forumManager->getIndex()),
      'forum' => views_embed_view('forum_plus_forums', 'overview'),
      '#cache' => [
        'tags' => Cache::mergeTags($this->nodeEntityTypeDefinition->getListCacheTags(), $this->commentEntityTypeDefinition->getListCacheTags()),
      ],
    ];
  }

  /**
   * Generates an action link to display at the top of the forum listing.
   *
   * @param string $vid
   *   Group type id.
   * @param \Drupal\group\Entity\GroupInterface $group
   *   $the group.
   *
   * @return array
   *   Render array containing the links.
   */
  protected function buildActionLinks($vid, GroupInterface $group = NULL) {
    $user = $this->currentUser();

    $links = [];
    // Loop through all bundles for forum taxonomy vocabulary field.
    foreach ($this->fieldMap['node']['taxonomy_forums']['bundles'] as $type) {
      if ($this->nodeAccess->createAccess($type)) {
        $node_type = $this->nodeTypeStorage->load($type);
        $links[$type] = [
          '#attributes' => ['class' => ['action-links']],
          '#theme' => 'menu_local_action',
          '#link' => [
            'title' => $this->t('Add new @node_type', [
              '@node_type' => $this->nodeTypeStorage->load($type)->label(),
            ]),
            'url' => Url::fromRoute('node.add', ['node_type' => $type]),
          ],
          '#cache' => [
            'tags' => $node_type->getCacheTags(),
          ],
        ];
        if ($group && $group->bundle() == $vid) {
          // We are viewing a forum term (specific forum), append the tid to
          // the url.
          $links[$type]['#link']['localized_options']['query']['forum_id'] = $group->id();
        }
      }
    }
    if (empty($links)) {
      // Authenticated user does not have access to create new topics.
      if ($user->isAuthenticated()) {
        $links['disallowed'] = [
          '#markup' => $this->t('You are not allowed to post new content in the forum.'),
        ];
      }
      // Anonymous user does not have access to create new topics.
      else {
        $links['login'] = [
          '#attributes' => ['class' => ['action-links']],
          '#theme' => 'menu_local_action',
          '#link' => array(
            'title' => $this->t('Log in to post new content in the forum.'),
            'url' => Url::fromRoute('user.login', [], ['query' => $this->getDestinationArray()]),
          ),
        ];
      }
    }
    return $links;
  }

}
