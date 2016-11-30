<?php

namespace Drupal\forum_plus\Entity;

use Drupal\Core\Config\Entity\ConfigEntityInterface;

/**
 * Provides an interface defining a Ranking entity.
 */
interface RankingInterface extends ConfigEntityInterface {
  /**
   * @return int
   *   Returns start property.
   */
  public function getStart();

  /**
   * @return int
   *  Returns end property.
   */
  public function getEnd();
}
