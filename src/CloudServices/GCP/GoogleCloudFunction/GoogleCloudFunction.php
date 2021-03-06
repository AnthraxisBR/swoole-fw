<?php


namespace AnthraxisBR\FastWork\CloudServices\GCP\GoogleCloudFunction;


use AnthraxisBR\FastWork\CloudServices\CloudFunctions\CloudFunctionInterface;
use AnthraxisBR\FastWork\CloudServices\CloudFunctions\CloudFunctions;

class GoogleCloudFunction extends CloudFunctionClient implements CloudFunctionInterface
{


    public function createCloudFunction(CloudFunctions $cloudFunctions)
    {
        return json_decode($this->create($cloudFunctions->getGoogleFunctionObject(), $cloudFunctions->getApplicationName())->getBody()->getContents());
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