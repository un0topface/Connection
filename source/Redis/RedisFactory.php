<?php
/*
 * Copyright 2015-2016 Alexey Maslov <alexey.y.maslov@gmail.com>
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

namespace alxmsl\Connection\Redis;

use InvalidArgumentException;

/**
 * Factory for simple PhpRedis instance creation
 * @author alxmsl
 */
final class RedisFactory {
    /**
     * @var Connection[] $ConnectionsPool pool of connections
     */
    private static $ConnectionsPool = [];

    /**
     * Get connection from pool by array config
     * @param array $config
     * @return Connection
     */
    private static function getConnection(array $config) {
        $key = self::getConnectionKeyName($config['host'], $config['port']);

        if (!isset(self::$ConnectionsPool[$key])) {
            $Redis = new Connection();
            $Redis->setHost($config['host']);
            $Redis->setPort($config['port']);
            (isset($config['connect_timeout'])) && $Redis->setConnectTimeout($config['connect_timeout']);
            (isset($config['connect_tries']))   && $Redis->setConnectTries($config['connect_tries']);
            self::$ConnectionsPool[$key] = $Redis;
        }

        return self::$ConnectionsPool[$key];
    }

    /**
     * Get connection name by host and port
     * @param string $host
     * @param int $port
     * @return string
     */
    private static function getConnectionKeyName($host, $port) {
        $name = $host . ':' . $port;
        return $name;
    }

    /**
     * Create PhpRedis instance by array config
     * @param array $config array configuration
     * @return Connection
     * @throws InvalidArgumentException
     */
    public static function createRedisByConfig(array $config) {
        return self::getConnection($config);
    }
}
