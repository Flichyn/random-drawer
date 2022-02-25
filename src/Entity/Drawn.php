<?php

namespace App\Entity;

use App\Repository\DrawnRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=DrawnRepository::class)
 */
class Drawn
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
     * @ORM\Column(type="integer")
     */
    private $crew_id;

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

    public function getCrewId(): ?int
    {
        return $this->crew_id;
    }

    public function setCrewId(int $crew_id): self
    {
        $this->crew_id = $crew_id;

        return $this;
    }
}
