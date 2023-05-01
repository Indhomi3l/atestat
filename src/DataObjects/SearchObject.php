<?php

namespace App\DataObjects;

class SearchObject
{
    private ?string $songName = null;
    private ?string $artistName = null;

    private ?int $selectedSong = 0;

    /**
     * @return string
     */
    public function getSongName(): string
    {
        return $this->songName;
    }

    /**
     * @param string $songName
     * @return SearchObject
     */
    public function setSongName(string $songName): SearchObject
    {
        $this->songName = $songName;
        return $this;
    }

    /**
     * @return string
     */
    public function getArtistName(): string
    {
        return $this->artistName;
    }

    /**
     * @param string $artistName
     * @return SearchObject
     */
    public function setArtistName(string $artistName): SearchObject
    {
        $this->artistName = $artistName;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getSelectedSong(): ?int
    {
        return $this->selectedSong;
    }

    /**
     * @param int|null $selectedSong
     * @return SearchObject
     */
    public function setSelectedSong(?int $selectedSong): SearchObject
    {
        $this->selectedSong = $selectedSong;
        return $this;
    }

}
