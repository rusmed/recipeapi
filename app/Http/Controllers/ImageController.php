<?php

namespace App\Http\Controllers;

use App\Image;
use App\Recipe;
use Illuminate\Http\Request;

/**
 * Class ImageController
 * @package App\Http\Controllers
 */
class ImageController extends Controller
{

    /**
     * @param Request $request
     */
    public function uploadImage(Request $request)
    {
        $imageFile = $request->file('image');
        if (!$imageFile) {
            return response()->json(['image' => 'The image field is required.'],400);
        }

        $ext = pathinfo($imageFile->getClientOriginalName(), PATHINFO_EXTENSION);
        $filename = uniqid() . '_' . time() . '.' . $ext;
        $request->file('image')->move(UPLOADS_DIR, $filename);

        $image = Image::create(['url' => $filename]);

        return response()->json($image, 201);
    }

}
