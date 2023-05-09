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
    $build['#attached']['library'][] = 'sapos_service/sapos_service_css';

    // If the current user has permission, build the block output.
    if ($has_permission) {
      $database = Database::getConnection('default', 'monitoring');
      $service_data = $database->select('service_status', 's')
        ->fields('s', ['station', 'verfuegbar'])
        ->execute()
        ->fetchAll();$database = Database::getConnection('default', 'monitoring');
        $service_data = $database->select('service_status', 's')
          ->fields('s', ['station', 'verfuegbar'])
          ->execute()
          ->fetchAll();

        // Initialize variables to store the values for each station
        $station1 = '';
        $station2 = '';
        $station3 = '';
        $station4 = '';

        // Process the service data and store values for each station
        foreach ($service_data as $index => $data) {
          $station = $data->station;
          $verfuegbar = $data->verfuegbar;

          // Assign values to the appropriate variable based on the station
          switch ($station) {
            case 'R-HEPS_1707_Septentrio_PolaRx5':
              $station1 = $verfuegbar;
              break;
            case 'R-HEPS_ELMS_Leica_GR25':
              $station2 = $verfuegbar;
              break;
            case 'HEPS_0707_Leica_GR25_2G':
              $station3 = $verfuegbar;
              break;
            case 'HEPS_ELM2_Septentrio_PolaRx5_2G':
              $station4 = $verfuegbar;
              break;
            default:
              break;
          }
        }
        if ($station1 || $station2) {
          $sapos_rheps = 'sapos_active';
        } else {
          $sapos_rheps = 'sapos_inactive';
        }
        if ($station3 || $station4) {
          $sapos_heps = 'sapos_active';
        } else {
          $sapos_heps = 'sapos_inactive';
        }

      $output .= '<div id="sapos_status"><div class="' . $sapos_heps . '">HEPS</div><div class="' . $sapos_rheps . '">R-HEPS-SH</div></div>';
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
    $has_permission = $account->hasPermission('access sapos service content');

    // Return the access result.
    return AccessResult::allowedIf($has_permission);
  }
}
