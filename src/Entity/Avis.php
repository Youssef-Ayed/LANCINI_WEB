<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\AvisRepository;
use Symfony\Component\Validator\Constraints\Date;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\NotBlank;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Core\Annotation\ApiProperty;
#[ORM\Entity(repositoryClass: AvisRepository::class)]
#[ApiResource]

class Avis
{
    #[ApiProperty]
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private $idavis = null;

    #[ORM\Column(length:100)]
    #[Assert\NotNull]
    #[ApiProperty]
    private  $description = null;

     
     #[ORM\Column(type:"decimal", precision:3, scale:1)]
     #[assert\NotBlank]
     #[Assert\Range(min:0, max:5)]
    private $note;

    #[ORM\Column(type: "date")]
    private  $date = null;

    #[ORM\ManyToOne(targetEntity: Produit::class)]
    #[ORM\JoinColumn(name: "idProduit", referencedColumnName: "idProduit")]
    #[Assert\NotNull]
    private $idProduit;


    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(name: "idevaluateuruser", referencedColumnName: "idUser")]
    #[Assert\NotNull]
    private $idevaluateuruser;
 

    public function getId(): ?int
    {
        return $this->idavis;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getNote(): ?string
    {
        return $this->note;
    }

    public function setNote(string $note): self
    {
        $this->note = $note;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getIdProduit(): ?Produit
    {
        return $this->idProduit;
    }

    public function setIdProduit(?Produit $idProduit): self
    {
        $this->idProduit = $idProduit;

        return $this;
    }

    public function getIdevaluateuruser(): ?User
    {
        return $this->idevaluateuruser;
    }

    public function setIdevaluateuruser(?User $idEvaluateurUser): self
    {
        $this->idevaluateuruser = $idEvaluateurUser;

        return $this;
    }

    public function getIdAvis(): ?int
    {
        return $this->idavis;
    }
    public function __toString()
    {
        return $this->description;
    }

}