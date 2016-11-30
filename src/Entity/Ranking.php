<?php

namespace Drupal\forum_plus\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBase;
use Drupal\forum_plus\Entity\RankingInterface;

namespace Drupal\forum_plus\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBase;
use Drupal\forum_plus\ExampleInterface;

/**
 * Defines the Ranking entity.
 *
 * @ConfigEntityType(
 *   id = "ranking",
 *   label = @Translation("Ranking"),
 *   handlers = {
 *     "list_builder" = "Drupal\forum_plus\Controller\RankingListBuilder",
 *     "form" = {
 *       "add" = "Drupal\forum_plus\Form\RankingForm",
 *       "edit" = "Drupal\forum_plus\Form\RankingForm",
 *       "delete" = "Drupal\forum_plus\Form\RankingDeleteForm",
 *     }
 *   },
 *   config_prefix = "ranking",
 *   admin_permission = "administer site configuration",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "label",
 *     "start" = "start",
 *     "end" = "end",
 *   },
 *   links = {
 *     "edit-form" = "/admin/config/system/ranking/{ranking}",
 *     "delete-form" = "/admin/config/system/ranking/{ranking}/delete",
 *   }
 * )
 */
class Ranking extends ConfigEntityBase implements RankingInterface {

  /**
   * The Ranking ID.
   *
   * @var string
   */
  public $id;

  /**
   * The Ranking label.
   *
   * @var string
   */
  public $label;

  /**
   * The Ranking Post Count start value.
   *
   * @var integer
   */
  public $start;

  /**
   * The Ranking Post Count end value.
   *
   * @var integer
   */
  public $end;

  /**
   * @return int
   *   Returns start property.
   */
  public function getStart() {
    return $this->start;
  }

  /**
   * @return int
   *  Returns end property.
   */
  public function getEnd() {
    return $this->end;
  }
}
