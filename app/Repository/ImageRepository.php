<?php

namespace App\Repository;

use App\Models\Image;

class ImageRepository implements Repository
{
    public function create(array $attributes): Image
    {
        return Image::create($attributes);
    }

    public function find(string $id): Image
    {
        return Image::query()->findOrFail($id);
    }

    public function update(string $id, array $attributes): Image
    {
        $image = $this->find($id);
        $image->update($attributes);
        return $image;
    }

    public function delete(string $id): Image
    {
        $image = $this->find($id);
        $image->delete();
        return $image;
    }
}
