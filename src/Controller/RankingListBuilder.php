<?php

namespace Drupal\forum_plus\Controller;

use Drupal\Core\Config\Entity\ConfigEntityListBuilder;
use Drupal\Core\Entity\EntityInterface;

/**
* Provides a listing of Ranking.
*/
class RankingListBuilder extends ConfigEntityListBuilder {
  /**
  * {@inheritdoc}
  */
  public function buildHeader() {
    $header['label'] = $this->t('Ranking');
    $header['start'] = $this->t('Start');
    $header['end'] = $this->t('End');
    return $header + parent::buildHeader();
  }

  /**
  * {@inheritdoc}
  */
  public function buildRow(EntityInterface $entity) {
    $row['label'] = $this->getLabel($entity);
    $row['start'] = $entity->getStart();
    $row['end'] = $entity->getEnd();

    return $row + parent::buildRow($entity);
  }
}
