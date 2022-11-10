<?php

namespace App\DTO;

final class PlaySecretSantaDto
{
    /** @var array */
    private $userIds;

    public function __construct(array $userids)
    {
        $this->userIds = $userids;
    }

    public function getUserIds()
    {
        return $this->userIds;
    }
}
