<?php

namespace RequestService;

use Exception;
use GuzzleHttp\Client as Guzzle;
use Mockery;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use GuzzleHttp\Psr7\Stream;

class RequestTest extends TestCase
{
    /**
     * @covers \RequestService\Request::__construct
     */
    public function testCreateSendRequest()
    {
        $config = [
            'back' => [
                'url' => 'localhost',
            ],
        ];

        $requestJson = new Request($config);

        $this->assertInstanceOf(Request::class, $requestJson);
    }

    /**
     * @covers \RequestService\Request::sendRequest
     */
    public function testSendRequestJson()
    {
        $service = 'back';
        $method = 'GET';
        $uri = '/test';
        $header = [];
        $body = [];

        $headers = [
            'headers' => [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ]
        ];

        $bodyResult = [
            'json' => $body
        ];

        $config = [
            $service => [
                'url' => 'localhost',
            ],
        ];

        $url = 'localhost/test';

        $response = null;

        $getBodySpy = Mockery::spy(Stream::class);
        $responseInterfaceMock = Mockery::mock(ResponseInterface::class)
            ->shouldReceive('getBody')
            ->withNoArgs()
            ->once()
            ->andReturn($getBodySpy)
            ->getMock();

        $guzzleMock = Mockery::mock(Guzzle::class)
            ->shouldReceive($method)
            ->with($url, array_merge($headers, $bodyResult))
            ->once()
            ->andReturn($responseInterfaceMock)
            ->getMock();

        $requestMock = Mockery::mock(
            Request::class,
            [
                $config
            ]
        )->makePartial();

        $requestMock->shouldReceive('prepareHeader')
            ->with($header)
            ->once()
            ->andReturn($headers)
            ->shouldReceive('prepareBody')
            ->with($body)
            ->once()
            ->andReturn($bodyResult)
            ->shouldReceive('prepareUrl')
            ->with($config[$service]['url'], $uri)
            ->once()
            ->andReturn($url)
            ->shouldReceive('newGuzzle')
            ->withNoArgs()
            ->once()
            ->andReturn($guzzleMock);

        $sendRequest = $requestMock->sendRequest(
            $service,
            $method,
            $uri,
            $header,
            $body
        );

        $this->assertEquals($sendRequest, $response);
    }

    /**
     * @covers \RequestService\Request::sendRequest
     */
    public function testExceptionWithIncorrectStatusCode()
    {
        $service = 'back';
        $method = 'GET';
        $uri = '/test';
        $header = [];
        $body = [];

        $headers = [
            'headers' => [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ]
        ];

        $bodyResult = [
            'json' => $body
        ];

        $config = [
            $service => [
                'url' => 'localhost',
            ],
        ];

        $url = 'localhost/test';

        $response = [
            'message' => 'Request error',
            'error_code' => 500,
        ];

        $guzzleMock = Mockery::mock(Guzzle::class)
            ->shouldReceive($method)
            ->with($url, array_merge($headers, $bodyResult))
            ->once()
            ->andThrow(new Exception('Request error', 0))
            ->getMock();

        $requestMock = Mockery::mock(
            Request::class,
            [
                $config
            ]
        )->makePartial();

        $requestMock->shouldReceive('prepareHeader')
            ->with($header)
            ->once()
            ->andReturn($headers)
            ->shouldReceive('prepareBody')
            ->with($body)
            ->once()
            ->andReturn($bodyResult)
            ->shouldReceive('prepareUrl')
            ->with($config[$service]['url'], $uri)
            ->once()
            ->andReturn($url)
            ->shouldReceive('newGuzzle')
            ->withNoArgs()
            ->once()
            ->andReturn($guzzleMock);

        $sendRequest = $requestMock->sendRequest(
            $service,
            $method,
            $uri,
            $header,
            $body
        );

        $this->assertEquals($sendRequest, $response);
    }

    /**
     * @covers \RequestService\Request::sendRequest
     */
    public function testIncorrectConfig()
    {
        $service = 'back';
        $method = 'GET';
        $uri = '/test';
        $header = [];
        $body = [];

        $headers = [
            'headers' => [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ]
        ];

        $bodyResult = [
            'json' => $body
        ];

        $config = [
            'test' => [
                'url' => 'localhost',
            ],
        ];

        $url = 'localhost/test';

        $response = [
            'message' => 'Service config not found',
            'error_code' => 422,
        ];

        $responseInterfaceMock = Mockery::mock(ResponseInterface::class)
            ->shouldReceive('getBody')
            ->never()
            ->andReturn(json_encode($response))
            ->getMock();

        $guzzleMock = Mockery::mock(Guzzle::class)
            ->shouldReceive($method)
            ->never()
            ->andReturn($responseInterfaceMock)
            ->getMock();

        $requestMock = Mockery::mock(
            Request::class,
            [
                $config
            ]
        )->makePartial();

        $requestMock->shouldReceive('prepareHeader')
            ->never()
            ->andReturn($headers)
            ->shouldReceive('prepareBody')
            ->never()
            ->andReturn($bodyResult)
            ->shouldReceive('prepareUrl')
            ->never()
            ->andReturn($url)
            ->shouldReceive('newGuzzle')
            ->never()
            ->andReturn($guzzleMock);

        $sendRequest = $requestMock->sendRequest(
            $service,
            $method,
            $uri,
            $header,
            $body
        );

        $this->assertEquals($sendRequest, $response);
    }

    /**
     * @covers \RequestService\Request::sendRequest
     */
    public function testSendRequestStreamJson()
    {
        $service = 'back';
        $method = 'GET';
        $uri = '/test';
        $header = [
            'headers' => [
                'stream' => true,
            ],
        ];

        $body = [];

        $headers = [
            'headers' => [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'stream' => true,
            ],
        ];

        $bodyResult = [
            'json' => $body
        ];

        $config = [
            $service => [
                'url' => 'localhost',
            ],
        ];

        $url = 'localhost/test';

        $response = 'result';

        $getBodySpy = Mockery::spy(Stream::class);
        $responseInterfaceMock = Mockery::mock(ResponseInterface::class)
            ->shouldReceive('getBody')
            ->withNoArgs()
            ->once()
            ->andReturn($getBodySpy)
            ->shouldReceive('getContents')
            ->withNoArgs()
            ->andReturn($response)
            ->getMock();

        $guzzleMock = Mockery::mock(Guzzle::class)
            ->shouldReceive($method)
            ->with($url, array_merge($headers, $bodyResult))
            ->once()
            ->andReturn($responseInterfaceMock)
            ->getMock();

        $requestMock = Mockery::mock(
            Request::class,
            [
                $config
            ]
        )->makePartial();

        $requestMock->shouldReceive('prepareHeader')
            ->with($header)
            ->once()
            ->andReturn($headers)
            ->shouldReceive('prepareBody')
            ->with($body)
            ->once()
            ->andReturn($bodyResult)
            ->shouldReceive('prepareUrl')
            ->with($config[$service]['url'], $uri)
            ->once()
            ->andReturn($url)
            ->shouldReceive('newGuzzle')
            ->withNoArgs()
            ->once()
            ->andReturn($guzzleMock);

        $sendRequest = $requestMock->sendRequest(
            $service,
            $method,
            $uri,
            $header,
            $body
        );

        $this->assertEquals($sendRequest, null);
    }

    /**
     * @covers \RequestService\Request::sendRequest
     */
    public function testSendRequest()
    {
        $service = 'back';
        $method = 'GET';
        $uri = '/test';
        $header = [];
        $body = [];

        $headers = [];

        $bodyResult = $body;

        $config = [
            $service => [
                'url' => 'localhost',
                'json' => false,
            ],
        ];

        $url = 'localhost/test';

        $response = [
            'result' => true,
        ];

        $getBodySpy = Mockery::spy(Stream::class);
        $responseInterfaceMock = Mockery::mock(ResponseInterface::class)
            ->shouldReceive('getBody')
            ->withNoArgs()
            ->once()
            ->andReturn($getBodySpy)
            ->getMock();

        $guzzleMock = Mockery::mock(Guzzle::class)
            ->shouldReceive($method)
            ->with($url, array_merge($headers, $bodyResult))
            ->once()
            ->andReturn($responseInterfaceMock)
            ->getMock();

        $requestMock = Mockery::mock(
            Request::class,
            [
                $config
            ]
        )->makePartial();

        $requestMock->shouldReceive('prepareHeader')
            ->with($header)
            ->once()
            ->andReturn($headers)
            ->shouldReceive('prepareBody')
            ->with($body)
            ->once()
            ->andReturn($bodyResult)
            ->shouldReceive('prepareUrl')
            ->with($config[$service]['url'], $uri)
            ->once()
            ->andReturn($url)
            ->shouldReceive('newGuzzle')
            ->withNoArgs()
            ->once()
            ->andReturn($guzzleMock);

        $sendRequest = $requestMock->sendRequest(
            $service,
            $method,
            $uri,
            $header,
            $body
        );

        $this->assertIsObject($sendRequest);
    }

    /**
     * @covers \RequestService\Request::sendRequest
     */
    public function testSendRequestJsonDelete()
    {
        $service = 'back';
        $method = 'DELETE';
        $uri = '/test';
        $header = [];
        $body = [];

        $headers = [
            'headers' => [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ]
        ];

        $bodyResult = [
            'json' => $body
        ];

        $config = [
            $service => [
                'url' => 'localhost',
            ],
        ];

        $url = 'localhost/test';

        $response = [];

        $responseInterfaceMock = Mockery::mock(ResponseInterface::class)
            ->shouldReceive('getBody')
            ->never()
            ->andReturn(json_encode($response))
            ->getMock();

        $guzzleMock = Mockery::mock(Guzzle::class)
            ->shouldReceive($method)
            ->with($url, array_merge($headers, $bodyResult))
            ->once()
            ->andReturn($responseInterfaceMock)
            ->getMock();

        $requestMock = Mockery::mock(
            Request::class,
            [
                $config
            ]
        )->makePartial();

        $requestMock->shouldReceive('prepareHeader')
            ->with($header)
            ->once()
            ->andReturn($headers)
            ->shouldReceive('prepareBody')
            ->with($body)
            ->once()
            ->andReturn($bodyResult)
            ->shouldReceive('prepareUrl')
            ->with($config[$service]['url'], $uri)
            ->once()
            ->andReturn($url)
            ->shouldReceive('newGuzzle')
            ->withNoArgs()
            ->once()
            ->andReturn($guzzleMock);

        $sendRequest = $requestMock->sendRequest(
            $service,
            $method,
            $uri,
            $header,
            $body
        );

        $this->assertEquals($sendRequest, $response);
    }

    protected function tearDown(): void
    {
        Mockery::close();
    }
}
