<?php


namespace App\Actions;


use AnthraxisBR\FastWork\Actions\Actions;
use AnthraxisBR\FastWork\CloudServices\CloudServices;
use AnthraxisBR\FastWork\Http\Request;
use App\CloudServices\CloundFunctions\GetUrlCloudFunction;
use App\CloudServices\CloundFunctions\GetUrlCloudFunctionGCP;
use App\CloudServices\Exemplo;


class CloudServicesAction extends Actions
{
    public function createCloudFunction(CloudServices $CloudServices)
    {

        $CloudServices->setService( new GetUrlCloudFunctionGCP() );

        return  $CloudServices->createCloudFunction();

    }


    public function index(Request $request, CloudServices $CloudServices)
    {

        /**
         * Informa onde será executado
         */
        $CloudServices->use('AWS'); // GCP ou Azure
        //$CloudServices->use('GCP');
        //$CloudServices->use('Azure');

        /**
         * Inicia um instancia de ObjectStorage, O provedor é usado para AWS, GCP e Azure
         */

        $file = new Exemplo($request->rawContent());

        /**
         * Configura o ObjectStorage
         * setFilename = Nome do arquivo
         * setTarget = destino do arquivo (bucket ou container)
         */
        $file->setFilename('arquivo.json')->setTarget('teste');

        /**
         * Define o serviço, no caso é a instancia $file de ObjectStorage, o comando a ser executado e executa
         */
        return $CloudServices->setService($file)->command('upload')->exec();
        /**
         * Também aceita
         */
         return $CloudServices->setService($file)->upload();
        /**
         * Ou também
         */
         return $CloudServices->upload($file);
    }



}