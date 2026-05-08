<?php

namespace App\Entity;

use App\Repository\InscriptionRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: InscriptionRepository::class)]
class Inscription
{
    public const STATUTS = ['confirmee', 'en_attente', 'annulee'];

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Assert\NotNull]
    private ?\DateTime $dateInscription = null;

    #[ORM\Column(length: 15)]
    #[Assert\Choice(choices: self::STATUTS)]
    private ?string $statut = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Assert\Length(max: 500)]
    private ?string $commentaire = null;

    #[ORM\ManyToOne(inversedBy: 'inscriptions')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Evenement $evenement = null;

    #[ORM\ManyToOne(inversedBy: 'inscriptions')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $participant = null;

    public function __construct()
    {
        $this->dateInscription = new \DateTime(); // auto
    }

    public function getId(): ?int { return $this->id; }

    public function getDateInscription(): ?\DateTime { return $this->dateInscription; }
    public function setDateInscription(\DateTime $dateInscription): static { $this->dateInscription = $dateInscription; return $this; }

    public function getStatut(): ?string { return $this->statut; }
    public function setStatut(string $statut): static { $this->statut = $statut; return $this; }

    public function getCommentaire(): ?string { return $this->commentaire; }
    public function setCommentaire(?string $commentaire): static { $this->commentaire = $commentaire; return $this; }

    public function getEvenement(): ?Evenement { return $this->evenement; }
    public function setEvenement(?Evenement $evenement): static { $this->evenement = $evenement; return $this; }

    public function getParticipant(): ?User { return $this->participant; }
    public function setParticipant(?User $participant): static { $this->participant = $participant; return $this; }
}