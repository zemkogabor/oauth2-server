<?php

declare(strict_types = 1);

namespace App\Entity;

use League\OAuth2\Server\Entities\ScopeEntityInterface;
use League\OAuth2\Server\Entities\Traits\EntityTrait;
use League\OAuth2\Server\Entities\Traits\ScopeTrait;

class ScopeEntity implements ScopeEntityInterface
{
    use EntityTrait;
    use ScopeTrait;

    /**
     * @inheritdoc
     */
    public function jsonSerialize(): mixed
    {
        return $this->getIdentifier();
    }
}
