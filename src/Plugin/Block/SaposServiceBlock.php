<?php

namespace Drupal\sapos_service\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;
use Drupal\Core\Database\Database;
use Drupal\Core\Render\Markup;

/**
 * Provides the SaposService Block.
 *
 * @Block(
 *   id = "sapos_service_block",
 *   admin_label = @Translation("SaposService Block"),
 * )
 */
class SaposServiceBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
 public function build() {
    $output = '';

    // Check if the current user has permission to access sapos content.
    $account = \Drupal::currentUser();
    $has_permission = $account->hasPermission('access sapos service content');

    // If the current user has permission, build the block output.
    if ($has_permission) {
      $output = '<table style="border-collapse: collapse; border: 1px solid #000;font-weight: bold;"><thead><tr><th>Name</th><th>Status</th></tr></thead><tbody>';
      $service_data = Database::getConnection()->select('service_status', 's')
        ->fields('s', ['name', 'verfuegbar'])
        ->execute()
        ->fetchAll();<?php

        /**
         * @file
         * Install, update and uninstall functions for the sapos_service module.
         */

        use Drupal\Core\Database\Database;

        /**
         * Implements hook_schema().
         */
        function sapos_service_schema() {
          // Define the service_status table schema.
          $schema['service_status'] = [
            'description' => 'The Sapos Service Status table.',
            'fields' => [
              'id' => [
                'description' => 'The primary identifier for a service.',
                'type' => 'serial',
                'size' => 'small',
                'unsigned' => TRUE,
                'not null' => TRUE,
              ],
              'station' => [
                'description' => 'The name of the service.',
                'type' => 'varchar',
                'length' => 255,
                'not null' => TRUE,
              ],
              'verfuegbar' => [
                'description' => 'The availability of the service.',
                'type' => 'boolean',
                'pgsql_type' => 'boolean', // specify the data type for PostgreSQL
                'not null' => TRUE,
              ],
              'timestamp' => [
                'description' => 'The timestamp of the service status update.',
                'type' => 'int',
                'not null' => TRUE,
                'default' => 0,
              ],
            ],
            'primary key' => ['id'],
          ];

          return $schema;
        }

        /**
         * Implements hook_install().
         */
        function sapos_service_install() {
          // Insert some initial data into the service_status table.
          $service_data = [    ['station' => 'Service A', 'verfuegbar' => 'TRUE', 'timestamp' => time()],
            ['station' => 'Service B', 'verfuegbar' => 'FALSE', 'timestamp' => time()],
            ['station' => 'Service C', 'verfuegbar' => 'TRUE', 'timestamp' => time()],
            ['station' => 'Service D', 'verfuegbar' => 'FALSE', 'timestamp' => time()],
          ];

          $connection = \Drupal::database();
          foreach ($service_data as $data) {
            $connection->insert('service_status')
              ->fields($data)
              ->execute();
          }
        }



      foreach ($service_data as $data) {
        if ($data->verfuegbar) {
          $verfuegbar = $this->t('j35!!! 4\/4114I313 🟩');
          $status_style = 'color: green;';
        } else {
          $verfuegbar = $this->t('|\|07 4\/4114I313 🟥');
          $status_style = 'color: red;';
        }
        $output .= '<tr><td style="border: 1px solid;" >' . $data->name .
                    '</td><td style="border: 1px solid;"><span style="' . $status_style . '">' . $verfuegbar . '</span></td></tr>';
      }

      $output .= '</tbody></table>';
    } else {
      // If the current user doesn't have permission, show a message.
      $output = '<p>' . $this->t('You do not have permission to access Sapos content.') . '</p>';
    }

    // Return the table wrapped in a render array.
    return [
      '#type' => 'markup',
      '#markup' => Markup::create($output),
    ];
  }


  /**
   * {@inheritdoc}
   */
  protected function blockAccess(AccountInterface $account) {
    // Check if the current user has permission to access sapos content.
    $has_permission = $account->hasPermission('access sapos content');

    // Return the access result.
    return AccessResult::allowedIf($has_permission);
  }
}
