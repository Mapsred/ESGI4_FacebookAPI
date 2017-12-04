<?php
/**
 * Created by PhpStorm.
 * User: francois.mathieu
 * Date: 04/12/2017
 * Time: 12:41
 */

namespace AppBundle\Security\Core\User;

use AppBundle\Utils\Facebook\Album;
use AppBundle\Utils\Facebook\Picture;
use HWI\Bundle\OAuthBundle\OAuth\Response\UserResponseInterface;
use HWI\Bundle\OAuthBundle\Security\Core\User\OAuthUserProvider as BaseOAuthUserProvider;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\UserInterface;

class OAuthUserProvider extends BaseOAuthUserProvider
{
    /**
     * @param string $username
     * @return OAuthUser
     */
    public function loadUserByUsername($username)
    {
        return new OAuthUser($username);
    }

    /**
     * @param UserResponseInterface $response
     * @return OAuthUser|UserInterface
     */
    public function loadUserByOAuthUserResponse(UserResponseInterface $response)
    {
        $user = new OAuthUser($response->getRealName());
        $user->setId($response->getUsername());
        $user->setEmail($response->getEmail());

        $albums = [];
        foreach ($response->getData()['albums']['data'] as $data) {
            $photos = [];
            foreach ($data['photos']['data'] as $photoData) {
                $photo = new Picture($photoData['id'], $photoData['picture']);
                $photos[] = $photo;
            }
            $album = new Album($data['id'], $data['name'], $photos);
            $albums[] = $album;
        }

        $user->setAlbums($albums);

        return $user;
    }

    /**
     * @param UserInterface $user
     * @return OAuthUser|UserInterface
     * @throws UnsupportedUserException
     */
    public function refreshUser(UserInterface $user)
    {
        if (!$this->supportsClass(get_class($user))) {
            throw new UnsupportedUserException(sprintf('Unsupported user class "%s"', get_class($user)));
        }

        return $user;
    }

    /**
     * @param string $class
     * @return bool
     */
    public function supportsClass($class)
    {
        return 'AppBundle\\Security\\Core\\User\\OAuthUser' === $class;
    }

}