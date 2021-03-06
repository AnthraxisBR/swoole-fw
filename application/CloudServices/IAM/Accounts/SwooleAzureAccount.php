<?php


namespace App\CloudServices\IAM\Accounts;


use AnthraxisBR\FastWork\CloudServices\IAM\AccountService;

/**
 * Representing AWS account using an class object
 *
 * Class SwooleAwsAccount
 * @package App\CloudServices\IAM
 */
class SwooleAzureAccount extends AccountService
{

    public $AccountName = 'AAA';

    public $AccountKey = '000';

    public $DefaultEndpointsProtocol = 'https';

}