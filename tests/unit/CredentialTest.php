<?php

namespace CredentialManager;

use Exception;
use Mockery;
use PHPUnit\Framework\TestCase;
use Predis\Client as Redis;

class CredentialTest extends TestCase
{
    /**
     * @runInSeparateProcess
     * @preserveGlobalState disabled
     * @covers \CredentialManager\Credential::__construct
     */
    public function testCreateCredential()
    {
        $credential = new Credential();
        $this->assertInstanceOf(Credential::class, $credential);
    }

    /**
     * @runInSeparateProcess
     * @preserveGlobalState disabled
     * @covers \CredentialManager\Credential::getCredential
     * @covers \CredentialManager\Credential::connectRedis
     */
    public function testGetCredential()
    {
        $origin = 'originTest';
        $service = 'serviceTest';

        $redisMock = Mockery::mock('overload:' . Redis::class)
            ->shouldReceive('get')
            ->with("token-{$origin}-{$service}")
            ->once()
            ->andReturn('token')
            ->getMock();

        $credential = new Credential();

        $getCredential = $credential->getCredential($origin, $service);

        $this->assertEquals($getCredential, 'token');
    }

    /**
     * @runInSeparateProcess
     * @preserveGlobalState disabled
     * @covers \CredentialManager\Credential::getCredential
     */
    public function testGetCredentialException()
    {
        $origin = 'originTest';
        $service = 'serviceTest';

        $redisMock = Mockery::mock('overload:' . Redis::class)
            ->shouldReceive('get')
            ->with("token-{$origin}-{$service}")
            ->once()
            ->andThrow(new Exception('err', 500))
            ->getMock();

        $credential = new Credential();

        $getCredential = $credential->getCredential($origin, $service);

        $this->assertEquals($getCredential, null);
    }

    /**
     * @runInSeparateProcess
     * @preserveGlobalState disabled
     * @covers \CredentialManager\Credential::setCredential
     */
    public function testSetCredential()
    {
        $origin = 'originTest';
        $service = 'serviceTest';
        $value = 'token';

        $redisMock = Mockery::mock('overload:' . Redis::class)
            ->shouldReceive('set')
            ->with("token-{$origin}-{$service}", $value)
            ->once()
            ->andReturn(true)
            ->getMock();

        $credential = new Credential();

        $setCredential = $credential->setCredential($origin, $service, $value);

        $this->assertEquals($setCredential, true);
    }

    /**
     * @runInSeparateProcess
     * @preserveGlobalState disabled
     * @covers \CredentialManager\Credential::setCredential
     */
    public function testSetCredentialException()
    {
        $origin = 'originTest';
        $service = 'serviceTest';
        $value = 'token';

        $redisMock = Mockery::mock('overload:' . Redis::class)
            ->shouldReceive('set')
            ->with("token-{$origin}-{$service}", $value)
            ->once()
            ->andThrow(new Exception('err', 500))
            ->getMock();

        $credential = new Credential();

        $setCredential = $credential->setCredential($origin, $service, $value);

        $this->assertEquals($setCredential, false);
    }

    /**
     * @runInSeparateProcess
     * @preserveGlobalState disabled
     * @covers \CredentialManager\Credential::delCredential
     */
    public function testDelCredential()
    {
        $origin = 'originTest';
        $service = 'serviceTest';

        $redisMock = Mockery::mock('overload:' . Redis::class)
            ->shouldReceive('del')
            ->with("token-{$origin}-{$service}")
            ->once()
            ->andReturn(true)
            ->getMock();

        $credential = new Credential();

        $delCredential = $credential->delCredential($origin, $service);

        $this->assertEquals($delCredential, true);
    }

    /**
     * @runInSeparateProcess
     * @preserveGlobalState disabled
     * @covers \CredentialManager\Credential::delCredential
     */
    public function testDelCredentialException()
    {
        $origin = 'originTest';
        $service = 'serviceTest';

        $redisMock = Mockery::mock('overload:' . Redis::class)
            ->shouldReceive('del')
            ->with("token-{$origin}-{$service}")
            ->once()
            ->andThrow(new Exception('err', 500))
            ->getMock();

        $credential = new Credential();

        $delCredential = $credential->delCredential($origin, $service);

        $this->assertEquals($delCredential, false);
    }

    protected function tearDown(): void
    {
        Mockery::close();
    }
}
