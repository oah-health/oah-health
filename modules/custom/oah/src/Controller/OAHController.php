<?php
/**
 *@file
 *Contains Drupal\oah\OAHController.
 */
namespace Drupal\oah\Controller;
use Drupal\Core\Controller\ControllerBase;
/**
 * Controller for the Signup form.
 */
 class OAHController extends ControllerBase{
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
