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

/**
 * Reads a JSON-RPC 2.0 request from STDIN and replies to STDOUT.
 */
class Server
{
    /** @var JsonRpc\Server */
    private $server;

    public function __construct(JsonRpc\Translator $translator)
    {
        $this->server = new JsonRpc\Server($translator);
    }

    public function reply()
    {
        $message = @file_get_contents('php://stdin');

        if ($message === false) {
            self::errorInvalidBody();
        }

        $reply = $this->server->reply($message);

        if ($reply !== null) {
            echo $reply;
        }
    }

    private static function errorInvalidBody()
    {
        @file_put_contents('php://stderr', 'Invalid body');
        exit(1);
    }
}
