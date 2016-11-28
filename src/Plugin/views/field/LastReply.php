<?php

namespace Drupal\forum_plus\Plugin\views\field;

use Drupal\Component\Plugin\PluginManagerInterface;
use Drupal\views\Plugin\views\field\FieldPluginBase;
use Drupal\views\ResultRow;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * A handler to show who did the last reply in a topic.
 *
 * @ingroup views_field_handlers
 *
 * @ViewsField("last_reply")
 */
class LastReply extends FieldPluginBase {

  /**
   * The views plugin manager join service.
   *
   * @var \Drupal\views\Plugin\views\join\JoinPluginBase
   */
  protected $viewsPluginManagerJoin;

  /**
   * Constructs a PluginBase object.
   *
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin_id for the plugin instance.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   * @param $views_plugin_manager_join
   *  The views plugin manager join Service
   */
  public function __construct(
    array $configuration,
    $plugin_id,
    $plugin_definition,
    PluginManagerInterface $views_plugin_manager_join
  ) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);

    $this->viewsPluginManagerJoin = $views_plugin_manager_join;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(
    ContainerInterface $container,
    array $configuration,
    $plugin_id,
    $plugin_definition
  ) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('plugin.manager.views.join')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function query() {
    $this->ensureMyTable();

    // Join users_field_data on comment_entity_statistics via uid.
    // This is necessary because comment_entity_statistics only holds username
    // data for anonymous users.
    $definition = [
      'table' => 'users_field_data',
      'field' => 'uid',
      'left_table' => 'comment_entity_statistics',
      'left_field' => 'last_comment_uid',
      'extra' => [
        [
          'field' => 'uid',
          'operator' => '!=',
          'value' => '0'
        ]
      ]
    ];

    $join = $this->viewsPluginManagerJoin->createInstance('standard', $definition);

    // Ensures we have the relationship.
    $this->users_data_table = $this->query->ensureTable('users_field_data', $this->relationship, $join);

    // Define properties.
    $this->uid = $this->query->addField($this->tableAlias, 'last_comment_uid');
    $this->created = $this->query->addField($this->tableAlias, 'last_comment_timestamp');
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
        'created' => $values->{$this->tableAlias . '_last_comment_timestamp'},
        'uid' => $values->{$this->tableAlias . '_last_comment_uid'},
      ],
    ];
  }

}
