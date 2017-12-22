<?php
/**
 * Created by PhpStorm.
 * User: francois.mathieu
 * Date: 04/12/2017
 * Time: 12:40
 */

namespace AppBundle\Security\Core\User;

use AppBundle\Utils\Facebook\Album;
use Doctrine\Common\Collections\ArrayCollection;
use HWI\Bundle\OAuthBundle\Security\Core\User\OAuthUser as BaseOAuthUser;

class OAuthUser extends BaseOAuthUser
{
    /**
     * @var int $id
     */
    private $id;

    /**
     * @var Album[]|ArrayCollection $albums
     */
    private $albums;

    /**
     * @var string $email
     */
    private $email;

    /**
     * OAuthUser constructor.
     * @param $username
     */
    public function __construct($username)
    {
        parent::__construct($username);
        $this->albums = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->getUsername();
    }

    /**
     * @return Album[]|ArrayCollection
     */
    public function getAlbums()
    {
        return $this->albums;
    }

    /**
     * @param Album[]|ArrayCollection $albums
     * @return OAuthUser
     */
    public function setAlbums($albums)
    {
        if (is_array($albums)) {
            $albums = new ArrayCollection($albums);
        }

        $this->albums = $albums;

        return $this;
    }

    /**
     * @param Album $album
     * @return OAuthUser
     */
    public function addAlbum(Album $album)
    {
        if (!$this->albums->contains($album)) {
            $this->albums->add($album);
        }

        return $this;
    }

    /**
     * @param Album $album
     * @return OAuthUser
     */
    public function removeAlbum(Album $album)
    {
        if ($this->albums->contains($album)) {
            $this->albums->removeElement($album);
        }

        return $this;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $email
     * @return OAuthUser
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @param $role
     * @return bool
     */
    public function hasRole($role)
    {
        return in_array($role, $this->getRoles());
    }

}