<?php

namespace Drupal\entity_rest_extra\Plugin\rest\resource;

use Drupal\Core\Session\AccountProxyInterface;
use Drupal\rest\ModifiedResourceResponse;
use Drupal\rest\Plugin\ResourceBase;
use Drupal\rest\ResourceResponse;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpFoundation\RequestStack;
use Drupal\Core\Entity\EntityTypeManagerInterface;

/**
 * Provides a resource to get view modes by entity and bundle.
 *
 * @RestResource(
 *   id = "entity_bundle_fields_by_view_mode",
 *   label = @Translation("Entity bundle fields by view mode"),
 *   uri_paths = {
 *     "canonical" = "/entity/fields/{entity}/{bundle}/{form_display}",
 *     "https://www.drupal.org/entity/fields/{entity}/{bundle}/{form_display}" = "/entity/fields/{entity}/{bundle}/{form_display}",
 *   }
 * )
 */
class EntityBundleFieldsByViewMode extends ResourceBase {

  /**
   * A current user instance.
   *
   * @var \Drupal\Core\Session\AccountProxyInterface
   */
  protected $currentUser;

  /**
   * The request stack.
   *
   * @var \Symfony\Component\HttpFoundation\RequestStack
   */
  protected $requestStack;

  /**
   * The entity type manager.
   *
   * @var \Drupal\Core\Entity\EntityManagerInterface
   */
  protected $entityTypeManager;

  /**
   * Constructs a new EntityBundleFieldsByViewMode object.
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
   * @param \Drupal\Core\Session\AccountProxyInterface $current_user
   *   A current user instance.
   * @param \Symfony\Component\HttpFoundation\RequestStack $request_stack
   *   Rrequest stack.
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The entity type manager service.
   */
  public function __construct(
    array $configuration,
    $plugin_id,
    $plugin_definition,
    array $serializer_formats,
    LoggerInterface $logger,
    AccountProxyInterface $current_user,
    RequestStack $request_stack,
    EntityTypeManagerInterface $entity_type_manager) {
    parent::__construct($configuration, $plugin_id, $plugin_definition, $serializer_formats, $logger);
    $this->currentUser = $current_user;
    $this->requestStack = $request_stack->getCurrentRequest();
    $this->entityTypeManager = $entity_type_manager;
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
      $container->get('logger.factory')->get('entity_rest_extra'),
      $container->get('current_user'),
      $container->get('request_stack'),
      $container->get('entity_type.manager')
    );
  }

  /**
   * Responds to GET requests.
   *
   * @param string $entity
   *   The entity type id example node.
   * @param string $bundle
   *   The Entity $bundle example article.
   * @param string $form_display
   *   The Valid form display mode example default or teaser.
   *
   * @return \Drupal\rest\ResourceResponse
   *   The HTTP response object.
   *
   * @throws \Symfony\Component\HttpKernel\Exception\HttpException
   *   Throws exception expected.
   */
  public function get($entity, $bundle, $form_display) {
    // Entity seems to be null for some reason???
    if (empty($entity)) {
      $entity = $this->requestStack->attributes->get('entity');
    }

    $response = [];
    $status = 200;
    $entity_form_display = $this->entityTypeManager->getStorage('entity_form_display')
      ->load($entity . '.' . $bundle . '.' . $form_display);
    if (!empty($entity_form_display)) {
      // Set some basic info
      $response['mode'] = $entity_form_display->get('mode');
      $response['bundle'] = $bundle;
      $response['entity'] = $entity;

      $shown_fields = $entity_form_display->get('content');
      if (count($shown_fields) != 0) {
        $response['shown'] = array_keys($shown_fields);
        $fields  = \Drupal::entityManager()->getFieldDefinitions($entity,$bundle);
        $field_info = [];
        foreach ($response['shown'] as $field_name) {
          switch (get_class($fields[$field_name])) {
            case 'Drupal\field\Entity\FieldConfig':
            case 'Drupal\Core\Field\Entity\BaseFieldOverride':
              $field_object = $fields[$field_name];
              $storage = $field_object->getFieldStorageDefinition();
              $config_settings = [];
              if (is_object($storage)) {
                if (get_class($storage) === 'Drupal\field\Entity\FieldStorageConfig') {
                  $config_settings = $storage->getSettings();
                }
              }
              $field_info[$field_name] = [
                'weight' => $shown_fields[$field_name]['weight'],
                'label' => $field_object->label(),
                'description' => $field_object->get('description'),
                'name' => $field_object->get('field_name'),
                'field_type' => $field_object->get('field_type'),
                'required' => $field_object->get('required'),
                'settings' => $field_object->getSettings(),
                'config_settings' => $config_settings,
              ];
              break;
            case 'Drupal\Core\Field\BaseFieldDefinition':
              $field_object = $fields[$field_name];
              $field_array = $field_object->toArray();
              $label = '';
              if (!empty($field_array['label'])) {
                $label = $field_array['label']->__toString();
              }
              $description = '';
              if (!empty($field_array['description'])) {
                $description = $field_array['description']->__toString();
              }
              $config = $field_object->getConfig($bundle);
              $field_info[$field_name] = [
                'weight' => $shown_fields[$field_name]['weight'],
                'label' => $label,
                'description' => $description,
                'name' => $field_array['field_name'],
                'field_type' => $field_object->getType(),
                'required' => $config->get('required'),
                'settings' => $field_object->getSettings(),
              ];
              break;

            default:
              // @TODO handle others??.
              break;
          }
        }
        $response['shown'] = $field_info;
      }
      else {
        $response['shown'] = 'this display has no fields';
      }
      $hidden_fields = $entity_form_display->get('hidden');
      if (count($hidden_fields) != 0) {
        $response['hidden'] = array_keys($hidden_fields);
      }
      else {
        $response['hidden'] = 'this display has no hidden fields';
      }
    }
    else {
      // Not found give helpfull message.
      $response = ['message' => 'Entity form Not found'];
      $status = 400;
    }

    // Let the people Alter as they see fit.
    \Drupal::moduleHandler()->invokeAll('entity_rest_extra_alter_fields_output_form_mode', [$response]);

    return new ResourceResponse($response, $status);
  }

}
