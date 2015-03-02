#Entity Rest Extra

Extra Rest resources to enable access to entities configuration via Rest Resource

The following resources help to get admin information about Drupal 8 to be used by external implementation i.e Headless Drupal.

This idea was suggested as a patch to Drupal 8 Core more information at https://www.drupal.org/node/2355291#comment-9241689

All resources require authentication with an user with permission *Administer content types*

This project is used as part of project [Marionette Drupal](https://github.com/enzolutions/generator-marionette-drupal) Yeoman Generator

Module generated using [Drupal Console](http://drupalconsole.com)

#Usage

Enable Rest Resources created by Entity Rest Extra.

Using the REST UI enable Rest Resources created by Entity Rest Extra, setting the Authentication and format JSON is recommended as you can see in the following image.

![REST UI](https://github.com/enzolutions/entity_rest_extra/blob/master/images/restui_bundle_entities_settings.png "REST UI")

Users with a role with permission **Administer content types** will be able to consume Rest Resources

Using the Chrome Application <a href="https://chrome.google.com/webstore/detail/postman-rest-client/fdmmgilgnpjigdojojpjoooidkmcomcm">Postman - REST Client</a> you can execute an authenticated request to URL **http://example.com/entitu/node/bundles** as you can see in the following image.

![Postman - Rest Client](https://github.com/enzolutions/entity_rest_extra/blob/master/images/postman_rest_request.png "Postman - Rest Client")

##Get Entity bundles

End Point: http://example.com/{entity}/bundles

Entity: could be any valid entity type i.e node or comment

###OUTPUT Sample

```
{
    "article": "Article",
    "page": "Basic page"
}
```

##Get view modes by Entity and Bundle

End Point: http://example.com/{entity}/{bundle}/view_modes

Entity: could be any valid entity type i.e node or comment

Bundle: could be any valid bundle for entity provided i.e page, article

###Output Sample

```
{
    "node.article.default": "default",
    "node.article.rss": "rss",
    "node.article.teaser": "teaser"
}
```

##Get fields by Entity and Bundle

End Point: http://example.com/{entity}/{bundle}/fields

Entity: could be any valid entity type i.e node or comment

Bundle: could be any valid bundle for entity provided i.e page, article

###Output Sample

```
{
"body": {
"uuid": "0614f505-95e1-4e36-8800-f8f8671a8e22",
"langcode": "en",
"status": true,
"dependencies": {
"config": [
"field.storage.node.body",
"node.type.article"
],
"module": [
"text"
]
},
"id": "node.article.body",
"field_name": "body",
"entity_type": "node",
"bundle": "article",
"label": "Body",
"description": "",
"required": false,
"translatable": true,
"default_value": [ ],
"default_value_callback": "",
"settings": {
"display_summary": true
},
"field_type": "text_with_summary"
},
"comment": {
"uuid": "b53f0289-4669-4c24-bf2c-4462b15ce43e",
"langcode": "en",
"status": true,
"dependencies": {
"config": [
"field.storage.node.comment",
"node.type.article"
],
"module": [
"comment"
]
},
"id": "node.article.comment",
"field_name": "comment",
"entity_type": "node",
"bundle": "article",
"label": "Comments",
"description": "",
"required": true,
"translatable": true,
"default_value": [
{
"status": 2,
"cid": 0,
"last_comment_name": null,
"last_comment_timestamp": 0,
"last_comment_uid": 0,
"comment_count": 0
}
],
"default_value_callback": "",
"settings": {
"default_mode": 1,
"per_page": 50,
"form_location": true,
"anonymous": 0,
"preview": 1
},
"field_type": "comment"
},
"field_image": {
"uuid": "7ff325d6-d7f3-45c7-8e00-d0df059d5d90",
"langcode": "en",
"status": true,
"dependencies": {
"config": [
"field.storage.node.field_image",
"node.type.article"
],
"module": [
"image"
]
},
"id": "node.article.field_image",
"field_name": "field_image",
"entity_type": "node",
"bundle": "article",
"label": "Image",
"description": "",
"required": false,
"translatable": true,
"default_value": [ ],
"default_value_callback": "",
"settings": {
"file_directory": "field\/image",
"file_extensions": "png gif jpg jpeg",
"max_filesize": "",
"max_resolution": "",
"min_resolution": "",
"alt_field": true,
"title_field": false,
"alt_field_required": false,
"title_field_required": false,
"default_image": {
"uuid": null,
"alt": "",
"title": "",
"width": null,
"height": null
},
"handler": "default"
},
"field_type": "image"
},
"field_tags": {
"uuid": "4ea5e8f3-9f16-4919-8f12-1836adc701ba",
"langcode": "en",
"status": true,
"dependencies": {
"config": [
"field.storage.node.field_tags",
"node.type.article"
],
"module": [
"taxonomy"
]
},
"id": "node.article.field_tags",
"field_name": "field_tags",
"entity_type": "node",
"bundle": "article",
"label": "Tags",
"description": "Enter a comma-separated list. For example: Amsterdam, Mexico City, \"Cleveland, Ohio\"",
"required": false,
"translatable": true,
"default_value": [ ],
"default_value_callback": "",
"settings": {
"handler": "default"
},
"field_type": "taxonomy_term_reference"
}
}
```
