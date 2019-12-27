<?php

require_once __DIR__ . '/../vendor/autoload.php';

use CredentialManager\Credential;

$credential = new Credential();

$credential->setCredential('serviceName', 'CredentialSample');
$getCredential = $credential->getCredential('serviceName');

print_r($getCredential);
echo PHP_EOL;
