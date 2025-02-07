<?php

namespace App\Interfaces\Domain;

interface IDestinationRepository
{
    public function get_all_destinations();
    public function createDestination(array $request);
    public function showDestination(int $id);
    public function updateDestination(array $request);
    public function destroyDestination(int $id);
    public function tripsOfDestination(int $id);
}
