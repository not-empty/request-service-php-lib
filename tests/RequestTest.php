<?php

namespace RequestService;

use Exception;
use GuzzleHttp\Client as Guzzle;
use GuzzleHttp\Exception\ClientException;
use Mockery;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

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

        $response = [
            'result' => true,
        ];

        $getBodySpy = Mockery::spy(StreamInterface::class);
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

        $this->assertEquals($sendRequest, null);
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

        $responseInterfaceMock = Mockery::mock(ResponseInterface::class)
            ->shouldReceive('getBody')
            ->never()
            ->andReturn(json_encode($response))
            ->getMock();

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
    public function testClientException()
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
            'error_code' => 400,
        ];

        $result = [
            'message' => '',
            'error_code' => 400
        ];
        $getBodySpy = Mockery::spy(StreamInterface::class);
        $requestInterfaceMock = Mockery::mock(RequestInterface::class)
            ->shouldReceive('getBody')
            ->withNoArgs()
            ->never()
            ->andReturnSelf()
            ->getMock();

        $responseInterfaceMock = Mockery::mock(ResponseInterface::class)
            ->shouldReceive('getStatusCode')
            ->withNoArgs()
            ->once()
            ->andReturn(400)
            ->shouldReceive('getBody')
            ->withNoArgs()
            ->once()
            ->andReturn($getBodySpy)
            ->shouldReceive('getContents')
            ->withNoArgs()
            ->andReturn(json_encode($response))
            ->getMock();

        $guzzleMock = Mockery::mock(Guzzle::class)
            ->shouldReceive($method)
            ->with($url, array_merge($headers, $bodyResult))
            ->once()
            ->andThrow(new ClientException(
                'Request error',
                $requestInterfaceMock,
                $responseInterfaceMock
            ))
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

        $this->assertEquals($sendRequest, $result);
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

        $getBodySpy = Mockery::spy(StreamInterface::class);
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

        $this->assertEquals($sendRequest, '');
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

        $getBodySpy = Mockery::spy(StreamInterface::class);
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

        $this->assertEquals($sendRequest, []);
    }

    /**
     * @covers \RequestService\Request::getErrorMessage
     */
    public function testGetErrorMessage()
    {
        $config = [
            'back' => [
                'url' => 'localhost',
            ],
        ];

        $result = [
            'message' => 'Request error',
            'error_code' => 400,
        ];

        $requestMock = new Request($config);

        $errorPayload = $requestMock->getErrorMessage(
            400,
            'Request error'
        );

        $this->assertEquals($result, $errorPayload);
    }

     /**
     * @covers \RequestService\Request::getErrorMessage
     */
    public function testGetErrorMessageWithNoCode()
    {
        $config = [
            'back' => [
                'url' => 'localhost',
            ],
        ];

        $result = [
            'message' => 'Request error',
            'error_code' => 500,
        ];

        $requestMock = new Request($config);

        $errorPayload = $requestMock->getErrorMessage(
            0,
            'Request error'
        );

        $this->assertEquals($result, $errorPayload);
    }

    protected function tearDown(): void
    {
        Mockery::close();
    }
}
