<?php

/**
 * @file
 * Contains \Drupal\interval\Plugin\Field\FieldWidget\IntervalWidget.
 */

namespace Drupal\interval\Plugin\Field\FieldWidget;

use Drupal\Core\Field\FieldDefinitionInterface;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\WidgetBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\interval\IntervalPluginManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides an interval widget.
 *
 * @FieldWidget(
 *   id = "interval_default",
 *   label = @Translation("Interval and Period"),
 *   field_types = {
 *     "interval"
 *   },
 *   settings = {
 *     "allowed_periods" = {}
 *   }
 * )
 */
class IntervalWidget extends WidgetBase implements ContainerFactoryPluginInterface {

  /**
   * The interval plugin manager service.
   *
   * @var \Drupal\interval\IntervalPluginManagerInterface
   */
  protected $intervalManager;

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static($plugin_id, $plugin_definition,$configuration['field_definition'], $configuration['settings'], $configuration['third_party_settings'], $container->get('element_info'));
  }

  /**
   * {@inheritdoc}
   */
  public function formElement(FieldItemListInterface $items, $delta, array $element, array &$form, FormStateInterface $form_state) {
    // Available periods.
    if ($this->getSetting('allowed_periods')) {
      $element['#periods'] = array_keys(array_filter($this->getSetting('allowed_periods')));
    }

    $element += array(
      '#type' => 'interval',
      '#default_value' => array(
        'interval' => $items->get($delta)->getInterval(),
        'period' => $items->get($delta)->getPeriod(),
      ),
    );

    return $element;
  }

  /**
   * {@inheritdoc}
   */
  public function settingsForm(array $form, FormStateInterface $form_state) {
    $settings = $this->getSettings();

    $options = array();
    $intervals = $this->intervalManager()->getDefinitions();
    foreach ($intervals as $key => $detail) {
      $options[$key] = $detail['plural'];
    }
    $form['allowed_periods'] = array(
      '#type' => 'checkboxes',
      '#title' => t('Allowed periods'),
      '#options' => $options,
      '#description' => t('Select the periods you wish to be available in the dropdown. Selecting none will make all of them available.'),
      '#default_value' => isset($settings['allowed_periods']) ? $settings['allowed_periods'] : array(),
    );
    return $form;
  }

  /**
   * Sets the interval plugin manager.
   *
   * @param \Drupal\interval\IntervalPluginManagerInterface $interval_manager
   *   The interval plugin manager.
   */
  public function setIntervalManager(IntervalPluginManagerInterface $interval_manager) {
    $this->intervalManager = $interval_manager;
  }

  /**
   * Gets the interval manager service.
   *
   * @return \Drupal\interval\IntervalPluginManagerInterface
   *   The interval manager service.
   */
  protected function intervalManager() {
    if (!$this->intervalManager) {
      $this->intervalManager = \Drupal::service('plugin.manager.interval.intervals');
    }
    return $this->intervalManager;
  }


}
