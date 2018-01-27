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
     * @var bool enabled
     */
    private $enabled;

    /**
     * Picture constructor.
     * @param string $source
     * @param bool $enabled
     */
    public function __construct($source, $enabled = true)
    {
        $this->source = $source;
        $this->enabled = $enabled;
    }

    /**
     * @return string
     */
    public function getSource()
    {
        return $this->source;
    }

    /**
     * @return bool
     */
    public function isEnabled(): bool
    {
        return $this->enabled;
    }

}
