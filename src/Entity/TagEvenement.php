<?php

namespace App\Entity;

use App\Repository\TagEvenementRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: TagEvenementRepository::class)]
#[UniqueEntity(fields: ['nom'], message: 'Ce tag existe déjà.')]
class TagEvenement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    #[Assert\NotBlank]
    #[Groups(['event:read'])]
    private ?string $nom = null;

    #[ORM\Column(length: 7)]
    #[Assert\NotBlank]
    #[Assert\Regex(pattern: '/^#[0-9A-Fa-f]{6}$/', message: 'Couleur invalide (ex: #FFAA00).')]
    private ?string $couleur = null;

    /**
     * @var Collection<int, Evenement>
     */
    #[ORM\ManyToMany(targetEntity: Evenement::class, mappedBy: 'tags')]
    private Collection $evenements;

    public function __construct()
    {
        $this->evenements = new ArrayCollection();
    }

    public function getId(): ?int { return $this->id; }

    public function getNom(): ?string { return $this->nom; }
    public function setNom(string $nom): static { $this->nom = $nom; return $this; }

    public function getCouleur(): ?string { return $this->couleur; }
    public function setCouleur(string $couleur): static { $this->couleur = $couleur; return $this; }

    /** @return Collection<int, Evenement> */
    public function getEvenements(): Collection { return $this->evenements; }

    public function addEvenement(Evenement $evenement): static
    {
        if (!$this->evenements->contains($evenement)) {
            $this->evenements->add($evenement);
            $evenement->addTag($this);
        }
        return $this;
    }

    public function removeEvenement(Evenement $evenement): static
    {
        if ($this->evenements->removeElement($evenement)) {
            $evenement->removeTag($this);
        }
        return $this;
    }
}