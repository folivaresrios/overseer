<?php

namespace issDev\Overseer\Traits;

use Illuminate\Support\Facades\Cache;

trait CacheTrait
{
    protected $minutes = 60;
    protected $relationship;

    public function cacheKey()
    {
        return sprintf(
        '%s/%s-%s',
        $this->getRelationship(),
        $this->getKey(),
        $this->updated_at->timestamp
    );
    }

    public function flushCache()
    {
        Cache::forget($this->cacheKey());
    }

    public function getCacheRelationship($relationship, $eager = false)
    {
        $this->setRelationship($relationship);
        return Cache::remember($this->cacheKey(), $this->minutes, function () use ($relationship, $eager) {
            if (!$eager) {
                return $this->{$relationship}();
            }
            return $this->{$relationship};
        });
    }

    public function setRelationship($relationship)
    {
        $this->relationship = $relationship;
    }

    public function getRelationship()
    {
        return $this->relationship;
    }
}
