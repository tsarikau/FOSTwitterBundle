<?php

namespace FOS\TwitterBundle\Security\Authentication\Token;

use Symfony\Component\Security\Core\Authentication\Token\AbstractToken;

class TwitterUserToken extends AbstractToken
{
    public function __construct($uid = '', array $roles = array())
    {
        parent::__construct($roles);

        $this->setUser($uid);

        if (!empty($uid)) {
            $this->authenticated = true;
        }
    }

    public function getCredentials()
    {
        return '';
    }
}
