<?php

namespace FOS\TwitterBundle\Security\Firewall;

use FOS\TwitterBundle\Security\Authentication\Token\TwitterUserToken;
use Symfony\Component\Security\Http\Firewall\AbstractAuthenticationListener;

use Symfony\Component\HttpFoundation\Request;

/**
 * Twitter authentication listener.
 */
class TwitterListener extends AbstractAuthenticationListener
{
    protected function attemptAuthentication(Request $request)
    {
        return $this->authenticationManager->authenticate(new TwitterUserToken());
    }
}
