<?php

declare(strict_types = 1);

namespace App\Entity;

use App\Repository\ClientRepository;
use DateTimeImmutable;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

#[ORM\Entity(repositoryClass: ClientRepository::class)]
#[ORM\HasLifecycleCallbacks]
#[ORM\Table(name: 'client')]
class ClientEntity implements ClientEntityInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;

    #[ORM\Column(type: 'uuid', unique: true)]
    private UuidInterface|string $uuid;

    #[ORM\Column]
    private string $name;

    #[ORM\Column(nullable: true)]
    private string|null $secret;

    #[ORM\Column]
    private string $redirect_uri;

    /**
     * The client secret may or may not be provided depending on the request sent by the client.
     * If the client is confidential (i.e. is capable of securely storing a secret) then the secret must be validated.
     *
     * if false, the client is public
     */
    #[ORM\Column]
    private bool $is_confidential;

    #[ORM\Column]
    private DateTimeImmutable $created_at;

    #[ORM\Column]
    private DateTimeImmutable $updated_at;

    public function getIdentifier(): string
    {
        return (string) $this->uuid;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getRedirectUri(): string
    {
        return $this->redirect_uri;
    }

    public function isConfidential(): bool
    {
        return $this->is_confidential;
    }

    public function getSecret(): string
    {
        return $this->secret;
    }

    /** @noinspection PhpUnused */
    #[ORM\PrePersist]
    public function setCreatedAt(): void
    {
        $this->created_at = new DateTimeImmutable();
    }

    /** @noinspection PhpUnused */
    #[ORM\PrePersist]
    public function setUuid(): void
    {
        $this->uuid = Uuid::uuid4();
    }

    /** @noinspection PhpUnused */
    #[ORM\PreUpdate]
    #[ORM\PrePersist]
    public function setUpdatedAt(): void
    {
        $this->updated_at = new DateTimeImmutable();
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @param string $secret
     */
    public function setSecret(string $secret): void
    {
        $this->secret = $secret;
    }

    /**
     * @param string $redirectUri
     */
    public function setRedirectUri(string $redirectUri): void
    {
        $this->redirect_uri = $redirectUri;
    }

    /**
     * @param bool $isConfidential
     */
    public function setIsConfidential(bool $isConfidential): void
    {
        $this->is_confidential = $isConfidential;
    }
}
