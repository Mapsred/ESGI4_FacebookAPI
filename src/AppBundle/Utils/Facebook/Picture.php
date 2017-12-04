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

    /**
     * Picture constructor.
     * @param $id
     * @param $picture
     */
    public function __construct($id, $picture)
    {
        $this->id = $id;
        $this->picture = $picture;
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
}