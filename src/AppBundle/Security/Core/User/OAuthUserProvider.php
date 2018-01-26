<?php
/**
 * Created by PhpStorm.
 * User: francois.mathieu
 * Date: 04/12/2017
 * Time: 12:41
 */

namespace AppBundle\Security\Core\User;

use AppBundle\Entity\Site;
use AppBundle\Manager\SiteManager;
use Doctrine\Common\Persistence\ObjectManager;
use HWI\Bundle\OAuthBundle\OAuth\Response\UserResponseInterface;
use HWI\Bundle\OAuthBundle\Security\Core\User\OAuthUserProvider as BaseOAuthUserProvider;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\UserInterface;

class OAuthUserProvider extends BaseOAuthUserProvider
{
    /**
     * @var ObjectManager $manager
     */
    private $manager;

    /**
     * @var SiteManager $siteManager
     */
    private $siteManager;

    public function __construct(ObjectManager $manager, SiteManager $siteManager)
    {
        $this->manager = $manager;
        $this->siteManager = $siteManager;
    }

    /**
     * @param string $username
     * @return OAuthUser
     */
    public function loadUserByUsername($username)
    {
        return new OAuthUser($username);
    }

    /**
     * @param UserResponseInterface $response
     * @return OAuthUser|UserInterface
     */
    public function loadUserByOAuthUserResponse(UserResponseInterface $response)
    {
        if (null === $site = $this->manager->getRepository("AppBundle:Site")->findOneBy(['userId' => $response->getUsername()])) {
            $site = new Site();
            $site->setUserId($response->getUsername());
        }
        $site->setUpdatedAt(new \DateTime());
        $site = $this->siteManager->generateOAuthUser($site, $response);

        return $site->getOAuthUser();
    }

    /**
     * @param UserInterface $user
     * @return OAuthUser|UserInterface
     * @throws UnsupportedUserException
     */
    public function refreshUser(UserInterface $user)
    {
        if (!$this->supportsClass(get_class($user))) {
            throw new UnsupportedUserException(sprintf('Unsupported user class "%s"', get_class($user)));
        }

        return $user;
    }

    /**
     * @param string $class
     * @return bool
     */
    public function supportsClass($class)
    {
        return 'AppBundle\\Security\\Core\\User\\OAuthUser' === $class;
    }
}
