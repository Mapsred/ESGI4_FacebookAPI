<?php
/**
 * Created by PhpStorm.
 * User: francois.mathieu
 * Date: 06/12/2017
 * Time: 08:59
 */

namespace AppBundle\Controller;


use AppBundle\Entity\Site;
use AppBundle\Security\Core\User\OAuthUser;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

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
     * @return Response
     */
    public function indexAction()
    {
        $site = $this->findSiteOrThrowException();

        return $this->render('AppBundle:Admin:index.html.twig', [
            'site' => $site
        ]);
    }

    /**
     * @return Site|null|object
     * @throws NotFoundHttpException
     */
    private function findSiteOrThrowException()
    {
        if (null !== $site = $this->getDoctrine()->getRepository("AppBundle:Site")->findOneBy(['userId' => $this->getUser()->getId()])) {
            $site->setOAuthUser($this->getUser());

            return $site;
        }

        throw $this->createNotFoundException("Site $site not found");
    }

}