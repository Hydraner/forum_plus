<?php

namespace Drupal\forum_plus;

use Drupal\Core\DependencyInjection\ContainerBuilder;
use Drupal\Core\DependencyInjection\ServiceProviderBase;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Overrides the forum service to use groups instead of vocabularies.
 */
class ForumPlusServiceProvider extends ServiceProviderBase {

  /**
   * {@inheritdoc}
   */
  public function alter(ContainerBuilder $container) {
    // Alter forum.breadcrumb.node service.
    $definition = $container->getDefinition('forum.breadcrumb.node');
    $definition->setClass('Drupal\forum_plus\Breadcrumb\ForumPlusNodeBreadcrumbBuilder');
    $definition->replaceArgument(2, new Reference('forum_plus_manager'));

    // Alter forum.breadcrumb.listing service.
    $definition = $container->getDefinition('forum.breadcrumb.listing');
    $definition->setClass('Drupal\forum_plus\Breadcrumb\ForumPlusListingBreadcrumbBuilder');
    $definition->replaceArgument(2, new Reference('forum_plus_manager'));
  }

}
