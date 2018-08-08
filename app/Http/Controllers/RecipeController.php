<?php

namespace App\Http\Controllers;

use App\Image;
use App\Recipe;
use Illuminate\Http\Request;

class RecipeController extends Controller
{

    /**
     * Create a new recipe
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function createRecipe(Request $request)
    {
        $this->validate($request, [
            'title' => 'required|string',
            'body' => 'required|string',
            'image_id' => 'required|integer'
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
     * Update recipe
     * @param Request $request
     * @param integer $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateRecipe(Request $request, $id)
    {
        $id = filter_var($id, FILTER_VALIDATE_INT);

        if (!$id) {
            return response()->json(['message' => 'Must be provided ID'], 400);
        }

        $this->validate($request, [
            'title' => 'required|string',
            'body' => 'required|string',
            'image_id' => 'required|integer'
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
     * Delete recipe
     * @param Request $request
     * @param integer $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteRecipe(Request $request, $id)
    {
        $id = filter_var($id, FILTER_VALIDATE_INT);

        if (!$id) {
            return response()->json(['message' => 'Must be provided ID'], 400);
        }

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

    /**
     * Get list all recipes
     * @return \Illuminate\Http\JsonResponse
     */
    public function getRecipes()
    {
        $recipes = Recipe::with(['author', 'image'])->get();
        return response()->json($recipes);
    }

    /**
     * Get recipe by id
     * @param integer $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getRecipe($id)
    {
        $id = filter_var($id, FILTER_VALIDATE_INT);

        if (!$id) {
            return response()->json(['message' => 'Must be provided ID'], 400);
        }

        $recipe = Recipe::with(['author', 'image'])->get()->find($id);
        if (!is_object($recipe)) {
            return response()->json(['message' => 'Recipe not found'], 400);
        }
        
        return response()->json($recipe);
    }
}
