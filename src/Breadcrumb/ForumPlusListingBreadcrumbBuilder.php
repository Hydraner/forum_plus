<?php

namespace Drupal\forum_plus\Breadcrumb;

use Drupal\Core\Link;
use Drupal\Core\Routing\RouteMatchInterface;

/**
 * Provides a breadcrumb builder base class for forum listing pages.
 */
class ForumPlusListingBreadcrumbBuilder extends ForumPlusBreadcrumbBuilderBase {

  /**
   * {@inheritdoc}
   */
  public function applies(RouteMatchInterface $route_match) {
    return $route_match->getRouteName() == 'forum.page' && $route_match->getParameter('group');
  }

  /**
   * {@inheritdoc}
   */
  public function build(RouteMatchInterface $route_match) {
    $breadcrumb = parent::build($route_match);
    $breadcrumb->addCacheContexts(['route']);

    // Add all parent forums to breadcrumbs.
    /** @var \Drupal\Taxonomy\TermInterface $term */
    $group = $route_match->getParameter('group');
    $group_id = $group->id();
    $breadcrumb->addCacheableDependency($group);

    $parents = $this->forumPlusManager->getParents($group_id);
    if ($parents) {
      foreach (array_reverse($parents) as $parent) {
        if ($parent->id() != $group_id) {
          $breadcrumb->addCacheableDependency($parent);
          $breadcrumb->addLink(Link::createFromRoute($parent->label(), 'forum.page', [
            'group' => $parent->id(),
          ]));
        }
      }
    }

    return $breadcrumb;
  }

}
