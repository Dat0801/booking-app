<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\File;

class ImageController extends Controller
{
    public function upload(Request $request): JsonResponse
    {
        $request->validate([
            'image' => [
                'required',
                File::image()
                    ->max(5120), // 5MB
            ],
            'folder' => ['sometimes', 'string', 'max:255'],
        ]);

        $folder = $request->string('folder', 'products')->toString();
        $file = $request->file('image');

        $path = $file->store($folder, 'public');

        $url = Storage::disk('public')->url($path);

        return response()->json([
            'url' => $url,
            'path' => $path,
        ], 201);
    }

    public function delete(Request $request): JsonResponse
    {
        $request->validate([
            'path' => ['required', 'string'],
        ]);

        $path = $request->string('path')->toString();

        if (Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);

            return response()->json([
                'message' => 'Image deleted successfully.',
            ]);
        }

        return response()->json([
            'message' => 'Image not found.',
        ], 404);
    }
}
