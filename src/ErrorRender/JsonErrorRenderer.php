<?php

declare(strict_types = 1);

namespace App\ErrorRender;

class JsonErrorRenderer extends \Slim\Error\Renderers\JsonErrorRenderer
{
    protected string $defaultErrorTitle = 'Internal server error';
}
