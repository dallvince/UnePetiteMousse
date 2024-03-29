<?php

namespace App\Entity;

use App\Repository\ProductsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=ProductsRepository::class)
 */
class Products
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Saisir un nom pour votre produit")
     *  @Assert\Length(
     *  min = 5,
     *  max = 50,
     *  minMessage = "Veuillez saisir au moins 5 caractères",
     *  maxMessage = "Veuillez saisir au maximum 100 caractères" 
     *  )
     */
    private $name;

    /**
     * @ORM\Column(type="float")
     * @Assert\NotBlank(message="Ajouter un prix")
     * @Assert\Positive(message="Veuillez saisir un prix au dessus de zéro")
     */
    private $price;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $picture;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="float", nullable=true)
     * @Assert\NotBlank(message="Ajouter le degré d'alcool")
     * @Assert\Positive(message="Veuillez saisir un degré au dessus de zéro")
     * 
     */
    private $abv;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Assert\NotBlank(message="Ajouter le degré d'amertume")
     * @Assert\Positive(message="Veuillez saisir un degré au dessus de zéro")
     */
    private $ebc;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     *  @Assert\Length(
     *  min = 1,
     *  max = 8,
     *  minMessage = "Veuillez saisir au moins 1 caractère",
     *  maxMessage = "Veuillez saisir au maximum 8 caractères" 
     *  )
     */
    private $glutenfree;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     *  @Assert\Length(
     *  min = 1,
     *  max = 8,
     *  minMessage = "Veuillez saisir au moins 1 caractère",
     *  maxMessage = "Veuillez saisir au maximum 8 caractères" 
     *  )
     */
    private $organic;

    /**
     * @ORM\OneToMany(targetEntity=Comments::class, mappedBy="products")
     */
    private $comments;

    /**
     * @ORM\ManyToOne(targetEntity=Styles::class, inversedBy="products")
     */
    private $styles;

    /**
     * @ORM\ManyToOne(targetEntity=Brewries::class, inversedBy="products")
     */
    private $brewries;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $status = 0;

    /**
     * @ORM\OneToOne(targetEntity=Stocks::class, cascade={"persist", "remove"})
     */
    private $stocks;

    /**
     * @ORM\OneToMany(targetEntity=DetailsCommande::class, mappedBy="product")
     */
    private $detailsCommandes;


    public function __construct()
    {
        $this->comments = new ArrayCollection();
        $this->detailsCommandes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getPicture(): ?string
    {
        return $this->picture;
    }

    public function setPicture(?string $picture): self
    {
        $this->picture = $picture;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getAbv(): ?float
    {
        return $this->abv;
    }

    public function setAbv(?float $abv): self
    {
        $this->abv = $abv;

        return $this;
    }

    public function getEbc(): ?int
    {
        return $this->ebc;
    }

    public function setEbc(?int $ebc): self
    {
        $this->ebc = $ebc;

        return $this;
    }

    public function getGlutenfree(): ?string
    {
        return $this->glutenfree;
    }

    public function setGlutenfree(?string $glutenfree): self
    {
        $this->glutenfree = $glutenfree;

        return $this;
    }

    public function getOrganic(): ?string
    {
        return $this->organic;
    }

    public function setOrganic(?string $organic): self
    {
        $this->organic = $organic;

        return $this;
    }

    /**
     * @return Collection<int, Comments>
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comments $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setProducts($this);
        }

        return $this;
    }

    public function removeComment(Comments $comment): self
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getProducts() === $this) {
                $comment->setProducts(null);
            }
        }

        return $this;
    }

    public function getStyles(): ?Styles
    {
        return $this->styles;
    }

    public function setStyles(?Styles $styles): self
    {
        $this->styles = $styles;

        return $this;
    }

    public function getBrewries(): ?Brewries
    {
        return $this->brewries;
    }

    public function setBrewries(?Brewries $brewries): self
    {
        $this->brewries = $brewries;

        return $this;
    }

    public function getStatus(): ?bool
    {
        return $this->status;
    }

    public function setStatus(?bool $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getStocks(): ?Stocks
    {
        return $this->stocks;
    }

    public function setStocks(?Stocks $stocks): self
    {
        $this->stocks = $stocks;

        return $this;
    }

    /**
     * @return Collection<int, DetailsCommande>
     */
    public function getDetailsCommandes(): Collection
    {
        return $this->detailsCommandes;
    }

    public function addDetailsCommande(DetailsCommande $detailsCommande): self
    {
        if (!$this->detailsCommandes->contains($detailsCommande)) {
            $this->detailsCommandes[] = $detailsCommande;
            $detailsCommande->setProduct($this);
        }

        return $this;
    }

    public function removeDetailsCommande(DetailsCommande $detailsCommande): self
    {
        if ($this->detailsCommandes->removeElement($detailsCommande)) {
            // set the owning side to null (unless already changed)
            if ($detailsCommande->getProduct() === $this) {
                $detailsCommande->setProduct(null);
            }
        }

        return $this;
    }
}
