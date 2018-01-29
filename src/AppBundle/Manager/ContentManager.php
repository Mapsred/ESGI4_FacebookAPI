<?php
/**
 * Created by PhpStorm.
 * User: Maps_red
 * Date: 29/01/2018
 * Time: 19:39
 */

namespace AppBundle\Manager;

use AppBundle\Entity\Content;
use AppBundle\Entity\Site;
use AppBundle\Repository\ContentRepository;
use AppBundle\Utils\Facebook\Album;
use AppBundle\Utils\Facebook\Picture;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Class ContentManager
 * @package AppBundle\Manager
 * @method ContentManager persistAndFlush($entity)
 * @method ContentManager removeEntity($entity)
 * @method ContentRepository getRepository()
 * @method Content newClass()
 */
class ContentManager extends BaseManager
{
    const ALBUM = "album";
    const PICTURE = "picture";

    /**
     * DisabledContentManager constructor.
     * @param ObjectManager $manager
     */
    public function __construct(ObjectManager $manager)
    {
        parent::__construct($manager, Content::class);
    }


    /**
     * @param Site $site
     * @param $picture
     * @param bool $enabled
     * @return Content
     */
    public function addPicture(Site $site, $picture, $enabled = false)
    {
        return $this->newClass()
            ->setContentId($picture instanceof Picture ? $picture->getId() : $picture)
            ->setSite($site)
            ->setType(self::PICTURE)
            ->setEnabled($enabled);
    }

    /**
     * @param Site $site
     * @param $pictures
     * @param bool $enabled
     * @return Content[]
     */
    public function addPictures(Site $site, $pictures, $enabled = false)
    {
        $entities = [];
        foreach ($pictures as $picture) {
            $entity = $this->addPicture($site, $picture, $enabled);
            $this->getManager()->persist($entity);
            $entities[] = $entity;
        }

        $this->getManager()->flush();

        return $entities;
    }





    /**
     * @param Site $site
     * @param $album
     * @param bool $enabled
     * @return Content
     */
    public function addAlbum(Site $site, $album, $enabled = false)
    {
        return $this->newClass()
            ->setContentId($album instanceof Album ? $album->getId() : $album)
            ->setSite($site)
            ->setType(self::ALBUM)
            ->setEnabled($enabled);
    }

    /**
     * @param Site $site
     * @param $albums
     * @param bool $enabled
     * @return Content[]
     */
    public function addAlbums(Site $site, $albums, $enabled = false)
    {
        $entities = [];
        foreach ($albums as $album) {
            $entity = $this->addAlbum($site, $album, $enabled);
            $this->getManager()->persist($entity);
            $entities[] = $entity;
        }

        $this->getManager()->flush();

        return $entities;
    }

    /**
     * @param Site $site
     * @param $albums
     * @param bool $enabled
     * @return ContentManager
     */
    public function switchAlbumsStatus(Site $site, $albums, $enabled = false)
    {
        $albums = $this->arrayCollectionToArray($albums);
        foreach ($site->getContentAlbums() as $content) {
            if (in_array($content->getContentId(), $albums)) {
                $content->setEnabled($enabled);
            }else {
                $content->setEnabled(!$enabled);
            }

            $this->getManager()->persist($content);

        }

        return $this;
    }


    /**
     * @param Site $site
     * @param $picture
     * @return bool
     */
    public function isPictureEnabled(Site $site, $picture)
    {
        return $this->isPicture($site, $picture, true);
    }

    /**
     * @param Site $site
     * @param $picture
     * @return bool
     */
    public function isPictureDisabled(Site $site, $picture)
    {
        return $this->isPicture($site, $picture, false);
    }

    /**
     * @param Site $site
     * @param $picture
     * @return bool
     */
    public function isPicturePresent(Site $site, $picture)
    {
        return $this->isPicture($site, $picture);
    }


    /**
     * @param Site $site
     * @param $album
     * @return bool
     */
    public function isAlbumEnabled(Site $site, $album)
    {
        return $this->isAlbum($site, $album, true);
    }

    /**
     * @param Site $site
     * @param $album
     * @return bool
     */
    public function isAlbumDisabled(Site $site, $album)
    {
        return $this->isAlbum($site, $album, false);
    }

    /**
     * @param Site $site
     * @param $album
     * @return bool
     */
    public function isAlbumPresent(Site $site, $album)
    {
        return $this->isAlbum($site, $album);
    }


    /**
     * @param Site $site
     * @param $id
     * @param $type
     * @param null $enabled
     * @return Content
     */
    private function getObject(Site $site, $id, $type, $enabled = null)
    {
        $criteras = ['contentId' => $id, 'enabled' => $enabled, 'site' => $site, 'type' => $type];
        if (null === $enabled) {
            unset($criteras['enabled']);
        }

        return $this->getRepository()->findOneBy($criteras);
    }

    /**
     * @param Site $site
     * @param $id
     * @param $type
     * @param bool $enabled
     * @return bool
     */
    private function isObject(Site $site, $id, $type, $enabled = null)
    {
        return !empty($this->getObject($site, $id, $type, $enabled));
    }


    /**
     * @param Site $site
     * @param $id
     * @param $type
     * @param null $enabled
     * @return Content
     */
    public function getPicture(Site $site, $id, $enabled = null)
    {
        return $this->getObject($site, $id, self::PICTURE, $enabled);
    }

    /**
     * @param Site $site
     * @param $id
     * @param bool $enabled
     * @return Content
     */
    public function getOrCreatePicture(Site $site, $id, $enabled = true)
    {
        if (null === $entity = $this->getPicture($site, $id)) {
            $entity = $this->addPicture($site, $id, $enabled);
        }

        return $entity;
    }

    /**
     * @param Site $site
     * @param $picture
     * @param bool $enabled
     * @return bool
     */
    private function isPicture(Site $site, $picture, $enabled = null)
    {
        $id = $picture instanceof Picture ? $picture->getId() : $picture;

        return $this->isObject($site, $id, self::PICTURE, $enabled);
    }


    /**
     * @param Site $site
     * @param $id
     * @param $type
     * @param null $enabled
     * @return Content
     */
    public function getAlbum(Site $site, $id, $enabled = null)
    {
        return $this->getObject($site, $id, self::ALBUM, $enabled);
    }

    /**
     * @param Site $site
     * @param $id
     * @param bool $enabled
     * @return Content
     */
    public function getOrCreateAlbum(Site $site, $id, $enabled = false)
    {
        if (null === $entity = $this->getAlbum($site, $id)) {
            $entity = $this->addAlbum($site, $id, $enabled);
        }

        return $entity;
    }

    /**
     * @param Site $site
     * @param $album
     * @param bool $enabled
     * @return bool
     */
    private function isAlbum(Site $site, $album, $enabled = null)
    {
        $id = $album instanceof Album ? $album->getId() : $album;

        return $this->isObject($site, $id, self::ALBUM, $enabled);
    }

    /**
     * @param ArrayCollection|array $collection
     * @return array
     */
    private function arrayCollectionToArray($collection)
    {
        if ($collection instanceof ArrayCollection) {
            $collection = $collection->map(function (Content $content) {
                return $content->getContentId();
            });
        }

        return $collection;
    }

}