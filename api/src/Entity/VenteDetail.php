<?php

namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use App\Repository\VenteDetailRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: VenteDetailRepository::class)]
#[ApiResource(
    operations: [
        new GetCollection(normalizationContext: ["groups" => ["venteDetail:collection", "produit:collection", "quantification:collection"]]),
        new Post(),
        new Get(),
        new Put(),
        new Patch(),
        new Delete(),
    ],
)]
#[ApiFilter(SearchFilter::class, properties: ['vente.id' => 'exact'])]
class VenteDetail
{
    #[Groups(["venteDetail:collection"])]
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Groups(["venteDetail:collection"])]
    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: true, onDelete: "SET NULL")]
    private ?Produit $produit = null;

    #[Groups(["venteDetail:collection"])]
    #[ORM\Column]
    private ?float $quantite = null;

    #[Groups(["venteDetail:collection"])]
    #[ORM\ManyToOne]
    private ?Quantification $unite = null;

    #[Groups(["venteDetail:collection"])]
    #[ORM\Column]
    private ?float $prix = null;

    #[Groups(["venteDetail:collection"])]
    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $note = null;

    #[Groups(["venteDetail:collection"])]
    #[ORM\ManyToOne(inversedBy: 'venteDetails')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Vente $vente = null;



    #[Groups(["venteDetail:collection"])]
    #[ORM\Column]
    private ?bool $livrer = true;

    #[Groups(["venteDetail:collection"])]
    public function getPrixTotal()
    {
        return $this->prix * $this->quantite;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProduit(): ?Produit
    {
        return $this->produit;
    }

    public function setProduit(?Produit $produit): static
    {
        $this->produit = $produit;

        return $this;
    }

    public function getQuantite(): ?float
    {
        return $this->quantite;
    }

    public function setQuantite(float $quantite): static
    {
        $this->quantite = $quantite;

        return $this;
    }

    public function getUnite(): ?Quantification
    {
        return $this->unite;
    }

    public function setUnite(?Quantification $unite): static
    {
        $this->unite = $unite;

        return $this;
    }

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(float $prix): static
    {
        $this->prix = $prix;

        return $this;
    }

    public function getNote(): ?string
    {
        return $this->note;
    }

    public function setNote(?string $note): static
    {
        $this->note = $note;

        return $this;
    }

    public function getVente(): ?Vente
    {
        return $this->vente;
    }

    public function setVente(?Vente $vente): static
    {
        $this->vente = $vente;

        return $this;
    }





    public function isLivrer(): ?bool
    {
        return $this->livrer;
    }

    public function setLivrer(bool $livrer): static
    {
        $this->livrer = $livrer;

        return $this;
    }
}
