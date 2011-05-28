<?php

namespace FOS\TwitterBundle\Security\Authentication\Provider;

use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use FOS\TwitterBundle\Security\Authentication\Token\TwitterAnywhereToken;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authentication\Provider\AuthenticationProviderInterface;

class TwitterAnywhereProvider implements AuthenticationProviderInterface
{
    private $consumerSecret;
    private $provider;
    private $checker;

    public function __construct($consumerSecret, UserProviderInterface $provider = null, UserCheckerInterface $checker = null)
    {
        $this->consumerSecret = $consumerSecret;
        $this->provider = $provider;
        $this->checker = $checker;
    }

    public function authenticate(TokenInterface $token)
    {
        if (!$this->supports($token)) {
            return null;
        }

        if ($token->getSignature() !== sha1($token->getUser().$this->consumerSecret)) {
            throw new AuthenticationException(sprintf('The presented signature was invalid.'));
        }

        if (null === $this->provider) {
            $authenticated = TwitterAnywhereToken::createAuthenticated($token->getUser(), array());
            $authenticated->setAttributes($token->getAttributes());

            return $authenticated;
        }

        try {
            $user = $this->provider->loadUserByUsername($token->getUser());
            $this->checker->checkPostAuth($user);

            $authenticated = TwitterAnywhereToken::createAuthenticated($user, $user->getRoles());
            $authenticated->setAttributes($token->getAttributes());

            return $authenticated;
        } catch (AuthenticationException $passthroughEx) {
            throw $passthroughEx;
        } catch (\Exception $ex) {
            throw new AuthenticationException($ex->getMessage(), null, 0, $ex);
        }
    }

    public function supports(TokenInterface $token)
    {
        return $token instanceof TwitterAnywhereToken;
    }
}