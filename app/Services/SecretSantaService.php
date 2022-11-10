<?php

namespace App\Services;

use App\Models\User;
use Exception;
use Illuminate\Support\Collection;

final class SecretSantaService
{
    private Collection $participants;

    /**
     *
     * Execute attaching secret-santa algorithm to given collection
     *
     * @throws Exception
     *
     * @return $this
     */
    public function apply(): self
    {
        if (empty($this->participants)|| $this->participants->count() < 2) {
            throw new Exception('Participants not set or invalid');
        }

        /**
         * Simple algorithm without using graph nodes & edges
         * Time complexity of algorithm is - O(n)
         * Space complexity of algorithm is - O(n) - because we created new shuffled collection before executing algorithm
         */
        for ($i = 0; $i < $this->participants->count(); $i++) {
            if ($i === $this->participants->count() - 1) {
                $this->participants[$i]->update([
                    'santa_id' => $this->participants[0]->id
                ]);

                break;
            }

            $this->participants[$i]->update([
                'santa_id' => $this->participants[$i + 1]->id
            ]);
        }

        return $this;

    }

    /**
     * Retrieve models if needed then shuffle collection and set this collection
     *
     * @param array|Collection $participants
     * @return $this
     */
    public function setParticipants(array|Collection $participants): self
    {
        $participants = is_array($participants)
            ? User::whereIn('id', $participants)->get()
            : $participants;

        $this->participants = $participants->shuffle();

        return $this;
    }

    /**
     * Return participants
     *
     * @return Collection
     */
    public function getParticipants(): Collection
    {
        return $this->participants;
    }

}
