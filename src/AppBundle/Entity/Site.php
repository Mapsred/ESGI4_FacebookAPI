<?php

namespace AppBundle\Entity;

use AppBundle\Manager\ContentManager;
use AppBundle\Security\Core\User\OAuthUser;
use Doctrine\Common\Collections\ArrayCollection;
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

    /**
     * @var ArrayCollection|Content[] $disabledContent
     * @ORM\OneToMany(targetEntity="Content", mappedBy="site", cascade={"persist"})
     */
    private $disabledContent;

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
     * Add disabledContent.
     *
     * @param Content $disabledContent
     *
     * @return Site
     */
    public function addDisabledContent(Content $disabledContent)
    {
        $this->disabledContent[] = $disabledContent;

        return $this;
    }

    /**
     * Remove disabledContent.
     *
     * @param Content $disabledContent
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeDisabledContent(Content $disabledContent)
    {
        return $this->disabledContent->removeElement($disabledContent);
    }

    /**
     * Get disabledContent.
     *
     * @return ArrayCollection|Content[]
     */
    public function getDisabledContent()
    {
        return $this->disabledContent;
    }

    /**
     * @return ArrayCollection
     */
    public function getPictures()
    {
        return $this->getContentPictures()->map(function (Content $content) {
            return $content->getContentId();
        });
    }

    /**
     * @return ArrayCollection|Content[]
     */
    public function getContentPictures()
    {
        $content = $this->getDisabledContent()->map(function (Content $content) {
            if ($content->getType() == ContentManager::PICTURE) {
                return $content;
            }
        });

        return new ArrayCollection(array_filter($content->toArray()));
    }

    /**
     * @return ArrayCollection
     */
    public function getAlbums()
    {
        return $this->getContentAlbums()->map(function (Content $content) {
            return $content->getContentId();
        });
    }

    /**
     * @return ArrayCollection|Content[]
     */
    public function getContentAlbums()
    {
        $content = $this->getDisabledContent()->map(function (Content $content) {
            if ($content->getType() == ContentManager::ALBUM) {
                return $content;
            }
        });

        return new ArrayCollection(array_filter($content->toArray()));
    }
}
