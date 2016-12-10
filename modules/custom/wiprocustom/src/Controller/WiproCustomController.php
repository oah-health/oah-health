<?php
/**
 *@file
 *Contains Drupal\wiprocustom\WiproCustomController.
 */
namespace Drupal\wiprocustom\Controller;
use Drupal\Core\Controller\ControllerBase;
/**
 * Controller for the Signup form.
 */
 class WiproCustomController extends ControllerBase{
   /**
    *{@inheritdoc}
    */
    public function signup(){
      $build = array(
        '#type' => 'markup',
        '#markup' => t('Signup Page'),
      );
      return $build;
    }
 }
