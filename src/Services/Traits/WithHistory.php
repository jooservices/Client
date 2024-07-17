<?php

namespace JOOservices\XClient\Services\Traits;

use GuzzleHttp\Middleware;

trait WithHistory
{
    protected array $histories = [];

    protected function initHistory(?int $id = null): static
    {
        if (isset($this->client)) {
            $id = spl_object_id($this->client);
        }

        if ($id && ! isset($this->histories[$id])) {
            $this->histories[$id] = [];
        } else {
            $this->histories[0] = [];
        }

        return $this;
    }

    public function enableHistory(?int $id = null): static
    {
        $this->initHistory($id)
            ->pushMiddleware(
                Middleware::history($this->histories[$id ?? 0]),
                $this->hasMocking() ? 'fake' : null
            );

        return $this;
    }

    public function getHistories(): array
    {
        return $this->histories;
    }
}
