<?php

/**
 * @file
 * Contains \Drupal\sapos_service\Controller\SaposServiceController.
 */

 namespace Drupal\sapos_service\Controller;

 use Drupal\Core\Controller\ControllerBase;
 use Drupal\Core\Database\Database;
 use Drupal\Core\Render\Markup;

 class SaposServiceController extends ControllerBase {

   public function content() {
     $output = 'Hi from the Sapos Service Controller !!! 🧔🏻✨📡 <hr/> <table class="sapos-service-table" border="1"><thead><tr><th style="border: 1px solid;">Name</th><th style="border: 1px solid;">Status</th></tr></thead><tbody>';
     $service_data = Database::getConnection()->select('service_status', 's')
       ->fields('s', ['name', 'verfuegbar'])
       ->execute()
       ->fetchAll();

     foreach ($service_data as $data) {
       if ($data->verfuegbar) {
         $verfuegbar = $this->t('Verfügbar 💚');
         $status_style = 'color: green;';
       } else {
         $verfuegbar = $this->t('Nicht verfügbar 🛑');
         $status_style = 'color: red;';
       }
       $output .= '<tr><td style="border: 1px solid;padding: 5px;">' . $data->name . '</td><td style="border: 1px solid;padding: 1px 5px;"><span style="' . $status_style . '">' . $verfuegbar . '</span></td></tr>';
     }

     $output .= '</tbody></table>';

     return [
       '#theme' => 'sapos_service_block',
       '#service_data' => $service_data,
       '#markup' => Markup::create($output),
       '#attached' => [
         'library' => [
           'sapos_service/sapos_service',
         ],
       ],
     ];
   }

 }
