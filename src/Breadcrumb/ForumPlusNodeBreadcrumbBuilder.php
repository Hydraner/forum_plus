<?php

namespace Drupal\forum_plus\Breadcrumb;

use Drupal\Core\Link;
use Drupal\Core\Routing\RouteMatchInterface;

/**
 * Breadcrumb builder for forum nodes.
 */
class ForumPlusNodeBreadcrumbBuilder extends ForumPlusBreadcrumbBuilderBase {

  /**
   * {@inheritdoc}
   */
  public function applies(RouteMatchInterface $route_match) {
    return $route_match->getRouteName() == 'entity.node.canonical'
    && $route_match->getParameter('node')
    && $this->forumPlusManager->checkNodeType($route_match->getParameter('node'));
  }

  /**
   * {@inheritdoc}
   */
  public function build(RouteMatchInterface $route_match) {
    $breadcrumb = parent::build($route_match);
    $breadcrumb->addCacheContexts(['route']);

    $parents = $this->forumPlusManager->getParents($route_match->getParameter('node')->forum_tid);
    if ($parents) {
      $parents = array_reverse($parents);
      foreach ($parents as $parent) {
        $breadcrumb->addCacheableDependency($parent);
        $breadcrumb->addLink(Link::createFromRoute($parent->label(), 'forum.page',
          array(
            'group' => $parent->id(),
          )
        ));
      }
    }

    return $breadcrumb;
  }

}
