<?php
/**
 * Created by PhpStorm.
 * User: francois.mathieu
 * Date: 06/12/2017
 * Time: 08:59
 */

namespace AppBundle\Controller;

use AppBundle\Entity\Site;
use AppBundle\Manager\SiteManager;
use AppBundle\Security\Core\User\OAuthUser;
use AppBundle\Utils\Facebook\Facebook;
use Facebook\GraphNodes\GraphNode;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\File\UploadedFile;
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
     * @Route("/", name="admin_index")
     * @return Response
     */
    public function indexAction()
    {
        $site = $this->get('AppBundle\Manager\SiteManager')->getSite();

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
        $site = $this->get('AppBundle\Manager\SiteManager')->getSite();

        return $this->render('AppBundle:Admin:color_choice.html.twig', [
            'site' => $site
        ]);
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

        if (null !== $redirectResponse = $this->verifyAndRedirect($site, 'publish_action', "Pour pouvoir ajouter des photos")) {
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
     * @Route("/albums", name="admin_albums")
     * @return Response
     */
    public function albumsAction()
    {
        $site = $this->get('AppBundle\Manager\SiteManager')->getSite();

        return $this->render('AppBundle:Admin:albums.html.twig', [
            'site' => $site
        ]);
    }

    /**
     * @Route("/album/{albumName}", name="admin_album")
     * @return Response
     */
    public function albumAction($albumName = null)
    {
        $album_name = ucwords(str_replace("-", " ", $albumName));

        $site = $this->get('AppBundle\Manager\SiteManager')->getSite();

        $album = $site->getOAuthUser()->getAlbums()->get(4);

        return $this->render('AppBundle:Admin:album.html.twig', [
            'site' => $site,
            'album' => $album

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
