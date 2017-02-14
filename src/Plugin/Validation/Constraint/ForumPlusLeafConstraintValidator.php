<?php

namespace Drupal\forum_plus\Plugin\Validation\Constraint;

use Drupal\forum\Plugin\Validation\Constraint\ForumLeafConstraintValidator;
use Symfony\Component\Validator\Constraint;

/**
 * Validates the ForumPlusLeaf constraint.
 */
class ForumPlusLeafConstraintValidator extends ForumLeafConstraintValidator {

  /**
   * {@inheritdoc}
   */
  public function validate($items, Constraint $constraint) {
    $item = $items->first();
    if (!isset($item)) {
      return NULL;
    }

    // Verify that a term has been selected.
    if (!$item->entity) {
      $this->context->addViolation($constraint->selectForum);
    }

    // The forum_container flag must not be set.
    if (!empty($item->entity->forum_container->value)) {
      $this->context->addViolation($constraint->noLeafMessage, array('%forum' => $item->entity->label()));
    }
  }

}
