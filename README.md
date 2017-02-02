# AmulenUserBundle
==========

Include libs in composer
--------------------

+ stof/doctrine-extensions-bundle
+ friendsofsymfony/rest-bundle
+ jms/serializer-bundle
+ nelmio/cors-bundle


Create bundles in AppKernel
--------------------

```
new FOS\RestBundle\FOSRestBundle(),
new JMS\SerializerBundle\JMSSerializerBundle(),
new Nelmio\CorsBundle\NelmioCorsBundle(),
new Stof\DoctrineExtensionsBundle\StofDoctrineExtensionsBundle(),
new Flowcode\NotificationBundle\FlowcodeNotificationBundle(),
new Flowcode\UserBundle\FlowcodeUserBundle(),
```


Configs in config.yml
--------------------

```
orm:
    auto_generate_proxy_classes: "%kernel.debug%"
    naming_strategy: doctrine.orm.naming_strategy.underscore
    auto_mapping: true
    mappings:
        # for FlowcodeUserBundle
        gedmo_tree: 
            type: annotation
            prefix: Gedmo\Tree\Entity
            dir: "%kernel.root_dir%/../vendor/gedmo/doctrine-extensions/lib/Gedmo/Tree/Entity"
            alias: GedmoTree
            is_bundle: false  

stof_doctrine_extensions:
    default_locale: es
    orm:
        default:
            tree: true
            sluggable: true
            timestampable: true


# Nelmio CORS Configuration
nelmio_cors:
    defaults:
        allow_credentials: false
        allow_origin: ['*']
        allow_headers: ['*']
        allow_methods: ['GET', 'POST', 'PUT', 'DELETE', 'OPTIONS']
        max_age: 3600
        hosts: []
        origin_regex: false
 
# FOSRest Configuration
fos_rest:
    body_listener: true
    routing_loader:
        include_format: false
    format_listener:
        rules:
            - { path: '^/', priorities: ['json'], fallback_format: json, prefer_extension: false }
    param_fetcher_listener: true
    view:
        view_response_listener: 'force'
        formats:
            json: true
```