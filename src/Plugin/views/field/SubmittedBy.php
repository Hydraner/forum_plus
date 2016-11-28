<?php

namespace Drupal\forum_plus\Plugin\views\field;

use Drupal\Component\Plugin\PluginManagerInterface;
use Drupal\forum_plus\ForumPlusManagerInterface;
use Drupal\views\Plugin\views\field\FieldPluginBase;
use Drupal\views\ResultRow;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * A handler to show who did the last reply in a topic.
 *
 * @ingroup views_field_handlers
 *
 * @ViewsField("submitted_by")
 */
class SubmittedBy extends FieldPluginBase {

  /**
   * {@inheritdoc}
   */
  public function query() {
    $this->ensureMyTable();

    // Define properties.
    $this->uid = $this->query->addField($this->tableAlias, 'uid');
    $this->created = $this->query->addField($this->tableAlias, 'created');
  }

  /**
   * {@inheritdoc}
   */
  public function render(ResultRow $values) {
    // Return similar object definition as provided by getLastPost.
    // This allows us to render using the forum_submitted theme function.
    return [
      '#theme' => 'forum_submitted',
      '#topic' => (object) [
        'created' => $values->{$this->tableAlias . '_created'},
        'uid' => $values->{$this->tableAlias . '_uid'},
      ],
    ];
  }

}
