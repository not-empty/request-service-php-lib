<?php

namespace RequestService;

use Mockery;
use PHPUnit\Framework\TestCase;

class BaseRequestTest extends TestCase
{
    /**
     * @covers \RequestService\BaseRequest::prepareBody
     */
    public function testPrepareBody()
    {
        $body = [
            'teste' => true,
        ];

        $baseRequest = new BaseRequest();
        $prepareBody = $baseRequest->prepareBody($body);

        $this->assertEquals($prepareBody, $body);
    }

    /**
     * @covers \RequestService\BaseRequest::prepareBody
     */
    public function testPrepareBodyAndNotSendValue()
    {
        $body = [];

        $baseRequest = new BaseRequest();
        $prepareBody = $baseRequest->prepareBody($body);

        $this->assertEquals($prepareBody, $body);
    }

    /**
     * @covers \RequestService\BaseRequest::prepareBody
     */
    public function testPrepareJsonBody()
    {
        $body = [
            'teste' => true,
        ];

        $baseRequest = new BaseRequest();
        $baseRequest->jsonRequest = true;

        $prepareBody = $baseRequest->prepareBody($body);

        $this->assertEquals($prepareBody, ['json' => $body]);
    }

    /**
     * @covers \RequestService\BaseRequest::prepareUrl
     */
    public function testPrepareUrlAndUriWithPipe()
    {
        $url = 'localhost/';
        $uri = '/auth';
        $result = 'localhost/auth';

        $baseRequest = new BaseRequest();
        $prepareUrl = $baseRequest->prepareUrl($url, $uri);

        $this->assertEquals($prepareUrl, $result);
    }

    /**
     * @covers \RequestService\BaseRequest::prepareUrl
     */
    public function testPrepareUrlWithPipeAndUriNotHasPipe()
    {
        $url = 'localhost/';
        $uri = 'auth';
        $result = 'localhost/auth';

        $baseRequest = new BaseRequest();
        $prepareUrl = $baseRequest->prepareUrl($url, $uri);

        $this->assertEquals($prepareUrl, $result);
    }

    /**
     * @covers \RequestService\BaseRequest::prepareUrl
     */
    public function testPrepareUrlWithPipeAndUriHasSubRoute()
    {
        $url = 'localhost/';
        $uri = 'auth/generate';
        $result = 'localhost/auth/generate';

        $baseRequest = new BaseRequest();
        $prepareUrl = $baseRequest->prepareUrl($url, $uri);

        $this->assertEquals($prepareUrl, $result);
    }

    /**
     * @covers \RequestService\BaseRequest::prepareUrl
     */
    public function testPrepareUrlWithPipeAndUriHasMoreThanOnePipe()
    {
        $url = 'localhost/';
        $uri = '/auth/generate';
        $result = 'localhost/auth/generate';

        $baseRequest = new BaseRequest();
        $prepareUrl = $baseRequest->prepareUrl($url, $uri);

        $this->assertEquals($prepareUrl, $result);
    }

    /**
     * @covers \RequestService\BaseRequest::prepareUrl
     */
    public function testPrepareUrlNotHasPipeAndUriHasPipe()
    {
        $url = 'localhost';
        $uri = '/auth';
        $result = 'localhost/auth';

        $baseRequest = new BaseRequest();
        $prepareUrl = $baseRequest->prepareUrl($url, $uri);

        $this->assertEquals($prepareUrl, $result);
    }

    /**
     * @covers \RequestService\BaseRequest::prepareUrl
     */
    public function testPrepareUrlWithProtocol()
    {
        $url = 'http://localhost/';
        $uri = '/auth';
        $result = 'http://localhost/auth';

        $baseRequest = new BaseRequest();
        $prepareUrl = $baseRequest->prepareUrl($url, $uri);

        $this->assertEquals($prepareUrl, $result);
    }

    /**
     * @covers \RequestService\BaseRequest::prepareUrl
     */
    public function testPrepareUrlWithSecurityProtocol()
    {
        $url = 'https://localhost/';
        $uri = '/auth';
        $result = 'https://localhost/auth';

        $baseRequest = new BaseRequest();
        $prepareUrl = $baseRequest->prepareUrl($url, $uri);

        $this->assertEquals($prepareUrl, $result);
    }

    /**
     * @covers \RequestService\BaseRequest::prepareHeader
     */
    public function testPrepareHeader()
    {
        $header = [
            'Context' => 'teste',
        ];

        $result = [
            'headers' => [
                'Context' => 'teste',
            ],
        ];

        $baseRequest = new BaseRequest();
        $prepareHeader = $baseRequest->prepareHeader($header);

        $this->assertEquals($prepareHeader, $result);
    }

    /**
     * @covers \RequestService\BaseRequest::prepareHeader
     */
    public function testPrepareJsonHeader()
    {
        $header = [
            'Context' => 'teste',
        ];

        $result = [
            'headers' => [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'Context' => 'teste',
            ],
        ];

        $baseRequest = new BaseRequest();
        $baseRequest->jsonRequest = true;

        $prepareHeader = $baseRequest->prepareHeader($header);

        $this->assertEquals($prepareHeader, $result);
    }

    /**
     * @covers \RequestService\BaseRequest::prepareHeader
     */
    public function testPrepareJsonHeaderWithBasicAuth()
    {
        $header = [
            'auth' => [
                'username',
                'password',
            ],
            'Context' => 'teste',
        ];

        $result = [
            'auth' => [
                'username',
                'password',
            ],
            'headers' => [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'Context' => 'teste',
            ],
        ];

        $baseRequest = new BaseRequest();
        $baseRequest->jsonRequest = true;

        $prepareHeader = $baseRequest->prepareHeader($header);

        $this->assertEquals($prepareHeader, $result);
    }

    /**
     * @covers \RequestService\BaseRequest::prepareHeader
     */
    public function testPrepareHeaderWithBasicAuth()
    {
        $header = [
            'Context' => 'teste',
            'auth' => [
                'username',
                'password',
            ],
        ];

        $result = [
            'auth' => [
                'username',
                'password',
            ],
            'headers' => [
                'Context' => 'teste',
            ],
        ];

        $baseRequest = new BaseRequest();
        $prepareHeader = $baseRequest->prepareHeader($header);

        $this->assertEquals($prepareHeader, $result);
    }

    protected function tearDown(): void
    {
        Mockery::close();
    }
}
