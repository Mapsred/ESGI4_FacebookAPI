<?php
/**
 * Created by PhpStorm.
 * User: francois.mathieu
 * Date: 05/12/2017
 * Time: 12:05
 */

namespace AppBundle\Manager;


use AppBundle\Entity\Site;
use AppBundle\Security\Core\User\OAuthUser;
use AppBundle\Utils\Facebook\Album;
use AppBundle\Utils\Facebook\Picture;
use Cocur\Slugify\Slugify;
use Doctrine\Common\Persistence\ObjectManager;
use HWI\Bundle\OAuthBundle\OAuth\ResourceOwner\FacebookResourceOwner;
use HWI\Bundle\OAuthBundle\OAuth\Response\UserResponseInterface;
use HWI\Bundle\OAuthBundle\Security\Core\Authentication\Token\OAuthToken;

class SiteManager
{
    /**
     * @var ObjectManager $manager
     */
    private $manager;

    /**
     * @var FacebookResourceOwner $resourceOwner
     */
    private $resourceOwner;

    /**
     * SiteManager constructor.
     * @param ObjectManager $manager
     * @param FacebookResourceOwner $resourceOwner
     */
    public function __construct(ObjectManager $manager, FacebookResourceOwner $resourceOwner)
    {
        $this->manager = $manager;
        $this->resourceOwner = $resourceOwner;
    }

    /**
     * Generate an OAuthUser from an AccessToken
     * @param Site $site
     * @param UserResponseInterface|null $response
     * @return Site
     */
    public function generateOAuthUser(Site $site, UserResponseInterface $response = null)
    {
        if (null === $response) {
            $token = new OAuthToken($site->getAccessToken());
            $token->setResourceOwnerName($this->resourceOwner->getName());
            $oldToken = $token->isExpired() ? $this->refreshToken($token, $this->resourceOwner) : $token;
            $response = $this->resourceOwner->getUserInformation($oldToken->getRawToken());
        }

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

        $slugify = new Slugify();
        $site
            ->setOAuthUser($user)
            ->setUserName($slugify->slugify($response->getRealName()))
            ->setAccessToken($response->getAccessToken());

        $this->manager->persist($site);
        $this->manager->flush();

        return $site;
    }

    /**
     * @param OAuthToken $expiredToken
     * @param FacebookResourceOwner $resourceOwner
     * @return OAuthToken
     */
    protected function refreshToken(OAuthToken $expiredToken, FacebookResourceOwner $resourceOwner)
    {
        $token = new OAuthToken($resourceOwner->refreshAccessToken($expiredToken->getRefreshToken()));
        $token->setRefreshToken($expiredToken->getRefreshToken());

        return $token;
    }

}