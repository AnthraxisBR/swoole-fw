<?php


namespace AnthraxisBR\SwooleFW\CloudServices\GCP\GoogleCloudFunction;


use AnthraxisBR\SwooleFW\CloudServices\CloudFunctions\CloudFunctionInterface;
use AnthraxisBR\SwooleFW\CloudServices\CloudFunctions\CloudFunctions;

class GoogleCloudFunction extends CloudFunctionClient implements CloudFunctionInterface
{


    public function createCloudFunction(CloudFunctions $cloudFunctionObject)
    {
        var_dump('zsxcas');
        return $this->create($cloudFunctionObject);
    }

    public function callCloudFunction(CloudFunctions $CloudFunctions)
    {

    }

    public function deleteCloudFunction(CloudFunctions $CloudFunctions)
    {

    }

    public function getCloudFunctionDownloadUrl(CloudFunctions $CloudFunctions)
    {

    }

    public function getCloudFunctionUploadUrl(CloudFunctions $CloudFunctions)
    {

    }

    public function getCloudFunction(CloudFunctions $CloudFunctions)
    {

    }

    public function listCloudFunctions($location)
    {

    }

    public function patchCloudFunction(CloudFunctions $CloudFunctions)
    {

    }
}