<?php

/*
 * This file is part of the FOSTwitterBundle package.
 *
 * (c) FriendsOfSymfony <http://friendsofsymfony.github.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FOS\TwitterBundle\Security\Authentication\Provider;

use FOS\TwitterBundle\Security\User\UserManagerInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authentication\Provider\AuthenticationProviderInterface;
use Symfony\Component\DependencyInjection\Container;

use FOS\TwitterBundle\Security\Authentication\Token\TwitterUserToken;
use FOS\TwitterBundle\Services\Twitter;

class TwitterProvider implements AuthenticationProviderInterface
{
    private $twitter;
    private $userProvider;
    private $userChecker;
    private $createUserIfNotExists;

    public function __construct(Twitter $twitter, UserProviderInterface $userProvider = null, UserCheckerInterface $userChecker = null, $createUserIfNotExists = false)
    {
        if (null !== $userProvider && null === $userChecker) {
            throw new \InvalidArgumentException('$userChecker cannot be null, if $userProvider is not null.');
        }

        if ($createUserIfNotExists && !$userProvider instanceof UserManagerInterface) {
            throw new \InvalidArgumentException('$userProvider must be an instanceof UserManagerInterface if createUserIfNotExists is true.');
        }

        $this->twitter = $twitter;
        $this->userProvider = $userProvider;
        $this->userChecker = $userChecker;
        $this->createUserIfNotExists = $createUserIfNotExists;
    }

    public function authenticate(TokenInterface $token)
    {
        if (!$this->supports($token)) {
            return null;
        }

        try {
            if ($accessToken = $this->twitter->getAccessToken($token->getUser(), $token->getOauthVerifier())) {
                return $this->createAuthenticatedToken($accessToken);
            }
        } catch (AuthenticationException $failed) {
            throw $failed;
        } catch (\Exception $failed) {
            throw new AuthenticationException('Unknown error', $failed->getMessage(), $failed->getCode(), $failed);
        }

        throw new AuthenticationException('The Twitter user could not be retrieved from the session.');
    }

    public function supports(TokenInterface $token)
    {
        return $token instanceof TwitterUserToken;
    }

    private function createAuthenticatedToken(array $accessToken)
    {
        if (null === $this->userProvider) {
            return new TwitterUserToken($accessToken['screen_name']);
        }

        $loadUser = (!$this->createUserIfNotExists || $this->userProvider
            ->userExists($accessToken['screen_name']));

        $user = $loadUser ?
            $this->userProvider->loadUserByUsername($accessToken['screen_name'])
            : $this->userProvider->createUserFromAccessToken($accessToken);

        if (!$user instanceof UserInterface) {
            throw new \RuntimeException('User provider did not return an implementation of user interface.');
        }

        if ($loadUser) {
            $this->userChecker->checkPostAuth($user);
        }

        return new TwitterUserToken($user, null, $user->getRoles());
    }
}
