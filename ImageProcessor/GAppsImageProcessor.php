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
    const DEVSTORAGE_URL = array('https://www.googleapis.com/auth/drive', 'https://www.googleapis.com/auth/drive.apps.readonly');

    private $service;
    private $registry_email;

    /**
     * GAppsImageProcessor constructor.
     * @param $projectName
     * @param $clientId
     * @param $clientSecret
     * @param $segmentName
     * @param $privateKey
     */
    public function __construct($projectName, $clientId, $clientEmail, $privateKey, $registry_email)
    {

        $this->registry_email = $registry_email;
        $client = new \Google_Client();

        $client->setApplicationName($projectName);
        $client->setClientId($clientId);
        $client->setScopes(self::DEVSTORAGE_URL);

        $privateKey = file_get_contents($privateKey); // получаем контент .p12 файла, который получили ранее
        $client->setAssertionCredentials(new \Google_Auth_AssertionCredentials(
            $clientEmail, // имеет вид 12345678910-fb2vu7ipertbg3t73shtu2m30b838756@developer.gserviceaccount.com, получили ранее
            self::DEVSTORAGE_URL,
            $privateKey
        ));
        $this->service = new \Google_Service_Drive($client);
    }

    public function save($param)
    {

        $file = new Google_Service_Drive_DriveFile();

        $file->setTitle($param['origin']);
        $file->setDescription('A test document');
        $file->setMimeType('image/jpeg');

        $data = file_get_contents($param['temp_path']);

        $createdFile = $this->service->files->insert($file, array(
            'data' => $data,
            'mimeType' => 'image/jpeg',
            'uploadType' => 'multipart'
        ));

        $createdFile->setPermissions($this->registry_email);

        $permission = new \Google_Service_Drive_Permission();
        $permission->setType('anyone');
        $permission->setRole('reader');
        $permission->setWithLink(true);
        $permission->setValue(null);

        $this->service->permissions->insert($createdFile->getId(), $permission);

        return $createdFile->getId();
    }

    public function delete($fileId)
    {
        if($this->service->files->delete($fileId))
            return true;
        return false;
    }

    public function show($fileId)
    {
        $file = $this->service->files->get($fileId);
        return str_replace("&export=download", "", $file->webContentLink);

    }
//    public function getListResourses(){
//
//        $listObjects = $this->service->files->listFiles()->getItems();
//
//        $list = array();
//        $i = 0;
//        foreach ($listObjects as $listObject){
//
//            $list[$i] = $listObject->id;
//            $i++;
//        }
//        return $list;
//    }
}
