<?php

namespace App\Repository;

interface Repository
{
    public function create(array $attributes);
    public function find(string $id);
    public function update(string $id, array $attributes);
    public function delete(string $id);
}
