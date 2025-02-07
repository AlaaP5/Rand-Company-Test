<?php

namespace App\Interfaces\Domain;


interface ITripRepository
{
    public function indexOfTrip(array $request);
    public function createTrip(array $request);
    public function showTrip(int $id);
    public function updateTrip(array $request);
    public function destroyTrip(int $id);
}
