<?php

/**
 * @file
 * Definition of Drupal\entity_rest_extra\Plugin\rest\resource\EntityBundleViewModesResource.
 */

namespace Drupal\entity_rest_extra\Plugin\rest\resource;

use Drupal\Core\Entity\EntityManagerInterface;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Session\AccountProxyInterface;
use Drupal\rest\Plugin\ResourceBase;
use Drupal\rest\ResourceResponse;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Psr\Log\LoggerInterface;

/**
 * Provides a resource to get view modes by entity and bundle.
 *
 * @RestResource(
 *   id = "bundle_view_modes",
 *   label = @Translation("View modes by bundle"),
 *   uri_paths = {
 *     "canonical" = "entity/{entity}/{bundle}/view_modes"
 *   }
 * )
 */
class EntityBundleViewModesResource extends ResourceBase {

  /**
   *  A curent user instance.
   *
   * @var \Drupal\Core\Session\AccountProxyInterface
   */

  protected $currentUser;

  /**
   *  A instance of entity manager.
   *
   * @var \Drupal\Core\Entity\EntityManagerInterface
   */

  protected $entityManager;

  /**
   * The configuration factory.
   *
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  protected $configFactory;

  /*
   * Responds to GET requests.
   *
   * Returns a list of bundles for specified entity.
   *
   * @return \Drupal\rest\ResourceResponse
   *   The response containing a list of bundle names.
   *
   * @throws \Symfony\Component\HttpKernel\Exception\HttpException
   */
  public function get($entity = NULL, $bundle = NULL) {
    if ($entity && $bundle) {
      $permission = 'Administer content types';
      if(!$this->currentUser->hasPermission($permission)) {
        throw new AccessDeniedHttpException();
      }

      $entity_view_display =  $this->entityManager->getDefinition('entity_view_display');
      $config_prefix = $entity_view_display->getConfigPrefix();

      $list = $this->configFactory->listAll($config_prefix . '.' . $entity . '.' . $bundle . '.');

      $view_modes = array();
      foreach ($list as $view_mode) {
        $view_mode_machine_id = str_replace($config_prefix . '.', '', $view_mode);
        list(,, $view_mode_label) = explode('.', $view_mode_machine_id);
        $view_modes[$view_mode_machine_id] = $view_mode_label;
      }

      if (!empty($view_modes)) {
        return new ResourceResponse($view_modes);
      }

      throw new NotFoundHttpException(t('Views modes for @entity and @bundle were not found', array('@entity' => $entity, '@bundle' => $bundle)));
    }

        throw new HttpException(t('Entity or Bundle weren\'t provided'));
  }

    /**
   * Constructs a Drupal\rest\Plugin\ResourceBase object.
   *
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin_id for the plugin instance.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   * @param array $serializer_formats
   *   The available serialization formats.
   * @param \Psr\Log\LoggerInterface $logger
   *   A logger instance.
   */
  public function __construct(
    array $configuration,
    $plugin_id, $plugin_definition,
    array $serializer_formats,
    LoggerInterface $logger,
    EntityManagerInterface $entity_manager,
    ConfigFactoryInterface $config_factory,
    AccountProxyInterface $current_user) {
    parent::__construct($configuration, $plugin_id, $plugin_definition, $serializer_formats, $logger);

    $this->entityManager = $entity_manager;
    $this->configFactory = $config_factory;
    $this->currentUser = $current_user;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->getParameter('serializer.formats'),
      $container->get('logger.factory')->get('rest'),
      $container->get('entity.manager'),
      $container->get('config.factory'),
      $container->get('current_user')
    );
  }
}
