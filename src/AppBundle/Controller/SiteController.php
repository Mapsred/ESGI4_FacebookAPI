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

class SiteController extends Controller
{
    /**
     * @Route("/{site}/home", name="site_home")
     * @param string $site
     */
    public function siteAction($site)
    {
        $site = $this->findSiteOrThrowException($site);

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

        return $site;
    }

}