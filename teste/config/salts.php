<?php
/**
 * Created by PhpStorm.
 * User: Junior
 * Date: 20/05/2017
 * Time: 08:53
 */

return [
    'TOKEN_SALT'    => hash('sha256', bin2hex(random_bytes(128)))
];