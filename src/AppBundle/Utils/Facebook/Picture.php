<?php
/**
 * Created by PhpStorm.
 * User: francois.mathieu
 * Date: 04/12/2017
 * Time: 14:56
 */

namespace AppBundle\Utils\Facebook;

use Doctrine\Common\Collections\ArrayCollection;

class Picture
{
    /** @var int $id */
    private $id;
    /** @var string $picture */
    private $picture;
    /** @var WebpImage[]|ArrayCollection $webpImages */
    private $webpImages;

    /**
     * Picture constructor.
     * @param $id
     * @param $picture
     * @param $webpImages
     */
    public function __construct($id, $picture, $webpImages)
    {
        $this->id = $id;
        $this->picture = $picture;
        $this->setWebpImages($webpImages);
    }


    /**
     * @return int
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
     * @param WebpImage[]|ArrayCollection $webpImages
     * @return Picture
     */
    public function setWebpImages($webpImages)
    {
        if (is_array($webpImages)) {
            $webpImages = new ArrayCollection($webpImages);
        }

        $this->webpImages = $webpImages;

        return $this;
    }

    /**
     * @return WebpImage[]|ArrayCollection
     */
    public function getWebpImages()
    {
        return $this->webpImages;
    }
}
