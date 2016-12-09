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
    $forum_page_route = $collection->get('forum.page');
    $forum_page_route->setDefault('_controller', '\Drupal\forum_plus\Controller\ForumPlusController::forumPage');
    $forum_page_route->setDefault('_title_callback', '\Drupal\forum_plus\Controller\ForumPlusController::groupTitle');
    $forum_page_route->setPath('/forum/{group}');
    $forum_page_route->addOptions(['parameters' => ['group' => ['type' => 'entity:group']]]);
    // Alter the forum.settings route.
    $forum_form_route = $collection->get('forum.settings');
    $forum_form_route->setDefault('_form', '\Drupal\forum_plus\ForumPlusSettingsForm');
    // Alter the forum.settings route.
    $forum_overview_route = $collection->get('forum.overview');
    $forum_overview_route->setDefault('_controller', '\Drupal\forum_plus\Controller\ForumPlusController::overview');
  }

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents() {
    // Run after EntityRouteAlterSubscriber.
    $events[RoutingEvents::ALTER][] = ['onAlterRoutes', 0];
    return $events;
  }

}
