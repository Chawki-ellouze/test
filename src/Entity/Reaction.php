<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\ReactionRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=ReactionRepository::class)
 */
class Reaction
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $type;

    /**
     * @ORM\Column(type="datetime")
     */
    private $reactedAt;

    /**
     * @ORM\ManyToOne(targetEntity=Comment::class, inversedBy="reactions")
     * @ORM\JoinColumn(nullable=false)
     */
    private $comment;

    public function __construct()
    {
        $this->reactedAt = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getReactedAt(): ?\DateTimeInterface
    {
        return $this->reactedAt;
    }

    public function setReactedAt(\DateTimeInterface $reactedAt): self
    {
        $this->reactedAt = $reactedAt;

        return $this;
    }

    public function getComment(): ?Comment
    {
        return $this->comment;
    }

    public function setComment(?Comment $comment): self
    {
        $this->comment = $comment;

        return $this;
    }
}
