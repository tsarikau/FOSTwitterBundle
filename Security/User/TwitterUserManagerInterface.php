<?php

namespace FOS\TwitterBundle\Security\User;

use Symfony\Component\Security\Core\User\UserManagerInterface;

interface TwitterUserManagerInterface extends UserManagerInterface
{
    /**
     * Creates a user for the given access token.
     *
     * @param array $token
     * @return UserInterface
     */
    function createUserFromAccessToken(array $token);
}