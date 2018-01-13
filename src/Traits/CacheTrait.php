<?php

namespace KissDev\Overseer\Traits;

use Illuminate\Support\Facades\Cache;

trait CacheTrait
{
    /**
     * The Overseer cache tag used by the model.
     * Should be implemented by Model using this trait
     *
     * @return string
     */
    public static function getCacheTag()
    {
        return '';
    }

    /**
     * Get fresh permission tag assigned to the user or role.
     * Internal method, should be implemented by Model using this trait
     *
     * @return array
     */
    protected function getFreshPermissions()
    {
    }

    /**
     * Flush the permission cache repository.
     *
     * @return void
     */
    public function flushPermissionCache()
    {
        $primaryKey = $this[$this->primaryKey];
        $cacheKey = 'overseer.' . substr(static::getCacheTag(), 0, -1) . '.permissions.' . $primaryKey;

        Cache::forget($cacheKey);
    }

    public function getPermissions()
    {
        $primaryKey = $this[$this->primaryKey];
        $cacheKey = 'overseer.' . substr(static::getCacheTag(), 0, -1) . '.permissions.' . $primaryKey;
        return Cache::remember($cacheKey, 60, function () {
            return $this->getFreshPermissions();
        });
    }
}
