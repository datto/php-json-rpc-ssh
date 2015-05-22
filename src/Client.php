<?php

/**
 * Copyright (C) 2015 Datto, Inc.
 *
 * This file is part of PHP JSON-RPC SSH.
 *
 * PHP JSON-RPC SSH is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License, version 3,
 * as published by the Free Software Foundation.
 *
 * PHP JSON-RPC SSH is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public License
 * along with PHP JSON-RPC SSH. If not, see <http://www.gnu.org/licenses/>.
 *
 * @author Spencer Mortensen <smortensen@datto.com>
 * @author Matt Coleman <matt@datto.com>
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL-3.0
 * @copyright 2015 Datto, Inc.
 */

namespace Datto\JsonRpc\Ssh;

use Datto\JsonRpc;

class Client
{
    /** @var string */
    protected $command;

    /** @var JsonRpc\Client */
    protected $client;

    public function __construct($destination, $command, $options = array())
    {
        $this->command = self::getSshCommand($destination, $command, $options);
        $this->client = new JsonRpc\Client();
    }

    public function notify($method, $arguments = null)
    {
        $this->client->notify($method, $arguments);
    }

    public function query($id, $method, $arguments = null)
    {
        $this->client->query($id, $method, $arguments);
    }

    public function send()
    {
        $message = $this->client->encode();
        $reply = $this->execute($this->command, $message);
        return $this->client->decode($reply);
    }

    private static function execute($executablePath, $input)
    {
        if ($executablePath === null) {
            return null;
        }

        $descriptorSpec = array(
            array('pipe', 'r'),
            array('pipe', 'w')
        );

        $process = proc_open($executablePath, $descriptorSpec, $pipes);

        if (!is_resource($process)) {
            return null;
        }

        $stdin = &$pipes[0];
        fwrite($stdin, $input);
        fclose($stdin);

        $stdout = &$pipes[1];
        $result = stream_get_contents($stdout);
        fclose($stdout);

        $exitCode = proc_close($process);

        if ($exitCode !== 0) {
            return null;
        }

        return $result;
    }

    private static function getSshCommand($destination, $command, $options)
    {
        if (!self::isValidOptions($options)) {
            return null;
        }

        $sshCommand = 'ssh';

        foreach ($options as $option => $value) {
            $sshCommand .= ' -' . $option;

            if ($value !== null) {
                $sshCommand .= ' ' . escapeshellarg($value);
            }
        }

        return $sshCommand .
            ' ' . escapeshellarg($destination) .
            ' -- ' . escapeshellarg($command);
    }

    private static function isValidOptions($input)
    {
        if (!is_array($input)) {
            return false;
        }

        foreach ($input as $option => $value) {
            if (!self::isValidOptionName($option)) {
                return false;
            }
        }

        return true;
    }

    private static function isValidOptionName($input)
    {
        return is_string($input) && ctype_alnum($input) && (0 < strlen($input));
    }
}
