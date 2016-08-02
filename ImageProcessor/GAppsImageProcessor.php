<?php
/**
 * Created by PhpStorm.
 * User: anget
 * Date: 19.07.16
 * Time: 14:10
 */

namespace Boboyan\ContentBundle\ImageProcessor;


use Google_Service_Drive_DriveFile;

class GAppsImageProcessor implements ImageProcessor
{

    const DEVSTORAGE_URL = \Google_Service_Drive::DRIVE_METADATA_READONLY;

    private $projectName;
    private $clientId;
    private $clientSecret;
    private $segmentName;
    private $privateKey;
    private $clientEmail;

    /**
     * GAppsImageProcessor constructor.
     * @param $projectName
     * @param $clientId
     * @param $clientSecret
     * @param $segmentName
     * @param $privateKey
     */
    public function __construct($projectName, $clientId, $clientSecret, $clientEmail, $segmentName, $privateKey)
    {
        $this->projectName = $projectName;
        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;
        $this->segmentName = $segmentName;
        $this->privateKey = $privateKey;
        $this->clientEmail = $clientEmail;
    }

    public function save($param)
    {
        $client = new \Google_Client();

        $client->setApplicationName($this->projectName);
        $client->setClientSecret('A2SVXIVVhjdJtJ86w9fIYC8-');
      // $client->setRedirectUri('http://172.17.0.3/app_dev.php');
        $client->setClientId('604919316416-1t3t22l7oo9techlo1c3tht0sr7i0uev.apps.googleusercontent.com');
        $client->setScopes(self::DEVSTORAGE_URL);
        $client->setAuthConfigFile('client_secret.json');
        $client->setAccessType('offline');

//
//        if (isset($_GET['code']) || (isset($_SESSION['access_token']) && $_SESSION['access_token'])) {
//            if (isset($_GET['code'])) {
//                $client->authenticate($_GET['code']);
//                $_SESSION['access_token'] = $client->getAccessToken();
//            } else
//                $client->setAccessToken($_SESSION['access_token']);

//        $privateKey = file_get_contents($this->privateKey); // получаем контент .p12 файла, который получили ранее
//        $client->setAssertionCredentials(new \Google_Auth_AssertionCredentials(
//            $this->clientEmail, // имеет вид 12345678910-fb2vu7ipertbg3t73shtu2m30b838756@developer.gserviceaccount.com, получили ранее
//            array(self::DEVSTORAGE_URL),
//            $privateKey
//        ));
            $service = new \Google_Service_Drive($client);
            $file = new Google_Service_Drive_DriveFile();

            $file->setTitle($param['origin']);
            $file->setDescription('A test document');
            $file->setMimeType('image/jpeg');

            $data = file_get_contents($param['temp_path']);

            $res = $service->files->insert($file, array(
                'data' => $data,
                'mimeType' => 'image/jpeg',
                'uploadType' => 'multipart'
            ));
           return $res;
        }
   // }

//        $object = new \Google_Service_Storage_StorageObject();
//        $object->setName($param['origin']); // имя файла в bucket(на диске google)
//        $res = $objects->insert(
//            $this->segmentName, $object, array(
//                'data' => file_get_contents($param['temp_path']), // контент файла
//                'uploadType' => 'media',
//                'predefinedAcl' => 'publicread' // присваиваем предопределенный ACL public-read
//            )
//        );
//        $request = new Google_HttpRequest($object['selfLink']);
//        $response = $this->client->getIo()->authenticatedRequest($request);
    //       var_dump($response);die();
//        return $res;


    public function delete($fileId)
    {
        // TODO: Implement delete() method.
    }

    public function show($fileId)
    {
        $client = new \Google_Client();

        $client->setApplicationName($this->projectName);
        $client->setClientId($this->clientId);
        $client->setScopes(self::DEVSTORAGE_URL);
        $privateKey = file_get_contents($this->privateKey); // получаем контент .p12 файла, который получили ранее
        $client->setAssertionCredentials(new \Google_Auth_AssertionCredentials(
            $this->clientEmail, // имеет вид 12345678910-fb2vu7ipertbg3t73shtu2m30b838756@developer.gserviceaccount.com, получили ранее
            array(self::DEVSTORAGE_URL),
            $privateKey
        ));

        echo "<img src='https://drive.google.com/file/d/0B99pTgxtw6TUMHkxNTVHOFNEMkE/view?usp=drivesdk'>";
        die();
    }
}
