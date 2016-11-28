<?php

namespace Drupal\forum_plus\Plugin\views\style;

use Drupal\views\Plugin\views\style\Table;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Routing\RouteMatchInterface;

/**
 * Style plugin to render each item as a row in a table.
 *
 * @ingroup views_style_plugins
 *
 * @ViewsStyle(
 *   id = "forum_table",
 *   title = @Translation("Forum table"),
 *   help = @Translation("Displays rows in a table."),
 *   theme = "forum_plus_forum_table",
 *   display_types = {"normal"}
 * )
 */
class ForumTable extends Table {

  /**
   * The route match service.
   *
   * @var \Drupal\Core\Routing\RouteMatchInterface $routeMatch
   */
  protected $routeMatch;

  /**
   * Constructs a PluginBase object.
   *
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin_id for the plugin instance.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   * @param \Drupal\Core\Routing\RouteMatchInterface $route_match
   *   Current route match service.
   */
  public function __construct(
    array $configuration,
    $plugin_id,
    $plugin_definition,
    RouteMatchInterface $route_match
  ) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);

    $this->routeMatch = $route_match;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(
    ContainerInterface $container,
    array $configuration,
    $plugin_id,
    $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('current_route_match')
    );
  }

  /**
   * Reorder the views results dependent on the parent id.
   *
   * {@inheritdoc}
   */
  public function preRender($result) {
    $parent_id = 0;
    if (!empty($this->routeMatch->getRawParameter('taxonomy_term'))) {
      $parent_id = $this->routeMatch->getRawParameter('taxonomy_term');
    }

    $this->view->result = $this->buildTree($result, $parent_id);

    parent::preRender($result);
  }

  /**
   * Helper method to build a term tree out of the nested containers.
   *
   * The function will recursively walk through the items and reorder them,
   * depended on the parent id they belong to. Additionally it stores the items
   * depth, in order to be able to add some visual feedback to the depth later
   * on.
   *
   * @param $items
   *   The items to sort.
   * @param int $parent_id
   *   The parent_id to start with grouping.
   * @param array $result
   *   The grouped items.
   * @param int $depth
   *   An indicator which stores the depth of the nested item in the flat result
   *   list.
   * @return array $results
   *   The sorted items.
   */
  private function buildTree($items, $parent_id = 0, &$result = [], $depth = 0) {
    // @todo: Make this configurable.
    $parent_field_name = 'taxonomy_term_field_data_taxonomy_term_hierarchy_tid';
    foreach ($items as $key => $value) {
      if ($value->{$parent_field_name} == $parent_id) {

        // Save the depth to the result, to be able to add indent in the
        // template.
        $value->depth = $depth;
        array_push($result, $value);

        unset($items[$key]);

        $oldParent = $parent_id;
        $parent_id = $value->tid;
        $old_depth = $depth;
        $depth = $depth+1;
        $this->buildTree($items, $parent_id, $result, $depth);
        $depth = $old_depth;
        $parent_id = $oldParent;
      }
    }

    return $result;
  }

}
