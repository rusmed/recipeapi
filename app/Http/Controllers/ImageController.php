<?php

namespace App\Http\Controllers;

use App\Recipe;
use Illuminate\Http\Request;

class ImageController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function getRecipes()
    {
        $recipes = Recipe::with(['author', 'image'])->get()->all();
        return response()->json($recipes);
    }

    public function getRecipe($id)
    {
        $recipe = Recipe::with(['author', 'image'])->get()->find($id);
        return response()->json($recipe);
    }

    public function createRecipe(Request $request)
    {
        $recipe = Author::create($request->all());

        return response()->json($recipe, 201);
    }
}
