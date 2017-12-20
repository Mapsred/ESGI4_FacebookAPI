<?php
/**
 * Created by PhpStorm.
 * User: francois.mathieu
 * Date: 20/12/2017
 * Time: 10:14
 */

namespace AppBundle\Utils\Facebook;

use AppBundle\Entity\Site;
use AppBundle\Manager\SiteManager;
use Facebook\Exceptions\FacebookResponseException;
use Facebook\Exceptions\FacebookSDKException;
use Facebook\Facebook as BaseFacebook;
use Facebook\FacebookResponse;
use Facebook\GraphNodes\GraphEdge;
use Facebook\GraphNodes\GraphNode;

class Facebook extends BaseFacebook
{
    /**
     * @var array $parameters
     */
    private $parameters = [
        'default_graph_version' => 'v2.10',
    ];

    /**
     * Facebook constructor.
     * @param SiteManager $siteManager
     * @param string $facebookClientId
     * @param string $facebookClientSecret
     * @throws FacebookSDKException
     */
    public function __construct(string $facebookClientId, string $facebookClientSecret)
    {
        $this->parameters = [
            'app_id' => $facebookClientId,
            'app_secret' => $facebookClientSecret
        ];

        parent::__construct($this->parameters);
        }

    /**
     * @param Site $site
     * @return GraphEdge|bool
     * @throws FacebookSDKException
     */
    public function getAlbums(Site $site)
    {
        try {
            /** @var FacebookResponse $response */
            $response = $this->get(sprintf('/%s/albums', $site->getUserId()), $site->getAccessToken());
        } catch (FacebookSDKException $e) {
            return false;
        }

        return $response->getGraphEdge();
    }

    /**
     * @param Site $site
     * @param string $album
     * @param string $message
     * @param string $photoPath
     * @return bool
     */
    public function uploadPhoto(Site $site, string $album, string $message, string $photoPath)
    {
        try {
            $this->post(sprintf('/%s/photos', $album), [
                'source' => $this->fileToUpload($photoPath),
                'message' => $message
            ], $site->getAccessToken());
        } catch (FacebookSDKException $e) {
            return false;
        }

        return true;
    }

    /**
     * @param Site $site
     * @return bool|array
     * @throws FacebookSDKException
     */
    public function getPermissions(Site $site)
    {
        $url = sprintf('/%s/permissions', $site->getUserId());
        try {
            $response = $this->get($url, $site->getAccessToken());
        } catch (FacebookSDKException $e) {
            return false;
        }

        $permissions = array_map(function (GraphNode $permission) {
            return $permission->getField('status') == "granted" ? $permission->getField('permission') : "";
        }, $response->getGraphEdge()->all());

        return array_filter($permissions);
    }
}