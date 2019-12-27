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
        $service = 'serviceTest';

        $redisMock = Mockery::mock('overload:' . Redis::class)
            ->shouldReceive('get')
            ->with("token-{$service}")
            ->once()
            ->andReturn('token')
            ->getMock();

        $credential = new Credential();

        $getCredential = $credential->getCredential($service);

        $this->assertEquals($getCredential, 'token');
    }

    /**
     * @runInSeparateProcess
     * @preserveGlobalState disabled
     * @covers \CredentialManager\Credential::getCredential
     */
    public function testGetCredentialException()
    {
        $service = 'serviceTest';

        $redisMock = Mockery::mock('overload:' . Redis::class)
            ->shouldReceive('get')
            ->with("token-{$service}")
            ->once()
            ->andThrow(new Exception('err', 500))
            ->getMock();

        $credential = new Credential();

        $getCredential = $credential->getCredential($service);

        $this->assertEquals($getCredential, null);
    }

    /**
     * @runInSeparateProcess
     * @preserveGlobalState disabled
     * @covers \CredentialManager\Credential::getCredential
     * @covers \CredentialManager\Credential::getTokenJwt
     */
    public function testGetCredentialAndGenerateToken()
    {
        $service = 'serviceTest';

        $redisMock = Mockery::mock('overload:' . Redis::class)
            ->shouldReceive('get')
            ->with("token-{$service}")
            ->once()
            ->andReturn('')
            ->getMock();

        $credential = Mockery::mock(Credential::class, [])->makePartial();
        $credential->shouldReceive('setCredential')
            ->with($service, 'generic_token')
            ->once()
            ->andReturn(true);

        $getCredential = $credential->getCredential($service);

        $this->assertEquals($getCredential, 'generic_token');
    }

    /**
     * @runInSeparateProcess
     * @preserveGlobalState disabled
     * @covers \CredentialManager\Credential::setCredential
     */
    public function testSetCredential()
    {
        $service = 'serviceTest';
        $value = 'token';

        $redisMock = Mockery::mock('overload:' . Redis::class)
            ->shouldReceive('set')
            ->with("token-{$service}", $value)
            ->once()
            ->andReturn(true)
            ->getMock();

        $credential = new Credential();

        $setCredential = $credential->setCredential($service, $value);

        $this->assertEquals($setCredential, true);
    }

    /**
     * @runInSeparateProcess
     * @preserveGlobalState disabled
     * @covers \CredentialManager\Credential::setCredential
     */
    public function testSetCredentialException()
    {
        $service = 'serviceTest';
        $value = 'token';

        $redisMock = Mockery::mock('overload:' . Redis::class)
            ->shouldReceive('set')
            ->with("token-{$service}", $value)
            ->once()
            ->andThrow(new Exception('err', 500))
            ->getMock();

        $credential = new Credential();

        $setCredential = $credential->setCredential($service, $value);

        $this->assertEquals($setCredential, false);
    }

    /**
     * @runInSeparateProcess
     * @preserveGlobalState disabled
     * @covers \CredentialManager\Credential::delCredential
     */
    public function testDelCredential()
    {
        $service = 'serviceTest';

        $redisMock = Mockery::mock('overload:' . Redis::class)
            ->shouldReceive('del')
            ->with("token-{$service}")
            ->once()
            ->andReturn(true)
            ->getMock();

        $credential = new Credential();

        $delCredential = $credential->delCredential($service);

        $this->assertEquals($delCredential, true);
    }

    /**
     * @runInSeparateProcess
     * @preserveGlobalState disabled
     * @covers \CredentialManager\Credential::delCredential
     */
    public function testDelCredentialException()
    {
        $service = 'serviceTest';

        $redisMock = Mockery::mock('overload:' . Redis::class)
            ->shouldReceive('del')
            ->with("token-{$service}")
            ->once()
            ->andThrow(new Exception('err', 500))
            ->getMock();

        $credential = new Credential();

        $delCredential = $credential->delCredential($service);

        $this->assertEquals($delCredential, false);
    }

    protected function tearDown(): void
    {
        Mockery::close();
    }
}
