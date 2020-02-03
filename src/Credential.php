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
        string $origin,
        string $service
    ): ?string {
        try {
            return $this->redis->get("token-{$origin}-{$service}");
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
        string $origin,
        string $service,
        string $credential
    ): bool {
        try {
            $this->redis->set("token-{$origin}-{$service}", $credential);
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
        string $origin,
        string $service
    ): bool {
        try {
            $this->redis->del("token-{$origin}-{$service}");
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
}
