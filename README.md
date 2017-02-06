# AmulenUserBundle
==========

Include libs in composer
--------------------

+ stof/doctrine-extensions-bundle
+ friendsofsymfony/rest-bundle
+ jms/serializer-bundle
+ nelmio/cors-bundle
+ lexik/jwt-authentication-bundle


Create bundles in AppKernel
--------------------

```
new FOS\RestBundle\FOSRestBundle(),
new JMS\SerializerBundle\JMSSerializerBundle(),
new Nelmio\CorsBundle\NelmioCorsBundle(),
new Stof\DoctrineExtensionsBundle\StofDoctrineExtensionsBundle(),
new Lexik\Bundle\JWTAuthenticationBundle\LexikJWTAuthenticationBundle(),
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


stof_doctrine_extensions:
    default_locale: es
    orm:
        default:
            tree: true
            sluggable: true
            timestampable: true

nelmio_cors:
        defaults:
            allow_credentials: false
            allow_origin: []
            allow_headers: []
            allow_methods: []
            expose_headers: []
            max_age: 0
            hosts: []
            origin_regex: false
        paths:
            '^/api/':
                allow_origin: ['*']
                allow_headers: ['Authorization']
                allow_methods: ['POST', 'PUT', 'GET', 'DELETE']
                max_age: 3600
            
lexik_jwt_authentication:
    private_key_path: "%jwt_private_key_path%"
    public_key_path:  "%jwt_public_key_path%"
    pass_phrase:      "%jwt_key_pass_phrase%"
    token_ttl:        "%jwt_token_ttl%"
```


Add in parameters.yml
--------------------
Reference: https://github.com/lexik/LexikJWTAuthenticationBundle

```
    jwt_private_key_path: %kernel.root_dir%/var/jwt/private.pem   # ssh private key path
    jwt_public_key_path:  %kernel.root_dir%/var/jwt/public.pem    # ssh public key path
    jwt_key_pass_phrase:  'keypass'                            # ssh key pass phrase
    jwt_token_ttl:        86400
```