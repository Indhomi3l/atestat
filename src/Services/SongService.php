<?php

namespace App\Services;

use App\Repository\SongRepository;

class SongService
{

    public function __construct(
        private readonly SongRepository $repository
    )
    {}

    public function getSongsForHomePage() {
        return $this->repository->getLastFifteenSongs();
    }
}
