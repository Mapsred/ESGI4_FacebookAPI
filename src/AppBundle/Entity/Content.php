<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Content
 *
 * @ORM\Table(name="content")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ContentRepository")
 */
class Content
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
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=255)
     */
    private $type;

    /**
     * @var Site $site
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Site", inversedBy="disabledContent", cascade={"persist"})
     * @ORM\JoinColumn(name="site", referencedColumnName="id", nullable=true)
     */
    private $site;

    /**
     * @var string
     *
     * @ORM\Column(name="content_id", type="string")
     */
    private $contentId;

    /**
     * @var bool
     *
     * @ORM\Column(name="enabled", type="boolean")
     */
    private $enabled = false;

    /**
     * @var string
     *
     * @ORM\Column(name="album_id", type="string", nullable=true)
     */
    private $albumId;

    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set type.
     *
     * @param string $type
     *
     * @return Content
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type.
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set contentId.
     *
     * @param string $contentId
     *
     * @return Content
     */
    public function setContentId($contentId)
    {
        $this->contentId = $contentId;

        return $this;
    }

    /**
     * Get contentId.
     *
     * @return string
     */
    public function getContentId()
    {
        return $this->contentId;
    }

    /**
     * Set site.
     *
     * @param Site|null $site
     *
     * @return Content
     */
    public function setSite(Site $site = null)
    {
        $this->site = $site;

        return $this;
    }

    /**
     * Get site.
     *
     * @return Site|null
     */
    public function getSite()
    {
        return $this->site;
    }

    /**
     * Set enabled.
     *
     * @param bool $enabled
     *
     * @return Content
     */
    public function setEnabled($enabled)
    {
        $this->enabled = $enabled;

        return $this;
    }

    /**
     * Get enabled.
     *
     * @return bool
     */
    public function isEnabled()
    {
        return $this->enabled;
    }

    /**
     * Get enabled.
     *
     * @return bool
     */
    public function getEnabled()
    {
        return $this->enabled;
    }

    /**
     * Set albumId.
     *
     * @param string|null $albumId
     *
     * @return Content
     */
    public function setAlbumId($albumId = null)
    {
        $this->albumId = $albumId;

        return $this;
    }

    /**
     * Get albumId.
     *
     * @return string|null
     */
    public function getAlbumId()
    {
        return $this->albumId;
    }
}
