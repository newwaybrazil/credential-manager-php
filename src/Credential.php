<?php

namespace CredentialManager;

use Exception;
use Predis\Client as Redis;

class Credential
{
    private $redis;
    private $redisConfig;

    /**
     * method __construct
     * construct class with redis config if pass
     * @param string $redisConfig
     * @return void
     */
    public function __construct(
        array $redisConfig = []
    ) {
        $this->redisConfig = $redisConfig;
        $this->redis = $this->connectRedis();
    }

    /**
     * method getCredential
     * search and return if found a service credential in redis
     * @param string $service
     * @return string
     */
    public function getCredential(
        string $service
    ): ?string {
        try {
            $credential = $this->redis->get("token-{$service}");
            
            if (empty($credential) && method_exists($this, 'getTokenJwt')) {
                $credential = $this->getTokenJwt($service);
                $this->setCredential($service, $credential);
            }

            return $credential;
        } catch (Exception $e) {
            return null;
        }
    }

    /**
     * method getCredential
     * search and return if found a service credential in redis
     * @param string $service
     * @param string $credential
     * @return bool
     */
    public function setCredential(
        string $service,
        string $credential
    ): bool {
        try {
            $this->redis->set("token-{$service}", $credential);
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * method delCredential
     * remove credential from redis
     * @param string $service
     * @param string $credential
     * @return bool
     */
    public function delCredential(
        string $service
    ): bool {
        try {
            $this->redis->del("token-{$service}");
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * method connectRedis
     * return new predis client object
     * @return Redis
     */
    private function connectRedis(): Redis
    {
        $defaultConfig = [
            'scheme' => 'tcp',
            'host'   => 'localhost',
            'port'   => 6379,
        ];

        $this->redisConfig = array_merge($defaultConfig, $this->redisConfig);
        return new Redis($this->redisConfig);
    }


    /**
     * @codeCoverageIgnore
     * method getTokenJwt
     * using this method for custom
     * @param string $service
     * @return string
     */
    public function getTokenJwt(
        string $service
    ): string {
        return 'generic_token';
    }
}
