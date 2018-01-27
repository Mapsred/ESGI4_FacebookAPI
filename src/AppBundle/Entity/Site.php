<?php

namespace AppBundle\Entity;

use AppBundle\Security\Core\User\OAuthUser;
use AppBundle\Utils\Facebook\Album;
use AppBundle\Utils\Facebook\Picture;
use Doctrine\ORM\Mapping as ORM;

/**
 * Site
 *
 * @ORM\Table(name="site")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\SiteRepository")
 */
class Site
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string $userId
     *
     * @ORM\Column(name="user_id", type="string", length=255)
     */
    private $userId;

    /**
     * @var string $accessToken
     *
     * @ORM\Column(name="access_token", type="string", length=255)
     */
    private $accessToken;

    /**
     * @var string $userName
     *
     * @ORM\Column(name="user_name", type="string", length=255)
     */
    private $userName;

    /**
     * @var string $skinColor
     *
     * @ORM\Column(name="skin_color", type="string", length=255)
     */
    private $skinColor = 'skin-blue';

    /**
     * @var array $disabledAlbums
     *
     * @ORM\Column(name="disabled_albums", type="json_array", nullable=true)
     */
    private $disabledAlbums;

    /**
     * @var array $disabledPictures
     *
     * @ORM\Column(name="disabled_pictures", type="json_array", nullable=true)
     */
    private $disabledPictures;

    /**
     * @var OAuthUser $OAuthUser
     */
    private $OAuthUser;

    /**
     * @var array $givenScopes
     *
     * @ORM\Column(name="given_scopes", type="json_array", nullable=true)
     */
    private $givenScopes;

    /**
     * @var \DateTime $createdAt
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=true)
     */
    private $createdAt;

    public function __construct()
    {
        $this->disabledPictures = [];
        $this->disabledAlbums = [];
    }

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set userId
     *
     * @param string $userId
     *
     * @return Site
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;

        return $this;
    }

    /**
     * Get userId
     *
     * @return string
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * Set userName
     *
     * @param string $userName
     *
     * @return Site
     */
    public function setUserName($userName)
    {
        $this->userName = $userName;

        return $this;
    }

    /**
     * Get userName
     *
     * @return string
     */
    public function getUserName()
    {
        return $this->userName;
    }

    /**
     * Set albumOptions
     *
     * @param array $disabledAlbums
     *
     * @return Site
     */
    public function setDisabledAlbums($disabledAlbums)
    {
        $this->disabledAlbums = $disabledAlbums;

        return $this;
    }

    /**
     * Get albumOptions
     *
     * @return array
     */
    public function getDisabledAlbums()
    {
        return $this->disabledAlbums;
    }

    /**
     * @param $album
     * @return Site
     */
    public function disableAlbum($album)
    {
        $this->disabledAlbums[] = $album;

        return $this;
    }

    /**
     * @param $album
     * @return Site
     */
    public function enableAlbum($album)
    {
        $key = array_search($album, $this->disabledAlbums);

        if (isset($this->disabledAlbums[$key])) {
            unset($this->disabledAlbums[$key]);
        }

        return $this;
    }

    /**
     * @param $photo
     * @return Site
     */
    public function disablePicture($photo)
    {
        $this->disabledPictures[] = $photo;

        return $this;
    }

    /**
     * Set photoOptions
     *
     * @param array $disabledPictures
     *
     * @return Site
     */
    public function setDisabledPictures($disabledPictures)
    {
        $this->disabledPictures = $disabledPictures;

        return $this;
    }

    /**
     * Get photoOptions
     *
     * @return array
     */
    public function getDisabledPictures()
    {
        return $this->disabledPictures;
    }

    /**
     * @return string
     */
    public function getAccessToken()
    {
        return $this->accessToken;
    }

    /**
     * @param string $accessToken
     * @return Site
     */
    public function setAccessToken($accessToken)
    {
        $this->accessToken = $accessToken;

        return $this;
    }

    /**
     * @return OAuthUser
     */
    public function getOAuthUser()
    {
        return $this->OAuthUser;
    }

    /**
     * @param OAuthUser $OAuthUser
     * @return Site
     */
    public function setOAuthUser($OAuthUser)
    {
        $this->OAuthUser = $OAuthUser;

        return $this;
    }

    /**
     * @return array
     */
    public function getGivenScopes()
    {
        return $this->givenScopes;
    }

    /**
     * @param array $givenScopes
     * @return Site
     */
    public function setGivenScopes($givenScopes): Site
    {
        $this->givenScopes = $givenScopes;
        return $this;
    }

    /**
     * @param $scope
     * @return bool
     */
    public function hasScope($scope)
    {
        return in_array($scope, $this->givenScopes);
    }

    /**
     * Set skinColor
     *
     * @param string $skinColor
     *
     * @return Site
     */
    public function setSkinColor($skinColor)
    {
        $this->skinColor = $skinColor;

        return $this;
    }

    /**
     * Get skinColor
     *
     * @return string
     */
    public function getSkinColor()
    {
        return $this->skinColor;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param \DateTime $createdAt
     * @return Site
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }


    /**
     * @param Album|int $album
     * @return bool
     */
    public function isAlbumDisabled($album)
    {
        $options = $this->getDisabledAlbums();
        $options = is_array($options) ? $options : [];
        $albumId = $album instanceof Album ? $album->getId() : $album;
        $key = array_search($albumId, $options);
        if (false !== $key) {
            return isset($options[$key]);
        }

        return false;
    }

    /**
     * @param Picture|int $picture
     * @return bool
     */
    public function isPictureDisabled($picture)
    {
        $options = $this->getDisabledPictures();
        $options = is_array($options) ? $options : [];
        $pictureId = $picture instanceof Picture ? $picture->getId() : $picture;
        $key = array_search($pictureId, $options);
        if (false !== $key) {
            return isset($options[$key]);
        }

        return false;
    }
}
