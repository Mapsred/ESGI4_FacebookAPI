<?php

namespace AppBundle\Controller;

use AppBundle\Security\Core\User\OAuthUser;
use Cocur\Slugify\Slugify;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class DefaultController
 *
 * @author François MATHIEU <francois.mathieu@livexp.fr>
 * @method OAuthUser getUser()
 */
class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     * @return Response
     */
    public function indexAction()
    {
        $sites = $this->getDoctrine()->getRepository('AppBundle:Site')->findAll();

        return $this->render('AppBundle:Default:index.html.twig', [
            'sites' => $sites
        ]);
    }

    /**
     * @Route("/politique-de-confidentialité", name="policy")
     */
    public function policyAction()
    {
        return $this->render('AppBundle:Default:policy.html.twig');
    }

    /**
     * @Route("/conditions-de-service", name="service_condition")
     */
    public function serviceConditionAction()
    {
        return $this->render('AppBundle:Default:service_condition.html.twig');
    }

    /**
     * @Route("/redirect_to_page", name="redirect_to_page")
     */
    public function redirectToPageAction()
    {
        $slugify = new Slugify();

        return $this->getUser() instanceof OAuthUser ? $this->redirectToRoute('site_home', [
            'project_name' => $slugify->slugify($this->getUser()->getName())
        ]) : $this->redirectToRoute('homepage');
    }
}
