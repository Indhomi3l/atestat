<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class Lyric
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::BIGINT)]
    private int $id;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $content=null;
    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $meaning=null;

    private Song $song;

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
    public function getContent(): ?string
    {
        return $this->content;
    }

    /**
     * @param string $content
     * @return Lyric
     */
    public function setContent(string $content): Lyric
    {
        $this->content = $content;
        return $this;
    }

    /**
     * @return string
     */
    public function getMeaning(): ?string
    {
        return $this->meaning;
    }

    /**
     * @param string $meaning
     * @return Lyric
     */
    public function setMeaning(string $meaning): Lyric
    {
        $this->meaning = $meaning;
        return $this;
    }

    /**
     * @return Song
     */
    public function getSong(): Song
    {
        return $this->song;
    }

    /**
     * @param Song $song
     * @return Lyric
     */
    public function setSong(Song $song): Lyric
    {
        $this->song = $song;
        return $this;
    }
}
