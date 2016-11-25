<?php

namespace Drupal\forum_plus\Plugin\views\style;

use Drupal\Component\Utility\Html;
use Drupal\Core\Cache\CacheableDependencyInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\views\Plugin\views\style\Table;
use Drupal\views\Plugin\views\wizard\WizardInterface;

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
   * Render the display in this style.
   */
  public function render() {
    $results = $this->view->result;

    // @todo: Resort.
    $tree = $this->buildTree($results);
    $this->view->result = $tree;

    return parent::render();
  }

  private function buildTree($items, $parentId = 0, &$result = [], $depth = 0) {
    // @todo: Make this configurable.
    $parent_field_name = 'taxonomy_term_field_data_taxonomy_term_hierarchy_tid';
    foreach ($items as $key => $value) {
      if ($value->{$parent_field_name} == $parentId) {

        // Save the depth to the result, to be able to add indent in the
        // template.
        $value->depth = $depth;
        array_push($result, $value);

        unset($items[$key]);

        $oldParent = $parentId;
        $parentId = $value->tid;
        $old_depth = $depth;
        $depth = $depth+1;
        $this->buildTree($items, $parentId, $result, $depth);
        $depth = $old_depth;
        $parentId = $oldParent;
      }
    }


    return $result;
  }

}
