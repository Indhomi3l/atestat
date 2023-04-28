<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class Genre
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::BIGINT)]
    private int $id;



    #[ORM\Column(type: Types::STRING, unique: true)]
    private string $name;

    #[ORM\ManyToMany(targetEntity: Album::class, inversedBy: 'genres')]
    #[ORM\JoinTable('genres_albums')]
    private Collection $albums;

    #[ORM\ManyToMany(targetEntity: Artist::class, inversedBy: 'genres')]
    #[ORM\JoinTable('genres_artists')]
    private Collection $artists;

    public function __construct(){
        $this->albums = new ArrayCollection();
        $this->artists = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return Collection
     */
    public function getAlbums(): Collection
    {
        return $this->albums;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return Genre
     */
    public function setName(string $name): static
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @param Album $album
     * @return Genre
     */
    public function addAlbum(Album $album): static
    {
        if(!$this->albums->contains($album))
        {
            $this->albums->add($album);
            $album->addGenre($this);
        }
        return $this;
    }

    /**
     * @param Album $album
     * @return Genre
     */
    public function removeAlbum(Album $album): static
    {
        if($this->albums->contains($album))
        {
            $this->albums->removeElement($album);
            $album->removeGenre($this);
        }
        return $this;
    }

    /**
     * @param Artist $artist
     * @return Genre
     */
    public function addArtist(Artist $artist): static
    {
        if(!$this->artists->contains($artist))
        {
            $this->artists->add($artist);
            $artist->addGenre($this);
        }
        return $this;
    }

    /**
     * @param Artist $album
     * @return Genre
     */
    public function removeArtist(Artist $album): static
    {
        if($this->artists->contains($album))
        {
            $this->artists->removeElement($album);
            $album->removeGenre($this);
        }
        return $this;
    }
}
