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

/**
 * Class CurrentSiteListener
 *
 * @author François MATHIEU <francois.mathieu@livexp.fr>
 */
class CurrentSiteListener
{
    /**
     * @var SiteManager $siteManager
     */
    private $siteManager;

    /**
     * @var string $baseHost
     */
    private $baseHost;

    /**
     * @var TokenStorage $tokenStorage
     */
    private $tokenStorage;

    /**
     * CurrentSiteListener constructor.
     * @param SiteManager $siteManager
     * @param TokenStorage $tokenStorage
     * @param string $baseHost
     */
    public function __construct(SiteManager $siteManager, TokenStorage $tokenStorage, string $baseHost)
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
        $request = $event->getRequest();
        $currentHost = $request->getHttpHost();
        $subdomain = str_replace('.' . $this->baseHost, '', $currentHost);
        $request->attributes->set('_subdomain', $subdomain);

        $controller = explode("::", $request->attributes->get('_controller'))[0];
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

        throw new NotFoundHttpException(sprintf('No site for host "%s", subdomain "%s"', $this->baseHost, $subdomain));
    }

    /**
     * @param string $subdomain
     * @return Site|null|object
     */
    public function isSubDomain(string $subdomain)
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