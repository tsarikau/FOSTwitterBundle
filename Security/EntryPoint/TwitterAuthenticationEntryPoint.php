<?php

namespace FOS\TwitterBundle\Security\EntryPoint;

use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Http\EntryPoint\AuthenticationEntryPointInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\EventDispatcher\EventInterface;

use FOS\TwitterBundle\Services\Twitter;

/**
 * TwitterAuthenticationEntryPoint starts an authentication via Twitter.
 */
class TwitterAuthenticationEntryPoint implements AuthenticationEntryPointInterface
{
    protected $twitter;

    /**
     * Constructor
     *
     * @param Twitter $twitter
     */
    public function __construct(Twitter $twitter)
    {
        $this->twitter = $twitter;
    }

    /**
     * {@inheritdoc}
     */
    public function start(EventInterface $event, Request $request, AuthenticationException $authException = null)
    {
        $authURL = $this->twitter->getLoginUrl();
        if (!$authURL) {
            throw new AuthenticationException('Could not connect to Twitter!');
        }
        $response = new RedirectResponse($authURL);
        
        return $response;
    }
}
