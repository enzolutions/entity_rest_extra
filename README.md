#Entity Rest Extra

Extra Rest resources to enable access entities configuration

The following resources help to get admin information about Drupal 8 to be used by external implementation using Headless Drupal.

This idea was suggested as a patch to Drupal 8 Core more information at https://www.drupal.org/node/2355291#comment-9241689

All resources require authentication with an user with permission *Administer content types*

This project is used as part of project [Marionette Drupal](https://github.com/enzolutions/generator-marionette-drupal) Yeoman Generator

Module generated using [Drupal Console](http://drupalconsole.com)

#Usage

Using the contrib module <a href="https://www.drupal.org/project/restui/git-instructions" target="_blank">Rest UI</a> (I recommend to use the git version until Drupal 8 get a first release) you can enable Rest Resources created by Entity Rest Extra.

Using the REST UI enable Rest Resources created by Entity Rest Extra, setting the Authentication and format JSON is recommended as you can see in the following image.

![REST UI](https://github.com/enzolutions/entity_rest_extra/blob/master/images/restui_bundle_entities_settings.png "REST UI")

Using this settings the access will be granted to users with roles with permission **Administer content types**.

Using the Chrome Application <a href="https://chrome.google.com/webstore/detail/postman-rest-client/fdmmgilgnpjigdojojpjoooidkmcomcm">Postman - REST Client</a> you can execute an authenticated request to URL **http://example.com/bundles/node** as you can see in the following image.

![Postman - Rest Client](https://github.com/enzolutions/entity_rest_extra/blob/master/images/postman_rest_request.png "Postman - Rest Client")

##Get Entity bundles

End Point: http://example.com/bundles/{entity}

Entity: could be any valid entity type i.e node or comment

###OUTPUT Sample

```
{
    "article": "Article",
    "page": "Basic page"
}
```

##Get view modes by Entity and Bundle

End Point: http://example.com/view_modes/{entity}/{bundle}

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
