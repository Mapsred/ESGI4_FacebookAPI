<?php
/**
 * Created by PhpStorm.
 * User: francois.mathieu
 * Date: 04/12/2017
 * Time: 14:58
 */

namespace AppBundle\Utils\Facebook;


use Doctrine\Common\Collections\ArrayCollection;

class Album
{
    /** @var int $id */
    private $id;
    /** @var Picture[]|ArrayCollection $pictures */
    private $pictures;
    /** @var string $name */
    private $name;

    /**
     * Album constructor.
     * @param int $id
     * @param Picture[]|ArrayCollection $pictures
     * @param string $name
     */
    public function __construct($id, $name, $pictures)
    {
        $this->id = $id;
        $this->setPictures($pictures);
        $this->name = $name;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return Picture[]|ArrayCollection
     */
    public function getPictures()
    {
        return $this->pictures;
    }

    /**
     * @param Picture[]|ArrayCollection $pictures
     * @return Album
     */
    public function setPictures($pictures)
    {
        if (is_array($pictures)) {
            $pictures = new ArrayCollection($pictures);
        }

        $this->pictures = $pictures;

        return $this;
    }

    /**
     * @param Picture $picture
     * @return Album
     */
    public function addPicture(Picture $picture)
    {
        if (!$this->pictures->contains($picture)) {
            $this->pictures->add($picture);
        }

        return $this;
    }

    /**
     * @param Picture $picture
     * @return Album
     */
    public function removePicture(Picture $picture)
    {
        if ($this->pictures->contains($picture)) {
            $this->pictures->removeElement($picture);
        }

        return $this;
    }


}