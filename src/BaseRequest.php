<?php

namespace RequestService;

class BaseRequest
{
    public $jsonRequest = false;

    /**
     * prepare body request before send
     * @param array $body
     * @return array
     */
    public function prepareBody(
        array $body
    ): array {
        if ($this->jsonRequest) {
            return [
                'json' => $body
            ];
        }

        return $body;
    }

    /**
     * prepare header request before send
     * @param array $header
     * @return array
     */
    public function prepareHeader(
        array $header
    ): array {
        $headers = [];
        if (isset($header['auth'])) {
            $headers['auth'] = $header['auth'];
            unset($header['auth']);
        }

        $headers['headers'] = [];
        if ($this->jsonRequest) {
            $headers['headers'] = [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ];
        }

        $headers['headers'] = array_merge($headers['headers'], $header);

        return $headers;
    }

    /**
     * prepare the endpoint before send
     * @param string $url
     * @param string $uri
     * @return string
     */
    public function prepareUrl(
        string $url,
        string $uri
    ): string {
        $protocol = '';
        if (strpos($url, 'http') !== false) {
            $url = explode('//', $url);

            $protocol = $url[0].'//';
            $url = $url[1];
        }

        $url = str_replace('/', '', $url);

        if (strpos($uri, '/') === false || strpos($uri, '/') > 0) {
            $uri = "/{$uri}";
        }

        return "{$protocol}{$url}{$uri}";
    }
}
