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
use AppBundle\Utils\Facebook\Facebook;
use AppBundle\Utils\Facebook\Picture;
use AppBundle\Utils\Facebook\WebpImage;
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
     * @var Site $site
     */
    private $site;

    /**
     * @var Facebook $facebook
     */
    private $facebook;
    /**
     * @var ContentManager
     */
    private $contentManager;

    /**
     * SiteManager constructor.
     * @param ObjectManager $manager
     * @param FacebookResourceOwner $resourceOwner
     * @param Facebook $facebook
     * @param ContentManager $disabledContentManager
     */
    public function __construct(ObjectManager $manager, FacebookResourceOwner $resourceOwner, Facebook $facebook, ContentManager $disabledContentManager)
    {
        $this->manager = $manager;
        $this->resourceOwner = $resourceOwner;
        $this->facebook = $facebook;
        $this->contentManager = $disabledContentManager;
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

        $slugify = new Slugify();
        $user = new OAuthUser($response->getRealName());
        $user->setId($response->getUsername());
        $user->setEmail($response->getEmail());
        $user->setSlugifiedName($slugify->slugify($response->getRealName()));

        $site
            ->setOAuthUser($user)
            ->setUserName($user->getSlugifiedName())
            ->setAccessToken($response->getAccessToken());

        $permissions = $this->facebook->getPermissions($site);
        $site->setGivenScopes($permissions);

        if ($site->hasScope("user_photos")) {
            $albums = [];
            foreach ($response->getData()['albums']['data'] as $data) {
                $isAlbumEnabled = $this->getCreatedTime($data) > $site->getCreatedAt();
                $albumContent = $this->contentManager->getOrCreateAlbum($site, $data['id'], $isAlbumEnabled);

                // If ID is null, new AlbumContent
                if (null === $albumContent->getId()) {
                    $site->addDisabledContent($albumContent);
                }

                $photos = [];
                if (isset($data['photos'])) {
                    foreach ($data['photos']['data'] as $photoData) {
                        $isPictureEnabled = $this->getCreatedTime($photoData) > $site->getCreatedAt();
                        $pictureContent = $this->contentManager->getOrCreatePicture($site, $photoData['id'], $data['id'], $isPictureEnabled);

                        // If ID is null, new PictureContent
                        if (null === $pictureContent->getId()) {
                            $site->addDisabledContent($pictureContent);
                        }

                        $webpImages = [];
                        foreach ($photoData['webp_images'] as $webpImageData) {
                            $webpImage = new WebpImage($webpImageData['source'], $pictureContent->isEnabled());
                            $webpImages[] = $webpImage;
                        }

                        $photo = new Picture($photoData['id'], $photoData['picture'], $webpImages, $pictureContent->isEnabled());
                        $photos[] = $photo;
                    }
                }

                $album = new Album($data['id'], $data['name'], $photos, $albumContent->isEnabled());

                $albums[] = $album;
            }

            $user->setAlbums($albums);
        }

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

    /**
     * @return ObjectManager
     */
    public function getManager()
    {
        return $this->manager;
    }

    /**
     * @return Site
     */
    public function getSite()
    {
        return $this->site;
    }

    /**
     * @param Site $site
     */
    public function setSite($site)
    {
        $this->site = $site;
    }

    /**
     * @return Facebook
     */
    public function getFacebook(): Facebook
    {
        return $this->facebook;
    }

    /**
     * @param $data
     * @return \DateTime
     */
    private function getCreatedTime($data)
    {
        return new \DateTime($data['created_time']);
    }
}
