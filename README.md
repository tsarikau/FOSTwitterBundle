Introduction
============

This Bundle enables integration with Twitter PHP. Furthermore it
also provides a Symfony2 authentication provider so that users can login to a
Symfony2 application via Twitter. Furthermore via custom user provider support
the Twitter login can also be integrated with other data sources like the
database based solution provided by FOSUserBundle.

[![Build Status](https://secure.travis-ci.org/FriendsOfSymfony/FOSTwitterBundle.png)](http://travis-ci.org/FriendsOfSymfony/FOSTwitterBundle)

Installation
============

  1. Add this bundle and Abraham Williams' Twitter library to your project as Git submodules:

          $ git submodule add git://github.com/FriendsOfSymfony/FOSTwitterBundle.git vendor/bundles/FOS/TwitterBundle
          $ git submodule add git://github.com/kertz/twitteroauth.git vendor/twitteroauth

>**Note:** The kertz/twitteroauth is patched to be compatible with FOSTwitterBundle

  2. Register the namespace `FOS` to your project's autoloader bootstrap script:

          //app/autoload.php
          $loader->registerNamespaces(array(
                // ...
                'FOS'    => __DIR__.'/../vendor/bundles',
                // ...
          ));

  3. Add this bundle to your application's kernel:

          //app/AppKernel.php
          public function registerBundles()
          {
              return array(
                  // ...
                  new FOS\TwitterBundle\FOSTwitterBundle(),
                  // ...
              );
          }

  4. Configure the `twitter` service in your YAML configuration:

            #app/config/config.yml
            fos_twitter:
                file: %kernel.root_dir%/../vendor/twitteroauth/twitteroauth/twitteroauth.php
                consumer_key: xxxxxx
                consumer_secret: xxxxxx
                callback_url: http://www.example.com/login_check

  5. Add the following configuration to use the security component:

            #app/config/config.yml
            security:
                factories:
                  - "%kernel.root_dir%/../vendor/bundles/FOS/TwitterBundle/Resources/config/security_factories.xml"
                providers:
                    fos_twitter:
                        id: fos_twitter.auth
                firewalls:
                    secured:
                        pattern:   /secured/.*
                        fos_twitter: true
                    public:
                        pattern:   /.*
                        anonymous: true
                        fos_twitter: true
                        logout: true
                access_control:
                    - { path: /.*, role: [IS_AUTHENTICATED_ANONYMOUSLY] }

Using Twitter @Anywhere
-----------------------

>**Note:** If you want the Security Component to work with Twitter @Anywhere, you need to send a request to the configured check path upon successful client authentication (see https://gist.github.com/1021384 for a sample configuration).

A templating helper is included for using Twitter @Anywhere. To use it, first
call the `->setup()` method toward the top of your DOM:

        <!-- inside a php template -->
          <?php echo $view['twitter_anywhere']->setup() ?>
        </head>

        <!-- inside a twig template -->
          {{ twitter_anywhere_setup() }}
        </head>

Once that's done, you can queue up JavaScript to be run once the library is
actually loaded:

        <!-- inside a php template -->
        <span id="twitter_connect"></span>
        <?php $view['twitter_anywhere']->setConfig('callbackURL', 'http://www.example.com/login_check') ?>
        <?php $view['twitter_anywhere']->queue('T("#twitter_connect").connectButton()') ?>

        <!-- inside a twig template -->
        <span id="twitter_connect"></span>
        {{ twitter_anywhere_setConfig('callbackURL', 'http://www.example.com/login_check') }}
        {{ twitter_anywhere_queue('T("#twitter_connect").connectButton()') }}

Finally, call the `->initialize()` method toward the bottom of the DOM:

        <!-- inside a php template -->
          <?php $view['twitter_anywhere']->initialize() ?>
        </body>

        <!-- inside a twig template -->
        {{ twitter_anywhere_initialize() }}
        </body>

### Configuring Twitter @Anywhere

You can set configuration using the templating helper. with the setConfig() method.

## Advanced uses

Please see the FOSFacebookBundle for documentation on creating a custom user provider.
