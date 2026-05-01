<?php

namespace TrishulApi\Core\Middleware;

class MiddlewareQueue
{
    private array $queue = [];
    public function __construct(array $queue){
        $this->queue = $queue;
    }

    public function get_queue(): array{
        return $this->queue;
    }

    public function add_single(string $middleware)
    {
        $this->queue[] = $middleware;
    }

    public function add_multiple(array $middleware)
    {
        $this->queue = array_merge($this->queue, $middleware);
    }
}