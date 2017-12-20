<?php
/**
 * Created by PhpStorm.
 * User: francois.mathieu
 * Date: 20/12/2017
 * Time: 10:14
 */

namespace AppBundle\Utils\Facebook;

use AppBundle\Manager\SiteManager;
use Facebook\Exceptions\FacebookResponseException;
use Facebook\Exceptions\FacebookSDKException;
use Facebook\Facebook as BaseFacebook;
use Facebook\FacebookResponse;
use Facebook\GraphNodes\GraphEdge;

class Facebook extends BaseFacebook
{
    /** @var array $parameters */
    private $parameters = [
        'default_graph_version' => 'v2.10',
    ];
    /**
     * @var SiteManager
     */
    private $siteManager;

    /**
     * Facebook constructor.
     * @param SiteManager $siteManager
     * @param string $facebookClientId
     * @param string $facebookClientSecret
     * @throws FacebookSDKException
     */
    public function __construct(SiteManager $siteManager, string $facebookClientId, string $facebookClientSecret)
    {
        $this->parameters = [
            'app_id' => $facebookClientId,
            'app_secret' => $facebookClientSecret
        ];

        parent::__construct($this->parameters);

        $this->siteManager = $siteManager;
    }

    /**
     * @return GraphEdge|bool
     * @throws FacebookSDKException
     */
    public function getAlbums()
    {
        $site = $this->siteManager->getSite();
        try {
            /** @var FacebookResponse $response */
            $response = $this->get(sprintf('/%s/albums', $site->getUserId()), $site->getAccessToken());
        } catch (FacebookSDKException $e) {
            return false;
        }

        return $response->getGraphEdge();
    }

    /**
     * @param string $album
     * @param string $message
     * @param string $photoPath
     * @return bool
     */
    public function uploadPhoto(string $album, string $message, string $photoPath)
    {
        $site = $this->siteManager->getSite();

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
}