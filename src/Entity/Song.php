<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity]
class Song
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::BIGINT)]
    private ?int $id=null;

    #[ORM\ManyToOne(targetEntity: Album::class, inversedBy: 'tracks')]
    #[ORM\JoinColumn(name: 'album_id', referencedColumnName: 'id')]
    private Album $album;

    #[ORM\ManyToMany(targetEntity: Artist::class, inversedBy: 'songs')]
    #[ORM\JoinTable('songs_artists')]
    private Collection $artists;

    private int $duration;

    private bool $explicit = false;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    #[Assert\Url()]
    private ?string $spotifyUrl;

    #[ORM\Column(type: Types::STRING)]
    private string $spotifyId;

    #[ORM\Column(type: Types::STRING)]
    private string $name;

    #[ORM\Column(type: Types::STRING)]
    private string $spotifyApiUri;

    #[ORM\OneToOne(targetEntity: Lyric::class)]
    private Lyric $lyrics;


    public function __construct() {
        $this->artists = new ArrayCollection();
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Album
     */
    public function getAlbum(): Album
    {
        return $this->album;
    }

    /**
     * @param Album $album
     * @return Song
     */
    public function setAlbum(Album $album): Song
    {
        $this->album = $album;
        return $this;
    }


    /**
     * @return Collection
     */
    public function getArtists(): Collection
    {
        return $this->artists;
    }

    /**
     * @param Artist $artist
     * @return Song
     */
    public function addArtist(Artist $artist): static
    {
        if(!$this->artists->contains($artist))
        {
            $this->artists->add($artist);
            $artist->addSong($this);
        }
        return $this;
    }

    /**
     * @param Artist $album
     * @return Song
     */
    public function removeArtist(Artist $album): static
    {
        if($this->artists->contains($album))
        {
            $this->artists->removeElement($album);
            $album->removeSong($this);
        }
        return $this;
    }

    /**
     * @return int
     */
    public function getDuration(): int
    {
        return $this->duration;
    }

    /**
     * @param int $duration
     * @return Song
     */
    public function setDuration(int $duration): Song
    {
        $this->duration = $duration;
        return $this;
    }

    /**
     * @return bool
     */
    public function isExplicit(): bool
    {
        return $this->explicit;
    }

    /**
     * @param bool $explicit
     * @return Song
     */
    public function setExplicit(bool $explicit): Song
    {
        $this->explicit = $explicit;
        return $this;
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
     * @return Song
     */
    public function setSpotifyUrl(?string $spotifyUrl): Song
    {
        $this->spotifyUrl = $spotifyUrl;
        return $this;
    }

    /**
     * @return string
     */
    public function getSpotifyId(): string
    {
        return $this->spotifyId;
    }

    /**
     * @param string $spotifyId
     * @return Song
     */
    public function setSpotifyId(string $spotifyId): Song
    {
        $this->spotifyId = $spotifyId;
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
     * @return Song
     */
    public function setName(string $name): Song
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getSpotifyApiUri(): string
    {
        return $this->spotifyApiUri;
    }

    /**
     * @param string $spotifyApiUri
     * @return Song
     */
    public function setSpotifyApiUri(string $spotifyApiUri): Song
    {
        $this->spotifyApiUri = $spotifyApiUri;
        return $this;
    }

    /**
     * @return Lyric
     */
    public function getLyrics(): Lyric
    {
        return $this->lyrics;
    }

    /**
     * @param Lyric $lyrics
     * @return Song
     */
    public function setLyrics(Lyric $lyrics): static
    {
        $this->lyrics = $lyrics;
        return $this;
    }

    public function __toString(): string {
        return $this->getName();
    }
}
