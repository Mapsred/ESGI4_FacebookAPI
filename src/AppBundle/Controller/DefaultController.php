<?php

namespace AppBundle\Controller;

use AppBundle\Security\Core\User\OAuthUser;
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
}
