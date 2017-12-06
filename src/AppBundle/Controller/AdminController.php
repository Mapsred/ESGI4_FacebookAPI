<?php
/**
 * Created by PhpStorm.
 * User: francois.mathieu
 * Date: 06/12/2017
 * Time: 08:59
 */

namespace AppBundle\Controller;


use AppBundle\Security\Core\User\OAuthUser;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class AdminController
 *
 * @author François MATHIEU <francois.mathieu@livexp.fr>
 * @method OAuthUser getUser()
 */
class AdminController extends Controller
{
    /**
     * @Route("/admin", name="admin_index")
     */
    public function indexAction()
    {
        return $this->render('AppBundle:Admin:index.html.twig');
    }
}