<?php

namespace App\Http\Controllers\Image;

use App\Http\Controllers\Controller;
use App\Http\Requests\Image\DeleteRequest;
use App\Models\Image;
use App\Repository\ImageRepository;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class DeleteController extends Controller
{
    private ImageRepository $imageRepository;

    public function __construct(ImageRepository $imageRepository)
    {
        $this->imageRepository = $imageRepository;
    }

    public function __invoke(DeleteRequest $request, string $id)
    {
        $image = $this->getImage($id);

        DB::beginTransaction();

        // delete record image to database
        $image = $this->deleteImageToDatabase($image);

        // delete image to server
        $this->deleteImageToServer($image);

        DB::commit();

        return response()->json([
            "success" => true,
            "message" => "successfully",
        ]);
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

    private function deleteImageToDatabase(Image $image): Image
    {
        try {
            $image = $this->imageRepository->delete($image->id);
        } catch (\Exception $exception) {
            DB::rollBack();
        }

        return $image;
    }

    private function deleteImageToServer(Image $image)
    {
        $filepath = base_path("files/{$image->base_path}/{$image->id}.{$image->ext}");

        try {
            if (File::exists($filepath)) {
                File::delete($filepath);
            }
        } catch (\Exception $exception) {
            DB::rollBack();
        }
    }
}
