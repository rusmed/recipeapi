<?php

namespace App\Http\Controllers;

use App\Image;
use App\Recipe;
use Illuminate\Http\Request;

class RecipeController extends Controller
{

    public function getRecipes()
    {
        $recipes = Recipe::with(['author', 'image'])->get()->all();
        return response()->json($recipes);
    }

    /**
     * @param integer $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getRecipe($id)
    {
        $recipe = Recipe::with(['author', 'image'])->get()->find($id);
        return response()->json($recipe);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function createRecipe(Request $request)
    {
        $this->validate($request, [
            'title' => 'required',
            'body' => 'required',
            'image_id' => 'required'
        ]);

        $image = Image::find($request->input('image_id'));
        if (!is_object($image)) {
            return response()->json(['message' => 'Image not found'], 400);
        }

        $data = [
            'title' => $request->input('title'),
            'body' => $request->input('body'),
            'author_id' => $request->user()->id,
            'image_id' => $request->input('image_id')
        ];

        $recipe = Recipe::create($data);

        $recipe->author = $request->user();
        $recipe->image = $image;

        return response()->json($recipe, 201);
    }

    /**
     * @param Request $request
     * @param integer $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateRecipe(Request $request, $id)
    {
        $this->validate($request, [
            'title' => 'required',
            'body' => 'required',
            'image_id' => 'required'
        ]);

        $recipe = Recipe::find($id);
        if (!is_object($recipe)) {
            return response()->json(['message' => 'Recipe not found'], 400);
        }

        if ($recipe->author_id != $request->user()->id) {
            return response()->json(['message' => 'Access denied!'], 403);
        }

        if ($request->input('image_id') != $recipe->image_id) {
            $image = Image::find($request->input('image_id'));
            if (!is_object($image)) {
                return response()->json(['message' => 'Image not found'], 400);
            }
        }

        $data = [
            'title' => $request->input('title'),
            'body' => $request->input('body'),
            'image_id' => $request->input('image_id')
        ];

        Recipe::where('id', $id)->update($data);

        $recipe = Recipe::with(['author', 'image'])->get()->find($id);

        return response()->json($recipe, 200);
    }

    /**
     * @param Request $request
     * @param integer $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteRecipe(Request $request, $id)
    {
        $recipe = Recipe::find($id);
        if (!is_object($recipe)) {
            return response()->json(['message' => 'Recipe not found'], 400);
        }

        if ($recipe->author_id != $request->user()->id) {
            return response()->json(['message' => 'Access denied!'], 403);
        }

        Recipe::destroy($id);

        return response()->json(null, 204);
    }
}
