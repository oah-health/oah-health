<?php

/**
 * @file
 * Contains \Drupal\google_qr_code\Plugin\Block\google_qr_code_block
 */

namespace Drupal\google_qr_code\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;
use Drupal\Core\Url;
use Drupal\Core\Link;

/**
 * Provides a Google QR Code Block
 * @Block(
 *     id = "Google QR Code",
 *     admin_label = @Translation ("Google QR Code"),
 *   )
 */
class google_qr_code_block extends BlockBase{

  public function build(){

    // Determine render type, and get the current URL.
    $render_type = \Drupal::config('google_qr_code.settings')->get('whenshow');
    $google_qr_current_url = \Drupal::request()->getUri();

    $qr_code_height = \Drupal::config('google_qr_code.settings')->get('height');
    $qr_code_width = \Drupal::config('google_qr_code.settings')->get('width');


    if ($render_type == 'on_click') {
      $requested_url_obj = Url::fromUri($google_qr_current_url);

      $link_object = Link::fromTextAndUrl(t('Click to see QR Code for this URL'), $requested_url_obj);
      $output = $link_object->toRenderable();

      $output['#class'] = 'google-qr-code-link';
      $output['#cache'] = array(
        'contexts' => array('url.path'),
      );
      $output['#attached']['library'][] = 'google_qr_code/google-qr-code-js';
      $output['#attached']['drupalSettings']['google_qr_code_url'] = $google_qr_current_url;
      $output['#attached']['drupalSettings']['google_qr_code_height'] = $qr_code_height;
      $output['#attached']['drupalSettings']['google_qr_code_width'] = $qr_code_width;

      // Return markup, and return the block as being cached per URL path.
      return $output;
    }
    else {
      // Get the google charts API image URL.
      $google_qr_image_url = "https://chart.googleapis.com/chart?chs=" .
        $qr_code_width . "x" . $qr_code_height
        . "&cht=qr&chl=" . $google_qr_current_url . '&chld=H|0';

      // Write the alternate description for the QR image.
      $google_qr_alt = $this->t('QR Code for @url', array('@url' => $google_qr_current_url));

      // Return markup, and return the block as being cached per URL path.
      return array(
        '#theme' => 'image',
        '#uri' => $google_qr_image_url,
        '#width' => $qr_code_width,
        '#height' => $qr_code_height,
        '#alt' => $google_qr_alt,
        '#class' => 'google-qr-code-image',
        '#cache' => array(
          'contexts' => array('url.path'),
        ),
      );
    }


  }

  public function blockAccess(AccountInterface $account){
    //return AccessResult::allowedIfHasPermission($account, 'view rsvplist');
    return AccessResult::allowed();
  }
}
