<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity]
class Artist
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::BIGINT)]
    private int $id;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    #[Assert\Url()]
    private ?string $spotifyUrl;

    #[ORM\Column(type: Types::STRING)]
    private string $name;

    #[ORM\ManyToMany(targetEntity: Genre::class, mappedBy: 'artists')]
    private Collection $genres;

    #[ORM\ManyToMany(targetEntity: Album::class, inversedBy: 'artists')]
    #[ORM\JoinTable('artists_albums')]
    private Collection $albums;

    #[ORM\ManyToMany(targetEntity: Song::class, mappedBy: 'artists')]
    private Collection $songs;


    public function __construct(){
        $this->genres = new ArrayCollection();
        $this->albums = new ArrayCollection();
        $this->songs = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }


    /**
     * @return string|null
     */
    public function getSpotifyUrl(): ?string
    {
        return $this->spotifyUrl;
    }

    /**
     * @param string|null $spotifyUrl
     * @return Artist
     */
    public function setSpotifyUrl(?string $spotifyUrl): Artist
    {
        $this->spotifyUrl = $spotifyUrl;
        return $this;
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
     * @return Artist
     */
    public function setName(string $name): Artist
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return Collection
     */
    public function getGenres(): Collection
    {
        return $this->genres;
    }

    /**
     * @param Genre $genre
     * @return $this
     */
    public function addGenre(Genre $genre): static
    {
        if (!$this->genres->contains($genre)) {
            $this->genres->add($genre);
            $genre->addArtist($this);
        }
        return $this;
    }

    /**
     * @param Genre $genre
     * @return Artist
     */
    public function removeGenre(Genre $genre): static
    {
        if ($this->genres->contains($genre)) {
            $this->genres->removeElement($genre);
            $genre->removeArtist($this);
        }
        return $this;
    }

    /**
     * @return Collection
     */
    public function getAlbums(): Collection
    {
        return $this->albums;
    }
    /**
     * @param Album $album
     * @return $this
     */
    public function addAlbum(Album $album): static
    {
        if(!$this->albums->contains($album))
        {
            $this->albums->add($album);
            $album->addArtist($this);
        }
        return $this;
    }

    /**
     * @param Album $album
     * @return $this
     */
    public function removeAlbum(Album $album): static
    {
        if($this->albums->contains($album))
        {
            $this->albums->removeElement($album);
            $album->removeArtist($this);
        }
        return $this;
    }

    /**
     * @return Collection
     */
    public function getSongs(): Collection
    {
        return $this->songs;
    }

    /**
     * @param Song $song
     * @return $this
     */
    public function addSong(Song $song): static
    {
        if(!$this->songs->contains($song))
        {
            $this->songs->add($song);
            $song->addArtist($this);
        }
        return $this;
    }

    /**
     * @param Song $song
     * @return $this
     */
    public function removeSong(Song $song): static
    {
        if($this->songs->contains($song))
        {
            $this->songs->removeElement($song);
            $song->removeArtist($this);
        }
        return $this;
    }
}
