<?php
/**
 * Created by PhpStorm.
 * User: francois.mathieu
 * Date: 06/12/2017
 * Time: 10:43
 */

namespace AppBundle\EventListener;


use AppBundle\Entity\Site;
use AppBundle\Manager\SiteManager;
use AppBundle\Security\Core\User\OAuthUser;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

class CurrentSiteListener
{
    private $siteManager;
    private $baseHost;
    /**
     * @var TokenStorage
     */
    private $tokenStorage;

    public function __construct(SiteManager $siteManager, TokenStorage $tokenStorage, $baseHost)
    {
        $this->siteManager = $siteManager;
        $this->baseHost = $baseHost;
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * @param GetResponseEvent $event
     */
    public function onKernelRequest(GetResponseEvent $event)
    {
        $currentHost = $event->getRequest()->getHttpHost();
        $subdomain = str_replace('.' . $this->baseHost, '', $currentHost);

        $controller = explode("::", $event->getRequest()->attributes->get('_controller'))[0];
        //If the controller is not in the array, it is not a site route
        if (!in_array($controller, ["AppBundle\Controller\SiteController", "AppBundle\Controller\AdminController"])) {
            return;
        }

        //Handle subdomain (ex: francois-mathieu.myphotobook.ovh
        if (null !== $site = $this->isSubDomain($subdomain)) {
            $site = $this->siteManager->generateOAuthUser($site);
            $this->siteManager->setSite($site);

            return;
        }

        //Handle admin page
        if (null !== $user = $this->getUser()) {
            if (null !== $site = $this->isAdmin()) {
                $site->setOAuthUser($user);
                $this->siteManager->setSite($site);

                return;
            }
        }

        throw new NotFoundHttpException(sprintf('No site for host "%s", subdomain "%s"', $this->baseHost, $subdomain));
    }

    /**
     * @param $subdomain
     * @return Site|null|object
     */
    public function isSubDomain($subdomain)
    {
        if ($subdomain != $this->baseHost) {
            return $this->siteManager->getManager()->getRepository("AppBundle:Site")->findOneBy(['userName' => $subdomain]);
        }

        return null;
    }

    /**
     * @return Site|null|object
     */
    public function isAdmin()
    {
        return $site = $this->siteManager->getManager()->getRepository("AppBundle:Site")->findOneBy(['userId' => $this->getUser()->getId()]);
    }

    /**
     * @return OAuthUser|null
     */
    private function getUser()
    {
        if (null === $token = $this->tokenStorage->getToken()) {
            return null;
        }

        if (!is_object($user = $token->getUser())) {
            return null;
        }

        return $user;
    }


}