<?php

namespace App\Entity;

use App\Repository\PostagemRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: PostagemRepository::class)]
class Postagem
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Assert\NotBlank(message: "A titulo é obrigatório")]
    #[ORM\Column(length: 255)]
    private ?string $titulo = null;

    #[Assert\NotBlank(message: "A descrição é obrigatória")]
    #[ORM\Column(type: 'text')]
    private ?string $descricao = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $imagem = null;

    #[ORM\ManyToOne(inversedBy: 'postagems')]
    private ?Usuario $usuario = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitulo(): ?string
    {
        return $this->titulo;
    }

    public function setTitulo(string $titulo): static
    {
        $this->titulo = $titulo;

        return $this;
    }

    public function getDescricao(): ?string
    {
        return $this->descricao;
    }

    public function setDescricao(string $descricao): static
    {
        $this->descricao = $descricao;

        return $this;
    }

    public function getImagem(): ?string
    {
        return $this->imagem;
    }

    public function setImagem(?string $imagem): static
    {
        $this->imagem = $imagem;

        return $this;
    }

    public function getUsuario(): ?Usuario
    {
        return $this->usuario;
    }

    public function setUsuario(?Usuario $usuario): static
    {
        $this->usuario = $usuario;

        return $this;
    }
}
