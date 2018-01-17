<?php
/**
 * Created by PhpStorm.
 * User: francois.mathieu
 * Date: 05/12/2017
 * Time: 10:52
 */

namespace AppBundle\Controller;

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
     * @Route("/{type}", name="site_home", defaults={"type": "large"})
     * @param $type
     * @return Response
     */
    public function siteAction($type)
    {
        $site = $this->get('AppBundle\Manager\SiteManager')->getSite();

        return $this->render('AppBundle:Site:home.html.twig', [
            'site' => $site,
            'type' => $type
        ]);
    }
}
