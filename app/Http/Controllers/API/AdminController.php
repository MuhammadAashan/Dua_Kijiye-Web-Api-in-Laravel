<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\category;
use App\Models\dua;

class AdminController extends Controller
{
    //
    public function AddCategory(Request $request)
    {
        try {
            // Validate the incoming request data
            $validatedData = $request->validate([
                'name' => 'required|array|max:255|unique:category,name',
            ]);
            $categoryName = $validatedData['name'];
            // Check if the category already exists
            $existingCategory = Category::pluck('name')->flatten();

            foreach ($existingCategory as $existingCat) {
                if ($existingCat== $categoryName['name']) {
                    return response()->json(['message' => 'Category already exists'], 409);
                }
        }
            // Create a new category
            $category = Category::create($validatedData);

            // Return a success response
            return response()->json(['message' => 'Category created successfully', 'category' => $category], 201);
        } catch (\Throwable $e) {
            // Handle exceptions
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }


    public function GetAllCategories()
    {
        try {
            // Retrieve all categories from the database
            $categories = Category::with('duas')->get();

            // Return a JSON response with the categories
            return response()->json(['categories' => $categories], 200);
            //           $categories = Category::with('duas')->get();
            //                 $categoryNames = [];

            // foreach ($categories as $category) {
            //      // Decode the JSON string
            //     $categoryNames[] = $category->name['name']; // Access the 'name' property
            // }

            // return $categoryNames;


        } catch (\Throwable $e) {
            // Handle exceptions
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function EditCategory(Request $request, $id)
    {
        try {

            // Find the category by ID
            $category = Category::findOrFail($id);

            // Validate the incoming request data
            $validatedData = $request->validate([
                'name' => 'required|array|max:255|unique:category,name,' . $id,
            ]);

            // Update the category with the validated data
            $category->update($validatedData);

            // Return a success response
            return response()->json(['message' => 'Category updated successfully', 'category' => $category], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            // Handle the case where the category with the given ID is not found
            return response()->json(['error' => 'Category not found'], 404);
        } catch (\Throwable $e) {
            // Handle other exceptions
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }


    public function AddDua(Request $request)
    {
        try {
            // Validate the incoming request data
            $validatedData = $request->validate([
                'user_id' => 'nullable|integer',
                'category_id' => 'nullable|exists:category,id',
                'dua_name' => 'required|array|max:255',
                'audiolink' => 'nullable|string',
                'urdu_translation' => 'nullable|string',
                'english_translation' => 'nullable|string',
                'arabic_translation' => 'nullable|string',
                'transliteration' => 'nullable|string',
            ]);
            // Extract dua names from validated data
            $duaNames = collect($validatedData['dua_name'])->pluck('english');
            // Check if any of the dua names already exist in the database
            $existingDuas = Dua::pluck('dua_name')->flatten()->toArray();

            foreach ($existingDuas as $existingDua) {
                    if ($existingDua== $duaNames[0]) {
                        return response()->json(['message' => 'Some Duas already exist'], 400);
                    }
            }
            //Create the dua using the validated data
            $dua = Dua::create($validatedData);

            //Return a success response
            return response()->json(['message' => 'Dua created successfully', 'dua' => $dua], 201);
        } catch (\Throwable $e) {
            // Handle exceptions
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function GetAllDuas()
    {
        try {
            // Retrieve all duas with user_id = 0 from the database
            $duas = Dua::where('user_id', 0)->get();

            // Return a JSON response with the filtered duas
            return response()->json(['duas' => $duas], 200);
        } catch (\Throwable $e) {
            // Handle exceptions
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    public function GetDuasByCategory($id)
    {
        try {
            // Retrieve all duas from the database


            // Retrieve all duas with the specified user_id and category_id from the database
            $duas = Dua::where('user_id', 0)->where('category_id', $id)->get();

            // Return a JSON response with the duas
            return response()->json(['duas' => $duas], 200);
        } catch (\Throwable $e) {
            // Handle exceptions
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function EditDuaByDuaId(Request $request, $id)
    {
        try {

            // Find the dua by ID
            $dua = Dua::findOrFail($id);

            // Validate the incoming request data
            $validatedData = $request->validate([
                'user_id' => 'default:0',
                'category_id' => 'nullable|exists:category,id',
                'dua_name' => 'required|array|max:255',
                'audiolink' => 'nullable|string',
                'urdu_translation' => 'nullable|string',
                'english_translation' => 'nullable|string',
                'arabic_translation' => 'nullable|string',
                'transliteration' => 'nullable|string',

            ]);

            // Update the dua with the validated data
            $dua->update($validatedData);

            // Return a success response
            return response()->json(['message' => 'Dua updated successfully', 'dua' => $dua], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            // Handle the case where the dua with the given ID is not found
            return response()->json(['error' => 'Dua not found'], 404);
        } catch (\Throwable $e) {
            // Handle other exceptions
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function DeleteDua($id)
    {
        try {

            // Find the dua by ID
            $dua = Dua::findOrFail($id);

            // Delete the dua
            $dua->delete();

            // Return a success response
            return response()->json(['message' => 'Dua deleted successfully'], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            // Handle the case where the dua with the given ID is not found
            return response()->json(['error' => 'Dua not found'], 404);
        } catch (\Throwable $e) {
            // Handle other exceptions
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
