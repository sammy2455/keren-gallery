<?php

namespace App\Http\Controllers\Image;

use App\Http\Controllers\Controller;
use App\Http\Requests\Image\ShowRequest;
use App\Models\Image;
use App\Repository\ImageRepository;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\File;

class ShowController extends Controller
{
    private ImageRepository $imageRepository;

    public function __construct(ImageRepository $imageRepository)
    {
        $this->imageRepository = $imageRepository;
    }

    public function __invoke(ShowRequest $request, string $id)
    {
        $image = $this->getImage($id);

        //
        return $this->createResponse($image);
    }

    private function getImage(string $id)
    {
        try {
            $image = $this->imageRepository->find($id);
        } catch (\Exception $exception) {
            throw new HttpResponseException(response(null, 404));
        }

        return $image;
    }

    private function createResponse(Image $image)
    {
        $filepath = base_path("files/{$image->base_path}/{$image->id}.{$image->ext}");

        if (!File::exists($filepath)) {
            return response(null, 404);
        }

        $imageFile = File::get($filepath);

        $response = new Response();

        $response->setContent($imageFile);
        $response->setStatusCode(200);
        $response->header('Content-Type', $image->mime);

        return $response;
    }
}
