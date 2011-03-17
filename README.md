TODO
====
    1. Update Tests

Installation
============

  1. Add this bundle and Abraham Williams' Twitter library to your project as Git submodules:

          $ git submodule add git://github.com/FriendsOfSymfony/TwitterBundle.git vendor/bundles/FOS/TwitterBundle
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
                    fos_twitter: "%kernel.root_dir%/../vendor/bundles/FOS/TwitterBundle/Resources/config/security_factories.xml"
                providers:
                    fos_twitter:
                        id: fos_twitter.auth
                firewalls:
                    public:
                        pattern:   /.*
                        anonymous: true
                        fos_twitter: true
                        logout: true
                    secured:
                        pattern:   /secured/.*
                        fos_twitter: true
                access_control:
                    - { path: /secured/*, role: [IS_AUTHENTICATED_FULLY] }

Using Twitter @Anywhere
-----------------------

>**Note:** The security component won't work with @Anywhere and has to implemented with Javascript in your view as explained in [http://dev.twitter.com/anywhere/begin](http://dev.twitter.com/anywhere/begin)

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
