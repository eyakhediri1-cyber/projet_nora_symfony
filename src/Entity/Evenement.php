<?php

namespace App\Entity;

use App\Repository\EvenementRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use ApiPlatform\Metadata\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: EvenementRepository::class)]
#[ApiResource(
    normalizationContext: ['groups' => ['event:read']],
    denormalizationContext: ['groups' => ['event:write']]
)]
class Evenement
{
    public const CATEGORIES = ['conference', 'atelier', 'meetup', 'formation', 'concert'];
    public const STATUTS = ['brouillon', 'publie', 'complet', 'annule'];

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['event:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Assert\Length(min: 5, max: 255)]
    #[Groups(['event:read', 'event:write'])]
    private ?string $titre = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Assert\NotBlank]
    #[Assert\Length(min: 30)]
    #[Groups(['event:read', 'event:write'])]
    private ?string $description = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Assert\NotNull]
    #[Groups(['event:read', 'event:write'])]
    private ?\DateTime $dateDebut = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Assert\NotNull]
    #[Groups(['event:read', 'event:write'])]
    private ?\DateTime $dateFin = null;

    #[ORM\Column]
    #[Assert\Range(min: 1)]
    #[Groups(['event:read', 'event:write'])]
    private ?int $capaciteMax = null;

    #[ORM\Column(nullable: true)]
    #[Assert\PositiveOrZero]
    #[Groups(['event:read', 'event:write'])]
    private ?float $prix = null;

    #[ORM\Column(length: 30)]
    #[Assert\Choice(choices: self::CATEGORIES)]
    #[Groups(['event:read', 'event:write'])]
    private ?string $categorie = null;

    #[ORM\Column(length: 20)]
    #[Assert\Choice(choices: self::STATUTS)]
    #[Groups(['event:read', 'event:write'])]
    private ?string $statut = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Groups(['event:read'])]
    private ?\DateTime $dateCreation = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['event:read'])]
    private ?string $imageName = null;

    #[ORM\ManyToOne(inversedBy: 'evenements')]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotBlank]
    #[Groups(['event:read', 'event:write'])]
    private ?Lieu $lieu = null;

    #[ORM\ManyToOne(inversedBy: 'evenementsOrganises')]
    private ?User $organisateur = null;

    #[ORM\ManyToMany(targetEntity: TagEvenement::class, inversedBy: 'evenements')]
    #[Groups(['event:read'])]
    private Collection $tags;

    #[ORM\OneToMany(targetEntity: Inscription::class, mappedBy: 'evenement', cascade: ['remove'])]

    private Collection $inscriptions;

    public function __construct()
    {
        $this->dateCreation = new \DateTime();
        $this->tags = new ArrayCollection();
        $this->inscriptions = new ArrayCollection();
    }

    // ================= GETTERS / SETTERS =================

    public function getId(): ?int { return $this->id; }

    public function getTitre(): ?string { return $this->titre; }
    public function setTitre(string $titre): static { $this->titre = $titre; return $this; }

    public function getDescription(): ?string { return $this->description; }
    public function setDescription(string $description): static { $this->description = $description; return $this; }

    public function getDateDebut(): ?\DateTime { return $this->dateDebut; }
    public function setDateDebut(\DateTime $dateDebut): static { $this->dateDebut = $dateDebut; return $this; }

    public function getDateFin(): ?\DateTime { return $this->dateFin; }
    public function setDateFin(\DateTime $dateFin): static { $this->dateFin = $dateFin; return $this; }

    public function getCapaciteMax(): ?int { return $this->capaciteMax; }
    public function setCapaciteMax(int $capaciteMax): static { $this->capaciteMax = $capaciteMax; return $this; }

    public function getPrix(): ?float { return $this->prix; }
    public function setPrix(?float $prix): static { $this->prix = $prix; return $this; }

    public function getCategorie(): ?string { return $this->categorie; }
    public function setCategorie(string $categorie): static { $this->categorie = $categorie; return $this; }

    public function getStatut(): ?string { return $this->statut; }
    public function setStatut(string $statut): static { $this->statut = $statut; return $this; }

    public function getDateCreation(): ?\DateTime { return $this->dateCreation; }

    public function getImageName(): ?string { return $this->imageName; }
    public function setImageName(?string $imageName): static { $this->imageName = $imageName; return $this; }

    public function getLieu(): ?Lieu { return $this->lieu; }
    public function setLieu(?Lieu $lieu): static { $this->lieu = $lieu; return $this; }

    public function getOrganisateur(): ?User { return $this->organisateur; }
    public function setOrganisateur(?User $organisateur): static { $this->organisateur = $organisateur; return $this; }

    public function getTags(): Collection { return $this->tags; }

    public function addTag(TagEvenement $tag): static
    {
        if (!$this->tags->contains($tag)) {
            $this->tags->add($tag);
        }
        return $this;
    }

    public function removeTag(TagEvenement $tag): static
    {
        $this->tags->removeElement($tag);
        return $this;
    }

    public function getInscriptions(): Collection { return $this->inscriptions; }

    public function addInscription(Inscription $inscription): static
    {
        if (!$this->inscriptions->contains($inscription)) {
            $this->inscriptions->add($inscription);
            $inscription->setEvenement($this);
        }
        return $this;
    }

    public function removeInscription(Inscription $inscription): static
    {
        if ($this->inscriptions->removeElement($inscription)) {
            if ($inscription->getEvenement() === $this) {
                $inscription->setEvenement(null);
            }
        }
        return $this;
    }
}