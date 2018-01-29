<?php
/**
 * Created by PhpStorm.
 * User: francois.mathieu
 * Date: 06/12/2017
 * Time: 08:59
 */

namespace AppBundle\Controller;

use AppBundle\Entity\Site;
use AppBundle\Manager\ContentManager;
use AppBundle\Manager\SiteManager;
use AppBundle\Security\Core\User\OAuthUser;
use AppBundle\Utils\Facebook\Album;
use AppBundle\Utils\Facebook\Facebook;
use Facebook\GraphNodes\GraphNode;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
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
     * @Route("/", name="admin_index", options={"expose" = true})
     * @return Response
     */
    public function indexAction()
    {
        $site = $this->get(SiteManager::class)->getSite();

        return $this->render('AppBundle:Admin:index.html.twig', [
            'site' => $site
        ]);
    }

    /**
     * @Route("/color", name="admin_colorChoice")
     * @return Response
     */
    public function colorAction()
    {
        $site = $this->get(SiteManager::class)->getSite();

        return $this->render('AppBundle:Admin:color_choice.html.twig', [
            'site' => $site,
            'colors' => [
                ['blue', 'bleu', 'btn-primary'],
                ['yellow', 'jaune', 'btn-warning'],
                ['green', 'vert', 'btn-success'],
                ['purple', 'violet', 'bg-purple'],
                ['red', 'rouge', 'btn-danger'],
                ['black', 'noir', 'bg-black']
            ]
        ]);
    }

    /**
     * @Route("/editColor", options={ "expose" = true }, name="admin_colorEdit")
     * @param Request $request
     * @Method({"POST"})
     * @return JsonResponse
     */
    public function editColorAction(Request $request)
    {

        if (!$request->request->has('color')) {
            return new JsonResponse('Color Manquant', Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $site = $this->get(SiteManager::class)->getSite();
        $color = $request->request->get('color');

        $em = $this->getDoctrine()->getManager();

        $site->setSkinColor($color);
        $em->persist($site);
        $em->flush();

        $this->addFlash('success', 'La couleur est changée');

        return new JsonResponse('La couleur est changée');

    }

    /**
     * @Route("/upload", name="photo_upload")
     * @param Request $request
     * @return Response
     */
    public function photoUploadAction(Request $request)
    {
        $site = $this->get(SiteManager::class)->getSite();
        if (null !== $redirectResponse = $this->verifyAndRedirect($site, 'user_photos', "Pour pouvoir ajouter des photos")) {
            return $redirectResponse;
        }

        if (null !== $redirectResponse = $this->verifyAndRedirect($site, 'publish_actions', "Pour pouvoir ajouter des photos")) {
            return $redirectResponse;
        }

        $albums = $this->get(Facebook::class)->getAlbums($site);

        $albumIds = array_map(function (GraphNode $album) {
            return $album->getField('id');
        }, $albums->all());

        if ($request->isMethod('POST')) {
            /** @var UploadedFile $photo */
            $photo = $request->files->get('photo');
            $message = $request->request->get('message');
            $album = $request->request->get('album');

            if (!in_array($album, $albumIds)) {
                $this->addFlash('danger', 'L\'album sélectionné n\'existe pas.');

                return $this->redirectToRoute('photo_upload', ['project_name' => $site->getUserName()]);
            }

            if (!$this->get(Facebook::class)->uploadPhoto($site, $album, $message, $photo->getPathname())) {
                $this->addFlash('danger', 'Une erreur s\'est produite, merci de rééssayer plus tard');

                return $this->redirectToRoute('photo_upload', ['project_name' => $site->getUserName()]);
            }

            $this->addFlash('success', 'La photo sélectionnée a bien été ajoutée.');
            //In case of success, we need to refresh the site to add the new photo
            $site = $this->get(SiteManager::class)->generateOAuthUser($site);
            $this->get(SiteManager::class)->setSite($site);


            return $this->redirectToRoute('admin_index', ['project_name' => $site->getUserName()]);
        }

        return $this->render('AppBundle:Admin:photo_upload.html.twig', [
            'site' => $site,
            'albums' => $albums->all()
        ]);
    }

    /**
     * @Route("/albums/{type}", name="admin_albums", defaults={"type": "list"})
     * @param Request $request
     * @param string $type
     * @return Response
     */
    public function albumsAction(Request $request, $type)
    {
        $site = $this->get(SiteManager::class)->getSite();
        if ($request->isMethod('POST')) {
            $albumsToDisable = $request->request->get("albums", []);
            $albumsToDisable = array_keys($albumsToDisable);

            $this->get(ContentManager::class)->switchAlbumsStatus($site, $albumsToDisable, false);
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', 'Les albums sélectionnés ont bien été modifiés');

            return $this->redirectToRoute('admin_albums', ['project_name' => $site->getUserName(), 'type' => $type]);
        }

        return $this->render('AppBundle:Admin:albums.html.twig', [
            'site' => $site,
            'disabledAlbums' => $site->getAlbums(),
            'type' => $type
        ]);
    }

    /**
     * @Route("/album/{album_id}/{type}", name="admin_album", defaults={"type": "list"})
     * @param Request $request
     * @param null $album_id
     * @param string $type
     * @return Response
     */
    public function albumPhotosAction(Request $request, $album_id = null, $type)
    {
        $site = $this->get(SiteManager::class)->getSite();

        $redirectResponse = $this->redirectToRoute('admin_album', [
            'project_name' => $site->getUserName(), 'album_id' => $album_id, 'type' => $type
        ]);

        $album = $site->getOAuthUser()->getAlbums()->filter(function (Album $album) use ($album_id) {
            return $album->getId() == $album_id;
        });

        if ($album->count() == 0) {
            $this->addFlash('danger', "Cet album n'existe pas");

            return $redirectResponse;
        }

        if ($request->isMethod('POST')) {
            $picturesToDisable = $request->request->get("photos", []);
            $picturesToDisable = array_keys($picturesToDisable);

            $this->get(ContentManager::class)->switchPicturesStatus($site, $picturesToDisable, $album_id, false);
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', 'Les photos sélectionnés ont bien été désactivés');

            return $redirectResponse;
        }

        return $this->render('AppBundle:Admin:album.html.twig', [
            'site' => $site,
            'album' => $album->first(),
            'disabledAlbums' => $disabledAlbums = $site->getAlbums(),
            'disabledPhotos' => $site->getPictures(),
            'type' => $type
        ]);
    }

    /**
     * @Route("/album_update/{type}/{album_id}", name="admin_album_update", requirements={"type": "enable|disable"})
     * @param $type
     * @param $album_id
     * @return RedirectResponse
     */
    public function albumUpdateAction($type, $album_id)
    {
        $site = $this->get(SiteManager::class)->getSite();
        $redirectResponse = $this->redirectToRoute('admin_albums', ['project_name' => $site->getUserName(), 'type' => 'large']);

        $album = $site->getOAuthUser()->getAlbums()->filter(function (Album $album) use ($album_id) {
            return $album->getId() == $album_id;
        });

        if ($album->count() == 0) {
            $this->addFlash('danger', "Cet album n'existe pas");

            return $redirectResponse;
        }

        if ($type == "disable") {
            if (null !== $entity = $this->get(ContentManager::class)->getAlbum($site, $album_id, true)) {
                $entity->setEnabled(false);
                $this->get(ContentManager::class)->persistAndFlush($entity);
                $this->addFlash('success', 'L\'album a bien été désactivé.');
            } else {
                $this->addFlash('danger', 'Cet album est déja désactivé.');
            }

            return $redirectResponse;
        }

        if ($type == "enable") {
            if (null !== $entity = $this->get(ContentManager::class)->getAlbum($site, $album_id, false)) {
                $entity->setEnabled(true);
                $this->get(ContentManager::class)->persistAndFlush($entity);
                $this->addFlash('success', 'L\'album a bien été activé.');
            } else {
                $this->addFlash('danger', 'Cet album est déja activé.');
            }

            return $redirectResponse;
        }

        return $redirectResponse;
    }

    /**
     * @Route("/admin_delete_website", name="admin_delete_website")
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function deleteWebsiteAction(Request $request)
    {
        $site = $this->get(SiteManager::class)->getSite();
        if ($request->isMethod('POST')) {
            $this->addFlash('success', 'Votre site a bien été supprimé');
            $this->getDoctrine()->getManager()->remove($site);
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('logout');
        }

        return $this->render('AppBundle:Admin:delete_website.html.twig', [
            'site' => $site
        ]);
    }

    /**
     * @param Site $site
     * @param string $scope
     * @param string $msg
     * @return null|RedirectResponse
     */
    private function verifyAndRedirect(Site $site, string $scope, string $msg)
    {
        if (!$site->hasScope($scope)) {
            $this->addFlash('danger', sprintf('%s, vous devez accepter la permission \'%s\' de facebook', $msg, $scope));

            return $this->redirectToRoute('admin_index', ['project_name' => $site->getUserName()]);
        }

        return null;
    }
}
