<?php

namespace App\Core;

class Request
{
    private array $data = [];
    private array $query = [];

    public function __construct()
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $contentType = $_SERVER['CONTENT_TYPE'] ?? '';

        $this->query = $_GET ?? [];

        if ($method === 'GET') {
            $this->data = $_GET;
        } elseif ($method === 'POST') {
            $this->data = $_POST;
        } elseif (in_array($method, ['PUT', 'PATCH', 'DELETE'])) {
            $input = file_get_contents('php://input');

            if (stripos($contentType, 'application/json') !== false) {
                $this->data = json_decode($input, true) ?? [];
            } elseif (stripos($contentType, 'application/x-www-form-urlencoded') !== false) {
                parse_str($input, $this->data);
            } elseif (stripos($contentType, 'multipart/form-data') !== false) {
                $this->data = $this->parseMultipart($input, $contentType);
            } else {
                $this->data = [];
            }
        }
    }

    private function parseMultipart(string $input, string $contentType): array
    {
        $data = [];

        if (preg_match('/boundary=(.*)$/', $contentType, $matches)) {
            $boundary = $matches[1];
            $blocks = preg_split("/-+$boundary/", $input);

            foreach ($blocks as $block) {
                if (empty(trim($block))) continue;

                if (preg_match('/name="([^"]+)"/', $block, $nameMatch)) {
                    $name = $nameMatch[1];
                    $value = trim(substr($block, strpos($block, "\r\n\r\n") + 4));
                    $value = rtrim($value, "\r\n");
                    $data[$name] = $value;
                }
            }
        }

        return $data;
    }

    public function input(string $key, $default = null)
    {
        return $this->data[$key] ?? $this->query[$key] ?? $default;
    }

    public function body(): array
    {
        return $this->data;
    }

    public function query(): array
    {
        return $this->query;
    }

    public function all(): array
    {
        return array_merge($this->query, $this->data);
    }
}
