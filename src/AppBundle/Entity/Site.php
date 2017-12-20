<?php

namespace AppBundle\Entity;

use AppBundle\Security\Core\User\OAuthUser;
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
     * @var array $albumOptions
     *
     * @ORM\Column(name="album_options", type="json_array", nullable=true)
     */
    private $albumOptions;

    /**
     * @var array $photoOptions
     *
     * @ORM\Column(name="photo_options", type="json_array", nullable=true)
     */
    private $photoOptions;

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
     * @param array $albumOptions
     *
     * @return Site
     */
    public function setAlbumOptions($albumOptions)
    {
        $this->albumOptions = $albumOptions;

        return $this;
    }

    /**
     * Get albumOptions
     *
     * @return array
     */
    public function getAlbumOptions()
    {
        return $this->albumOptions;
    }

    /**
     * Set photoOptions
     *
     * @param array $photoOptions
     *
     * @return Site
     */
    public function setPhotoOptions($photoOptions)
    {
        $this->photoOptions = $photoOptions;

        return $this;
    }

    /**
     * Get photoOptions
     *
     * @return array
     */
    public function getPhotoOptions()
    {
        return $this->photoOptions;
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
        return isset($this->givenScopes[$scope]);
    }
}

