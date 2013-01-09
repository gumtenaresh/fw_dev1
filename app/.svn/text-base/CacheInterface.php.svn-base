<?php
/**
 * This interface defines the common methods required for caching
 * @author shiva
 *
 */
interface CacheInterface{
    /**
     * Add to cache
     * @param string $key
     * @param mixed $value
     * @param int $cacheTime, optional, default 0 means infinite
     * @return bool
     */
    public function add($key, $value, $cacheTime);
    
    /**
     * Fetches from cache
     * @param string $key
     * @param bool $sucess, this indicates (pass by reference) whether the call has been success or not.
     * @return mixed
     */
    public function fetch($key, &$sucess);
    
    /**
     * Removes a key from cache
     * @param string $key
     * @return bool
     */
    public function delete($key);
    
    /**
     * Clears the system cache
     * @return bool
     */
    public function flush();
}