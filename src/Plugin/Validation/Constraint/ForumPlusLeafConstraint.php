<?php

namespace Drupal\forum_plus\Plugin\Validation\Constraint;

use Drupal\forum\Plugin\Validation\Constraint\ForumLeafConstraint;
use Symfony\Component\Validator\Constraint;

/**
 * Checks that the node is assigned only a "leaf" term in the forum taxonomy.
 *
 * @Constraint(
 *   id = "ForumPlusLeaf",
 *   label = @Translation("Forum leaf", context = "Validation"),
 * )
 */
class ForumPlusLeafConstraint extends ForumLeafConstraint {
}
