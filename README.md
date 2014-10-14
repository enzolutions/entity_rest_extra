#Entity Rest Extra

Extra Rest resources to enable access entities configuration

The following resources help to get admin information about Drupal 8 to be used by external implementation using Headless Drupal implementation.

This idea was suggested as a patch to Drupal 8 Core more information at https://www.drupal.org/node/2355291#comment-9241689

All resources require authentication with user with permission *Administer content types*

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
