<?php

namespace Drupal\forum_plus\Routing;

use Drupal\Core\Routing\RouteSubscriberBase;
use Drupal\Core\Routing\RoutingEvents;
use Symfony\Component\Routing\RouteCollection;

/**
 * Defines a route subscriber, listening.
 */
class RouteSubscriber extends RouteSubscriberBase {

  /**
   * Adjust forum default routes.
   *
   * @param \Symfony\Component\Routing\RouteCollection $collection
   *   The route collection.
   */
  public function alterRoutes(RouteCollection $collection) {
    // Alter the forum.index route.
    $forum_index_route = $collection->get('forum.index');
    $forum_index_route->setDefault('_controller', '\Drupal\forum_plus\Controller\ForumPlusController::forumIndex');
    // Alter the forum.page route.
    $forum_page_route = $collection->get('forum.index');
    $forum_page_route->setDefault('_controller', '\Drupal\forum_plus\Controller\ForumPlusController::forumPage');
  }

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents() {
    // Run after EntityRouteAlterSubscriber.
    $events[RoutingEvents::ALTER][] = ['onAlterRoutes', -230];
    return $events;
  }

}
