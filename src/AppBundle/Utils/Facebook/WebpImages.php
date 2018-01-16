<?php
/**
 * Created by PhpStorm.
 * User: francois.mathieu
 * Date: 04/12/2017
 * Time: 14:56
 */

namespace AppBundle\Utils\Facebook;

class WebpImages
{
    /** @var int $id */
    private $source;

    /**
     * Picture constructor.
     * @param $id
     * @param $picture
     */
    public function __construct($source)
    {
        $this->source = $source;
    }


    public function getSource()
    {
        return $this->source;
    }

}
