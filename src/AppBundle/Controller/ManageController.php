<?php
/**
 * Created by PhpStorm.
 * User: francois.mathieu
 * Date: 16/01/2018
 * Time: 16:09
 */

namespace AppBundle\Controller;


use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ManageController extends Controller
{
    /**
     * @Route("/", name="manage_home")
     */
    public function homeManageAction()
    {
        return $this->render('AppBundle:Manage:index.html.twig');
    }

    /**
     * @Route("/edit", name="manage_edit")
     * @param Request $request
     * @return Response|RedirectResponse
     */
    public function adminAction(Request $request)
    {
        if ($request->isMethod('POST')) {
            foreach ($request->request->get('site') as $site) {
                if (null !== $site = $this->getDoctrine()->getRepository('AppBundle:Site')->findOneBy(['id' => $site])) {
                    $this->getDoctrine()->getManager()->remove($site);
                }
            }
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('manage_edit');
        }

        return $this->render('AppBundle:Manage:edit.html.twig', [
            'sites' => $this->getDoctrine()->getRepository('AppBundle:Site')->findAll()
        ]);

    }
}