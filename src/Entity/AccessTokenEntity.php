<?php

declare(strict_types = 1);

namespace App\Entity;

use App\Repository\AccessTokenRepository;
use DateTimeImmutable;
use League\OAuth2\Server\Entities\AccessTokenEntityInterface;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Entities\ScopeEntityInterface;
use League\OAuth2\Server\Entities\Traits\AccessTokenTrait;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AccessTokenRepository::class)]
#[ORM\HasLifecycleCallbacks]
#[ORM\Table(name: 'access_token')]
class AccessTokenEntity implements AccessTokenEntityInterface
{
    use AccessTokenTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;

    #[ORM\Column]
    private int $user_id;

    #[ORM\OneToOne]
    #[ORM\JoinColumn(name: 'client_id', referencedColumnName: 'id', nullable: false)]
    private ClientEntity $client;

    #[ORM\Column]
    private string $token;

    #[ORM\Column]
    private bool $is_revoke;

    #[ORM\Column]
    private DateTimeImmutable $expiry_at;

    #[ORM\Column]
    private DateTimeImmutable $created_at;

    #[ORM\Column]
    private DateTimeImmutable $updated_at;

    /**
     * @var ScopeEntityInterface[] $scopes
     */
    protected array $scopes;

    public function getExpiryDateTime(): DateTimeImmutable
    {
        return $this->expiry_at;
    }

    public function getUserIdentifier(): int
    {
        return $this->user_id;
    }

    /**
     * @return ScopeEntityInterface[]
     */
    public function getScopes(): array
    {
        return array_values($this->scopes);
    }

    public function getIdentifier(): string
    {
        return $this->token;
    }

    public function setIdentifier($identifier): void
    {
        $this->token = $identifier;
    }

    public function setExpiryDateTime(DateTimeImmutable $dateTime)
    {
        $this->expiry_at = $dateTime;
    }

    public function setUserIdentifier($identifier): void
    {
        $this->user_id = $identifier;
    }

    /**
     * @param ClientEntityInterface|ClientEntity $client
     *
     * @return void
     */
    public function setClient(ClientEntityInterface|ClientEntity $client): void
    {
        $this->client = $client;
    }

    /**
     * @param ScopeEntityInterface $scope
     */
    public function addScope(ScopeEntityInterface $scope): void
    {
        $this->scopes[$scope->getIdentifier()] = $scope;
    }

    /**
     * @param bool $isRevoke
     */
    public function setIsRevoke(bool $isRevoke): void
    {
        $this->is_revoke = $isRevoke;
    }

    /**
     * @return bool
     */
    public function isRevoked(): bool
    {
        return $this->is_revoke;
    }

    /** @noinspection PhpUnused */
    #[ORM\PrePersist]
    public function setCreatedAt(): void
    {
        $this->created_at = new DateTimeImmutable();
    }

    /** @noinspection PhpUnused */
    #[ORM\PreUpdate]
    #[ORM\PrePersist]
    public function setUpdatedAt(): void
    {
        $this->updated_at = new DateTimeImmutable();
    }

    public function getClient(): ClientEntity
    {
        return $this->client;
    }
}
