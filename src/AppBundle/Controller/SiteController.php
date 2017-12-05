<?php
/**
 * Created by PhpStorm.
 * User: francois.mathieu
 * Date: 05/12/2017
 * Time: 10:52
 */

namespace AppBundle\Controller;

use AppBundle\Entity\Site;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class SiteController extends Controller
{
    /**
     * @Route("/{site}/home", name="site_home")
     * @param string $site
     * @return Response
     */
    public function siteAction($site)
    {
        $site = $this->findSiteOrThrowException($site);
        $oauthUser = $site->getOAuthUser();

        return new Response();
    }

    /**
     * @param string $site
     * @return Site|null|object
     */
    private function findSiteOrThrowException($site)
    {
        if (null === $site = $this->getDoctrine()->getRepository("AppBundle:Site")->findOneBy(['userName' => $site])) {
            throw $this->createNotFoundException("Site $site not found");
        }

        return $this->get('AppBundle\Manager\SiteManager')->generateOAuthUser($site);
    }

}