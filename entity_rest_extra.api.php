<?php

/**
 * @file
 * Documentation for entity_rest_extra.
 */

/**
 * Define hook_entity_rest_extra_alter_fields_output().
 *
 * Alter The Entity fields array before return by REST.
 *
 * See "get" method in EntityBundleFieldsResource.
 *
 * @param array $fields
 *   Fields of an entity as an array
 */
function hook_entity_rest_extra_alter_fields_output(array &$fields) {
}

/**
 * Define hook_entity_rest_extra_alter_fields_output_form_mode().
 *
 * Alter The Entity fields array before returned by REST.
 *
 * See "get" method in EntityBundleFieldsByViewMode.
 *
 * @param array $response
 *   Fields of an entity as an array.
 */
function hook_entity_rest_extra_alter_fields_output_form_mode(array &$response) {
}
