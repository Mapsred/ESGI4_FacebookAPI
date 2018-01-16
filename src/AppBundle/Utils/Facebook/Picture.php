<?php
/**
 * Created by PhpStorm.
 * User: francois.mathieu
 * Date: 04/12/2017
 * Time: 14:56
 */

namespace AppBundle\Utils\Facebook;

class Picture
{
    /** @var int $id */
    private $id;
    /** @var string $picture */
    private $picture;
    /** @var WebpImages[]|ArrayCollection $webpimages */
    private $webpimages;


    /**
     * Picture constructor.
     * @param $id
     * @param $picture
     * @param $webpimages
     */

    public function __construct($id, $picture, $webpimages)
    {
        $this->id = $id;
        $this->picture = $picture;
        $this->webpimages = $webpimages;
    }


    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    public function getPicture()
    {
        return file_get_contents($this->picture);
    }

    public function getPictureLink()
    {
        return $this->picture;
    }

    /**
     * @return WebpImages[]|ArrayCollection
     */
    public function getWebpimages()
    {
        return $this->webpimages;
    }
}
