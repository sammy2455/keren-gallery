<?php

namespace App\Http\Controllers\Image;

use App\Http\Controllers\Controller;
use App\Http\Requests\Image\UploadRequest;
use App\Models\Image;
use App\Repository\ImageRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Spatie\ImageOptimizer\OptimizerChain;

class UploadController extends Controller
{
    private ImageRepository $imageRepository;

    public function __construct(ImageRepository $imageRepository)
    {
        $this->imageRepository = $imageRepository;
    }

    public function __invoke(UploadRequest $request)
    {
        // get image
        $imageFile = $request->file('image');

        DB::beginTransaction();

        // create a record in the database
        $image = $this->createRecordInDatabase($imageFile);

        // optimizer image
        $this->optimizerImage($imageFile, $image);

        // update image to server
        $this->uploadImageToServer($imageFile, $image);

        DB::commit();

        //
        return $this->createResponse($image);
    }

    private function createRecordInDatabase(UploadedFile $imageFile): Image
    {
        $attributes = [];

        $imageSize = getimagesize($imageFile);

        $attributes['id'] = Str::uuid()->toString();
        $attributes['base_path'] = 'image';
        $attributes['width'] = ($imageSize)? $imageSize[0] : null;
        $attributes['height'] = ($imageSize)? $imageSize[1] : null;
        $attributes['original_filesize'] = filesize($imageFile);
        $attributes['mime'] = $imageFile->getMimeType();
        $attributes['ext'] = $imageFile->getClientOriginalExtension();

        try {
            $image = $this->imageRepository->create($attributes);
        } catch (\Exception $exception) {
            DB::rollBack();
        }

        return $image;
    }

    private function optimizerImage(UploadedFile $imageFile, Image $image)
    {
        // optimizer image in server
        app(OptimizerChain::class)->optimize($imageFile->getPathname());

        $attributes = [];

        $attributes['filesize'] = filesize($imageFile);

        try {
            $image = $this->imageRepository->update($image->id, $attributes);
        } catch (\Exception $exception) {
            DB::rollBack();
        }
    }

    private function uploadImageToServer(UploadedFile $imageFile, Image $image)
    {
        try {
            $name = "{$image->id}.{$imageFile->getClientOriginalExtension()}";
            $imageFile->move(base_path("files/{$image->base_path}"), $name);
        } catch (\Exception $exception) {
            DB::rollBack();
        }
    }

    private function createResponse(Image $image): JsonResponse
    {
        $internalUrl = env('INTERNAL_URL');
        $externalUrl = env('EXTERNAL_URL');

        return response()->json([
            "success" => true,
            "id" => $image->id,
            "internal_url" => "{$internalUrl}/{$image->base_path}/{$image->id}",
            "url" => "{$externalUrl}/{$image->base_path}/{$image->id}",
        ]);
    }
}
