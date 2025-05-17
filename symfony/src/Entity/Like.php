<?php

namespace App\Entity;

use App\Repository\LikesRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LikesRepository::class)]
class Like
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Post::class, inversedBy: 'likes')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private ?Post $post = null;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: true, onDelete: 'CASCADE')]
    private ?User $user = null;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?\DateTimeInterface $liked_at = null;

    public function getId(): ?int { return $this->id; }

    public function getPost(): ?Post { return $this->post; }
    public function setPost(Post $post): static { $this->post = $post; return $this; }

    public function getUser(): ?User { return $this->user; }
    public function setUser(?User $user): static { $this->user = $user; return $this; }

    public function getLikedAt(): ?\DateTimeInterface
    {
        return $this->liked_at;
    }

    public function setLikedAt(?\DateTimeInterface $liked_at): static
    {
        $this->liked_at = $liked_at;

        return $this;
    }
}
