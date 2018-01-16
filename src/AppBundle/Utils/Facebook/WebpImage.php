<?php
/**
 * Created by PhpStorm.
 * User: francois.mathieu
 * Date: 04/12/2017
 * Time: 14:56
 */

namespace AppBundle\Utils\Facebook;

class WebpImage
{
    /** @var static $source */
    private $source;

    /**
     * Picture constructor.
     * @param string $source
     */
    public function __construct($source)
    {
        $this->source = $source;
    }

    /**
     * @return string
     */
    public function getSource()
    {
        return $this->source;
    }

}
