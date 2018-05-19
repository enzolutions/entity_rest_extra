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

Using the Chrome Application <a href="https://chrome.google.com/webstore/detail/postman-rest-client/fdmmgilgnpjigdojojpjoooidkmcomcm">Postman - REST Client</a> you can execute an authenticated request to URL **http://example.com/entituy/node/bundles** as you can see in the following image.

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


#### Hook Provided To alter output
See entity_rest_extra.api.php

###Output Sample

```
{
    "body": {
    "uuid": "0614f505-95e1-4e36-8800-f8f8671a8e22",
    "langcode": "en",
    "status": true,
    .
    .
    .
    "id": "node.article.body",
    "field_name": "body",
    "entity_type": "node",
    "bundle": "article",
    "label": "Body",
    "description": "",
    "required": false,
    "translatable": true,
    .
    .
    .
}
```

##Get Fields by Entity and Bundle and From Display mode

End Point: http://example.com/entity/fields/{entity}/{bundle}/{form_display}

Entity: could be any valid entity type i.e node or comment

Bundle: could be any valid bundle for entity provided i.e page, article

form_display: this is the form display machine name  example 'default' 


Motivation is to simplify field output for A Rest client to build a form from fields.


#### Hook Provided To alter output
See entity_rest_extra.api.php

###Output Sample
```
{
	"mode": "default",
	"bundle": "page",
	"entity": "node",
	"shown": {
		"body": {
			"weight": 31,
			"label": "Detailed Description",
			"description": "Please give details of your issue.",
			"name": "body",
			"field_type": "text_with_summary",
			"required": true,
			"settings": {
				"display_summary": false
			},
			"config_settings": []
		},
	"hidden": {
	...
	}
		...
```