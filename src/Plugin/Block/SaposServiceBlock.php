<?php

namespace Drupal\sapos_service\Plugin\Block;

use Drupal\Core\Block\BlockBase;

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
    $output = '<table style="border-collapse: collapse; border: 1px solid #000;"><thead><tr><th>Name</th><th>Status</th></tr></thead><tbody>';
    $service_data = \Drupal::database()->select('service_status', 's')
      ->fields('s', ['name', 'verfuegbar'])
      ->execute()
      ->fetchAll();

    foreach ($service_data as $data) {
      if ($data->verfuegbar) {
        $verfuegbar = $this->t('Available 💚💚💚');
        $status_style = 'color: green;';
      } else {
        $verfuegbar = $this->t('Not available 🛑');
        $status_style = 'color: red;';
      }
      $output .= '<tr><td style="border: 1px solid black; font-weight: bold;">' . $data->name . '</td><td style="border: 1px solid black;"><span style="' . $status_style . '">' . $verfuegbar . '</span></td></tr>';
    }

    $output .= '</tbody></table>';

    return [
      '#markup' => $output,
    ];
  }
}
