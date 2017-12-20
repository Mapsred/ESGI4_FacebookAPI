<?php
/**
 * Created by PhpStorm.
 * User: francois.mathieu
 * Date: 05/12/2017
 * Time: 10:52
 */

namespace AppBundle\Controller;

use AppBundle\Entity\Site;
use AppBundle\Security\Core\User\OAuthUser;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
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
     * @Route("/home", name="site_home")
     * @param Request $request
     * @return Response
     */
    public function siteAction(Request $request)
    {
        $site = $this->get('AppBundle\Manager\SiteManager')->getSite();
        $oauthUser = $site->getOAuthUser();

        return $this->render('AppBundle:Site:home.html.twig');
    }
}