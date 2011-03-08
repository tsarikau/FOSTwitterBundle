<?php

namespace FOS\TwitterBundle\DependencyInjection\Security\Factory;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\DependencyInjection\DefinitionDecorator;
use Symfony\Bundle\SecurityBundle\DependencyInjection\Security\Factory\AbstractFactory;

class TwitterFactory extends AbstractFactory
{
    public function __construct()
    {
        //Nothing to do here
    }

    public function getPosition()
    {
        return 'pre_auth';
    }

    public function getKey()
    {
        return 'fos_twitter';
    }

    protected function getListenerId()
    {
        return 'fos_twitter.security.authentication.listener';
    }

    protected function createAuthProvider(ContainerBuilder $container, $id, $config, $userProviderId)
    {
        // with user provider
        if (isset($config['provider'])) {
            $authProviderId = 'fos_twitter.auth.'.$id;

            $container
                ->setDefinition($authProviderId, new DefinitionDecorator('fos_twitter.auth'))
                ->addArgument(new Reference($userProviderId))
                ->addArgument(new Reference('security.account_checker'))
            ;

            return $authProviderId;
        }

        // without user provider
        return 'fos_twitter.auth';
    }

    protected function createEntryPoint($container, $id, $config, $defaultEntryPointId)
    {
        $entryPointId = 'fos_twitter.security.authentication.entry_point.'.$id;
        $container
            ->setDefinition($entryPointId, new DefinitionDecorator('fos_twitter.security.authentication.entry_point'))
        ;

        return $entryPointId;
    }
}
