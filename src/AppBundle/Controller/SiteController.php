<?php
/**
 * Created by PhpStorm.
 * User: francois.mathieu
 * Date: 05/12/2017
 * Time: 10:52
 */

namespace AppBundle\Controller;

use AppBundle\Manager\SiteManager;
use AppBundle\Security\Core\User\OAuthUser;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class SiteController
 *
 * @author François MATHIEU <francois.mathieu@livexp.fr>
 * @method OAuthUser getUser()
 */
class SiteController extends Controller
{
    /**
     * @Route("/", name="site_home")
     * @return Response
     */
    public function siteAction()
    {
        $site = $this->get(SiteManager::class)->getSite();

        return $this->render('AppBundle:Site:home.html.twig', [
            'site' => $site
        ]);
    }
}
