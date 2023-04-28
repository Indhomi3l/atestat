<?php

namespace App\Entity;

use App\Lib\Enum\AlbumType;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity]
class Album
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::BIGINT)]
    private int $id;

    #[ORM\Column(type: Types::STRING, enumType: AlbumType::class)]
    private AlbumType $albumType;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    #[Assert\Url()]
    private ?string $spotifyUrl;

    #[ORM\Column(type: Types::STRING)]
    private string $spotifyId;


    #[ORM\Column(type: 'ImageObjectCollection')]
    private ArrayCollection $images;

    #[ORM\Column(type: Types::STRING)]
    private string $name;

    #[ORM\Column(type: Types::STRING)]
    #[Assert\Date]
    private string $releaseDate;

    #[ORM\Column(type: Types::STRING)]
    private string $spotifyApiUri;

    #[ORM\Column(type: Types::STRING)]
    private string $label;

    #[ORM\ManyToMany(targetEntity: Genre::class, mappedBy: 'albums')]
    private Collection $genres;

    #[ORM\ManyToMany(targetEntity: Artist::class, mappedBy: 'albums')]
    private Collection $artists;

    #[ORM\OneToMany(mappedBy: 'album', targetEntity: Song::class)]
    private Collection $tracks;

    public function __construct()
    {
        $this->artists = new ArrayCollection();
        $this->tracks = new ArrayCollection();
        $this->genres = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return AlbumType
     */
    public function getAlbumType(): AlbumType
    {
        return $this->albumType;
    }

    /**
     * @param AlbumType $albumType
     * @return Album
     */
    public function setAlbumType(AlbumType $albumType): static
    {
        $this->albumType = $albumType;
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
     * @return Album
     */
    public function setSpotifyUrl(?string $spotifyUrl): static
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
     * @return Album
     */
    public function setSpotifyId(string $spotifyId): static
    {
        $this->spotifyId = $spotifyId;
        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getImages(): ArrayCollection
    {
        return $this->images;
    }

    /**
     * @param ArrayCollection $images
     * @return Album
     */
    public function setImages(ArrayCollection $images): static
    {
        $this->images = $images;
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
     * @return Album
     */
    public function setName(string $name): static
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getReleaseDate(): string
    {
        return $this->releaseDate;
    }

    /**
     * @param string $releaseDate
     * @return Album
     */
    public function setReleaseDate(string $releaseDate): static
    {
        $this->releaseDate = $releaseDate;
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
     * @return Album
     */
    public function setSpotifyApiUri(string $spotifyApiUri): static
    {
        $this->spotifyApiUri = $spotifyApiUri;
        return $this;
    }

    /**
     * @return string
     */
    public function getLabel(): string
    {
        return $this->label;
    }

    /**
     * @param string $label
     * @return Album
     */
    public function setLabel(string $label): static
    {
        $this->label = $label;
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
     * @return Collection
     */
    public function getArtists(): Collection
    {
        return $this->artists;
    }

    /**
     * @param Collection $artists
     * @return Album
     */
    public function setArtists(Collection $artists): static
    {
        $this->artists = $artists;
        return $this;
    }

    /**
     * @return Collection
     */
    public function getTracks(): Collection
    {
        return $this->tracks;
    }

    /**
     * @param Collection $tracks
     * @return Album
     */
    public function setTracks(Collection $tracks): static
    {
        $this->tracks = $tracks;
        return $this;
    }

    /**
     * @param Genre $genre
     * @return Album
     */
    public function addGenre(Genre $genre): static
    {
        if (!$this->genres->contains($genre)) {
            $this->genres->add($genre);
            $genre->addAlbum($this);
        }
        return $this;
    }

    /**
     * @param Genre $genre
     * @return Album
     */
    public function removeGenre(Genre $genre): static
    {
        if ($this->genres->contains($genre)) {
            $this->genres->removeElement($genre);
            $genre->removeAlbum($this);
        }
        return $this;
    }

    /**
     * @param Artist $artist
     * @return Album
     */
    public function addArtist(Artist $artist): static
    {
        if (!$this->artists->contains($artist)) {
            $this->artists->add($artist);
            $artist->addAlbum($this);
        }
        return $this;
    }

    /**
     * @param Artist $artist
     * @return Album
     */
    public function removeArtist(Artist $artist): static
    {
        if ($this->artists->contains($artist)) {
            $this->artists->removeElement($artist);
            $artist->removeAlbum($this);
        }
        return $this;
    }
}
