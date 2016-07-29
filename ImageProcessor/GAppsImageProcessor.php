<?php
/**
 * Created by PhpStorm.
 * User: anget
 * Date: 19.07.16
 * Time: 14:10
 */

namespace Boboyan\ContentBundle\ImageProcessor;


class GAppsImageProcessor implements ImageProcessor
{

    const DEVSTORAGE_URL = 'https://www.googleapis.com/auth/devstorage.full_control';
    private $projectName;
    private $clientId;
    private $clientSecret;
    private $segmentName;
    private $privateKey;

    /**
     * GAppsImageProcessor constructor.
     * @param $projectName
     * @param $clientId
     * @param $clientSecret
     * @param $segmentName
     * @param $privateKey
     */
    public function __construct($projectName, $clientId, $clientSecret, $segmentName, $privateKey)
    {
        $this->projectName = $projectName;
        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;
        $this->segmentName = $segmentName;
        $this->privateKey = $privateKey;
    }

    public function save($param)
    {
        $client = new \Google_Client();

        $client->setApplicationName($this->projectName);
        $client->setClientId($this->clientId);
        $client->setClientSecret($this->clientSecret);
        //$redirect_uri = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'];
        //$client->setRedirectUri($redirect_uri);
        $client->setScopes(self::DEVSTORAGE_URL);
        $privateKey = file_get_contents($this->privateKey); // получаем контент .p12 файла, который получили ранее
        $client->setAssertionCredentials(new \Google_Auth_AssertionCredentials(
            'bobo-1379@appspot.gserviceaccount.com', // имеет вид 12345678910-fb2vu7ipertbg3t73shtu2m30b838756@developer.gserviceaccount.com, получили ранее
            array(self::DEVSTORAGE_URL),
            $privateKey
        ));

        $storageService = new \Google_Service_Storage($client);
        $objects = $storageService->objects;

        $object = new \Google_Service_Storage_StorageObject();
        $object->setName($param['origin']); // имя файла в bucket(на диске google)
        $res = $objects->insert(
            $this->segmentName, $object, array(
                'data' => file_get_contents($param['temp_path']), // контент файла
                'uploadType' => 'media',
                //'contentEncoding' => CFileHelper::getMimeType('FILE_PATH'), // MIME-тип файла, в этом примере для этого используется класс CFileHelper из Yii2
                //'mimeType' => CFileHelper::getMimeType('FILE_PATH'), // то же самое, так же вы можете установить универсальный application/octet-stream
                'predefinedAcl' => 'publicread' // присваиваем предопределенный ACL public-read
            )
        );
        return $res;
    }

    public function delete($fileId)
    {
        // TODO: Implement delete() method.
    }

    public function show($fileId)
    {
        // TODO: Implement show() method.
    }


}