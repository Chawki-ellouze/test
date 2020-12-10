<?php

// src/Entity

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(
 *     normalizationContext={"groups"={"comment:read"}},
 *     denormalizationContext={"groups"={"comment:write"}}
 * )
 *
 * @ORM\Entity
 */
class Comment
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     *
     * @Groups({"comment:read", "article:read"})
     */
    private $id;

    /**
     * @ORM\Column(type="text")
     *
     * @Groups({"comment:read", "comment:write", "article:read"})
     */
    private $content;

    /**
     * @ORM\Column(type="datetime")
     *
     * @Groups({"comment:read", "article:read"})
     */
    private $createdAt;


    /**
     * @ORM\ManyToOne(targetEntity=Article::class, inversedBy="comments")
     * @ORM\JoinColumn(nullable=true)
     *
     * @Groups("comment:write")
     */
    private $article;

    /**
     * @ORM\OneToMany(targetEntity=Reaction::class, mappedBy="comment", orphanRemoval=true)
     * @Groups({"comment:read", "article:read"})
     */
    private $reactions;

    /**
     * @ORM\ManyToOne(targetEntity=Comment::class, inversedBy="responses")
     * @ORM\JoinColumn(nullable=true)
	 * @Groups("comment:write")
     */
    private $parent;

    /**
     * @ORM\OneToMany(targetEntity=Comment::class, mappedBy="parent")
     * @Groups({"comment:read", "article:read"})
     */
    private $responses;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="comments")
     */
    private $createdBy;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
        $this->reactions = new ArrayCollection();
        $this->responses = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getArticle(): ?Article
    {
        return $this->article;
    }

    public function setArticle(?Article $article): self
    {
        $this->article = $article;

        return $this;
    }

    /**
     * @return Collection|Reaction[]
     */
    public function getReactions(): Collection
    {
        return $this->reactions;
    }

    public function addReaction(Reaction $reaction): self
    {
        if (!$this->reactions->contains($reaction)) {
            $this->reactions[] = $reaction;
            $reaction->setComment($this);
        }

        return $this;
    }

    public function removeReaction(Reaction $reaction): self
    {
        if ($this->reactions->removeElement($reaction)) {
            // set the owning side to null (unless already changed)
            if ($reaction->getComment() === $this) {
                $reaction->setComment(null);
            }
        }

        return $this;
    }

    public function getParent(): ?self
    {
        return $this->parent;
    }

    public function setParent(?self $parent): self
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * @return Collection|self[]
     */
    public function getResponses(): Collection
    {
        return $this->responses;
    }

    public function addResponse(self $response): self
    {
        if (!$this->responses->contains($response)) {
            $this->responses[] = $response;
            $response->setParent($this);
        }

        return $this;
    }

    public function removeResponse(self $response): self
    {
        if ($this->responses->removeElement($response)) {
            // set the owning side to null (unless already changed)
            if ($response->getParent() === $this) {
                $response->setParent(null);
            }
        }

        return $this;
    }

    /**
     * @return int
     *
     * @Groups({"comment:read", "comment:write", "article:read"})
     */
    public function getLikesNumber()
    {
        $number = 0;
        if($this->getReactions()) {
            foreach ($this->reactions as $reaction) {
                if($reaction->getType() === 'like') {
                    $number++;
                }
            }
        }

        return $number;
    }


    /**
     * @return int
     *
     * @Groups({"comment:read", "comment:write", "article:read"})
     */
    public function getDislikesNumber()
    {
        $number = 0;
        if($this->getReactions()) {
            foreach ($this->reactions as $reaction) {
                if($reaction->getType() === 'dislike') {
                    $number++;
                }
            }
        }

        return $number;
    }

    /**
     * @return int
     *
     * @Groups({"comment:read", "comment:write", "article:read"})
     */
    public function getResponsesNumber()
    {
        return count($this->getResponses());
    }

    public function getCreatedBy(): ?User
    {
        return $this->createdBy;
    }

    public function setCreatedBy(?User $createdBy): self
    {
        $this->createdBy = $createdBy;

        return $this;
    }

}