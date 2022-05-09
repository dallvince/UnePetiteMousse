<?php

namespace App\Entity;

use App\Repository\CountriesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CountriesRepository::class)
 */
class Countries
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
    private $name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $flag;

    /**
     * @ORM\OneToMany(targetEntity=Brewries::class, mappedBy="countries")
     */
    private $brewries;

    public function __construct()
    {
        $this->brewries = new ArrayCollection();
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

    public function getFlag(): ?string
    {
        return $this->flag;
    }

    public function setFlag(?string $flag): self
    {
        $this->flag = $flag;

        return $this;
    }

    /**
     * @return Collection<int, Brewries>
     */
    public function getBrewries(): Collection
    {
        return $this->brewries;
    }

    public function addBrewry(Brewries $brewry): self
    {
        if (!$this->brewries->contains($brewry)) {
            $this->brewries[] = $brewry;
            $brewry->setCountries($this);
        }

        return $this;
    }

    public function removeBrewry(Brewries $brewry): self
    {
        if ($this->brewries->removeElement($brewry)) {
            // set the owning side to null (unless already changed)
            if ($brewry->getCountries() === $this) {
                $brewry->setCountries(null);
            }
        }

        return $this;
    }

    // public function __toString()
    // {
    //     return $this->name;
    // }
}
