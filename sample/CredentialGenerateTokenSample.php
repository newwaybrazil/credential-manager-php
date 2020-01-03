<?php

require_once __DIR__ . '/../vendor/autoload.php';

use CredentialManager\Credential;

class CredentialGenerateTokenSample extends Credential
{
    /**
     * method __construct
     * sample construct
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->delCredential('originName', 'serviceName');
        $getCredential = $this->getCredential('originName', 'serviceName');

        print_r($getCredential);
        echo PHP_EOL;
    }

    /**
     * method getTokenJwt
     * custom generate jwt token
     * @param string $service
     * @return string
     */
    public function getTokenJwt(
        string $service
    ): string {
        return 'CredentialGenerateTokenSample';
    }
}

$credentialGenerateTokenSample = new CredentialGenerateTokenSample();
