<?php

namespace Drupal\forum_plus\Routing;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Render\BubbleableMetadata;
use Drupal\Core\RouteProcessor\OutboundRouteProcessorInterface;
use Symfony\Component\Routing\Route;

/**
 * Custom RouteProcessor.
 *
 * This implements a RouteProcessor dealing with the broken state of forum links.
 * This class is taken from the not yet applied patch 2010132-forum_uri_2.patch.
 *
 * @see: Pathauto Issue: #2680503 - Comment: #11057861
 *  https://www.drupal.org/node/2680503#comment-11057861
 * @see: Forum Issue: #2010132 https://www.drupal.org/node/2010132
 *
 * @todo: Remove this once a solution in the forum module has been found.
 */
class RouteProcessor implements OutboundRouteProcessorInterface {

  /**
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  protected $configFactory;

  /**
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * RouteProcessor constructor.
   *
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   The config factory.
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The entity type manager.
   */
  function __construct(
    ConfigFactoryInterface $config_factory,
    EntityTypeManagerInterface $entity_type_manager
  ) {
    $this->configFactory = $config_factory;
    $this->entityTypeManager = $entity_type_manager;
  }

  /**
   * {@inheritdoc}
   */
  public function processOutbound(
    $route_name, Route $route,
    array &$parameters,
    BubbleableMetadata $bubbleable_metadata = NULL
  ) {
    if ($route_name == 'entity.group.canonical' && !empty($parameters['group'])) {
      if ($gid = $this->configFactory->get('forum.settings')->get('vocabulary')) {
        $group = $this->entityTypeManager->getStorage('group')->load($parameters['group']);
        if (!empty($group) && $group->bundle() == $gid) {
          $route->setPath('/forum/{group}');
        }
      }
    }
  }
}
