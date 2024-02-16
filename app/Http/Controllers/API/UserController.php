<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\category;
use App\Models\Dua;
use App\Models\favoritedua;
use App\Models\user;
use App\Models\duausercounter;
use App\Models\remainders;

class UserController extends Controller
{
    //

    public function signup(Request $request){

        try {
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|unique:users,email',
                'password' => 'required|string|min:8',
            ]);

            $user = User::create([
                'name' => $validatedData['name'],
                'email' => $validatedData['email'],
                'password' => $validatedData['password'],
            ]);
            return response()->json(['message' => 'Registration successful', 'user' => $user],201);
        } catch (\Exception $e) {
            // Handle all exceptions
            return response()->json(['error' => $e->getMessage()], 500);
        }

    }
    public function GetAllCategories()
    {
        try {
            // Retrieve all categories from the database
            $categories = Category::all();

            // Return a JSON response with the categories
            return response()->json(['categories' => $categories], 200);
        } catch (\Throwable $e) {
            // Handle exceptions
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function GetDuaByCategoryId($id)
    {
        try {
            // Retrieve duas by category ID
            $duas = Dua::where('user_id', 0)->where('category_id', $id)->get();

            // Return a JSON response with the retrieved duas
            return response()->json(['duas' => $duas], 200);
        } catch (\Throwable $e) {
            // Handle exceptions
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    public function GetDuaByDuaId($id){
        try {
            // Retrieve the dua by ID with its relationships
            $dua = Dua::with('favoriteduas', 'remainders')->findOrFail($id);

            // Return a JSON response with the retrieved dua and its details
            return response()->json(['dua' => $dua], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            // Handle the case where the dua with the given ID is not found
            return response()->json(['error' => 'Dua not found'], 404);
        } catch (\Throwable $e) {
            // Handle other exceptions
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function FavoriteDua($duaId,$userId)
    {
        try {
            // Find the dua by ID
            $dua = Dua::findOrFail($duaId);

            // Check if the user has already favorited the dua
            $existingFavorite = favoritedua::where('user_id', $userId)
                ->where('dua_id', $duaId)
                ->exists();

            // If the user hasn't already favorited the dua, create a new favorite
            if (!$existingFavorite) {
                $favorite = new favoritedua();
                $favorite->user_id = $userId;
                $favorite->dua_id = $duaId;
                $favorite->save();

                // Return a success response
                return response()->json(['message' => 'Dua favorited successfully'], 200);
            } else {
                // Return a response indicating that the dua is already favorited
                return response()->json(['message' => 'Dua is already favorited'], 200);
            }
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            // Handle the case where the dua with the given ID is not found
            return response()->json(['error' => 'Dua not found'], 404);
        } catch (\Throwable $e) {
            // Handle other exceptions
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function UnfavoriteDua($duaId,$userId)
    {
        try {
            // Check if the user has favorited the dua
            $favorite = favoritedua::where('user_id', $userId)
                ->where('dua_id', $duaId)
                ->first();

            // If the favorite record exists, delete it
            if ($favorite) {
                $favorite->delete();

                // Return a success response
                return response()->json(['message' => 'Dua unfavorited successfully'], 200);
            } else {
                // Return a response indicating that the dua is not favorited by the user
                return response()->json(['message' => 'Dua is not favorited by the user'], 200);
            }
        } catch (\Throwable $e) {
            // Handle exceptions
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }


    public function GetFavoriteDuabyUserId($id)
    {
        try {
            $favoriteDuas = Dua::whereHas('favoriteduas', function ($query) use ($id) {
                $query->where('user_id', $id);
            })->get();


            // Return a JSON response with the retrieved favorite duas
            return response()->json(['duas' => $favoriteDuas], 200);
        } catch (\Throwable $e) {
            // Handle exceptions
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function AddDua(Request $request){
        try {
            // Validate the incoming request data
            $validatedData = $request->validate([
                'user_id' => 'required|exists:users,id',
                'category_id' => 'nullable|exists:category,id',
                'dua_name' => 'required|array|max:255',
                'audiolink' => 'nullable|string',
                'urdu_translation' => 'nullable|string',
                'english_translation' => 'nullable|string',
                'arabic_translation' => 'nullable|string',
                'transliteration' => 'nullable|string',
            ]);

            // Create the dua using the validated data
            $dua = Dua::create($validatedData);

            // Return a success response
            return response()->json(['message' => 'Dua created successfully', 'dua' => $dua], 201);
        } catch (\Throwable $e) {
            // Handle exceptions
            return response()->json(['error' => $e->getMessage()], 500);
        }

    }

    // public function GetAllDuas(Request $request){
    //     try {
    //         // Retrieve all duas with user_id = 0 from the database
    //         $duas = Dua::where('user_id', 0)->where('user_id',$request->input('user_id'))->get();

    //         // Return a JSON response with the filtered duas
    //         return response()->json(['duas' => $duas], 200);
    //     } catch (\Throwable $e) {
    //         // Handle exceptions
    //         return response()->json(['error' => $e->getMessage()], 500);
    //     }
    // }
    public function GetDuasByCategory(Request $request){
        try {
            // Retrieve all duas from the database

            $categoryId = $request->input('category_id');

            // Retrieve all duas with the specified user_id and category_id from the database
            $duas = Dua::where('user_id',$request->input('user_id'))->where('category_id', $categoryId)->get();

            // Return a JSON response with the duas
            return response()->json(['duas' => $duas], 200);
        } catch (\Throwable $e) {
            // Handle exceptions
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    // public function EditDuaByDuaId(Request $request,$id)
    // {
    //     try {

    //         // Find the dua by ID
    //         $dua = Dua::findOrFail($id);

    //         // Validate the incoming request data
    //         $validatedData = $request->validate([
    //             'user_id' => 'required|exists:users,id',
    //             'category_id' => 'nullable|exists:category,id',
    //             'dua_name' => 'required|array|max:255',
    //             'audiolink' => 'nullable|string',
    //             'urdu_translation' => 'nullable|string',
    //             'english_translation' => 'nullable|string',
    //             'arabic_translation' => 'nullable|string',
    //             'transliteration' => 'nullable|string',

    //         ]);

    //         // Update the dua with the validated data
    //         $dua->update($validatedData);

    //         // Return a success response
    //         return response()->json(['message' => 'Dua updated successfully', 'dua' => $dua], 200);
    //     } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
    //         // Handle the case where the dua with the given ID is not found
    //         return response()->json(['error' => 'Dua not found'], 404);
    //     } catch (\Throwable $e) {
    //         // Handle other exceptions
    //         return response()->json(['error' => $e->getMessage()], 500);
    //     }
    // }

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
    public function updateCounter(Request $request, $duaId)
    {
        try {
            // Find the dua by ID
            //$dua = Dua::findOrFail($duaId);

            // Increment the counter for the dua
            $counter = duausercounter::where('dua_id', $duaId)->where('user_id',$request->input('user_id'))->first();
            if (!$counter) {
                $counter = new duausercounter();
                $counter->dua_id = $duaId;
                $counter->user_id=$request->input('user_id');
                $counter->count = 1;
            } else {
                $counter->count++;
            }
            $counter->save();

            // Return a success response
            return response()->json(['message' => 'Counter updated successfully'], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            // Handle the case where the dua with the given ID is not found
            return response()->json(['error' => 'Dua not found'], 404);
        } catch (\Throwable $e) {
            // Handle other exceptions
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function ResetCounter($duaId,$userId){
        try {
            // Find the authenticated user


            // Find the counter record for the specified dua and user
            $counter = duausercounter::where('dua_id', $duaId)
                ->where('user_id', $userId)
                ->first();

            // If the counter record exists, delete it
            if ($counter) {
                $counter->delete();

                // Return a success response
                return response()->json(['message' => 'Counter reset successfully'], 200);
            } else {
                // Return a response indicating that the counter record does not exist
                return response()->json(['message' => 'Counter not found'], 200);
            }
        } catch (\Throwable $e) {
            // Handle exceptions
            return response()->json(['error' => $e->getMessage()], 500);
        }

    }

    public function setReminder(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'user_id' => 'required|exists:users,id',
                'dua_id' => 'required|exists:dua,id',
                'category' => 'nullable',
                'reminder' => 'required|date', // Adjust validation as per your requirement
            ]);

            // Create a new reminder record
            $reminder = new remainders();
            $reminder->user_id = $validatedData['user_id'];
            $reminder->dua_id = $validatedData['dua_id'];
            $reminder->category = $validatedData['category'];
            $reminder->remainder = $validatedData['reminder'];
            $reminder->save();

            return response()->json(['message' => 'Reminder set successfully'], 200);
        } catch (\Exception $e) {
            // Other exceptions
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    public function getRemainder($id)
{
    try {
        // Validate the request data


        // Retrieve reminders for the specified user
        $reminders = remainders::with('dua')->where('user_id', $id)->get();

        // Return a JSON response with the retrieved reminders
        return response()->json(['reminders' => $reminders], 200);
    } catch (\Exception $e) {
        // Handle exceptions
        return response()->json(['error' => $e->getMessage()], 500);
    }
}
    public function deleteReminder(Request $request)
    {
        $validatedData = $request->validate([
            'user_id' => 'required|exists:users,id',
            'dua_id' => 'required|exists:dua,id',
        ]);

        // Find the reminder record
        $reminder = remainders::where('user_id', $validatedData['user_id'])
                            ->where('dua_id', $validatedData['dua_id'])
                            ->first();

        // If reminder exists, delete it
        if ($reminder) {
            $reminder->delete();
            return response()->json(['message' => 'Reminder deleted successfully'], 200);
        } else {
            return response()->json(['message' => 'Reminder not found'], 404);
        }
    }
}
