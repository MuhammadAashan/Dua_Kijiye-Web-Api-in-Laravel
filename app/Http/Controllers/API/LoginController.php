<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\User;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;



class LoginController extends Controller
{
    //
    public function login(Request $request)
    {

        $email = $request->input('email');
        $password = $request->input('password');
        $admin = Admin::where('email', $email)->where('password', $password)->first();
        $user = User::where('email', $email)->where('password', $password)->first();
        if ($admin != null) {
            return response()->json($admin, 200);
        } elseif ($user != null) {
            return response()->json( $user, 200);
        }
        return response()->json(['error' => 'Resource not found'], Response::HTTP_NOT_FOUND);
    }


    public function update_profile(Request $request){
        try {
            $id= $request -> input("id");

            $is_admin = $request->has('role') && $request->input('role') === 'admin';

            // Find the user or admin by their ID
            $model = $is_admin ? Admin::findOrFail($id) : User::findOrFail($id);

            // Update the name, email, and password if provided in the request
            if ($request->filled('name')) {
                $model->name = $request->input('name');
            }

            if ($request->filled('email')) {
                $model->email = $request->input('email');
            }

            if ($request->filled('password')) {
                $model->password =$request->input('password');
            }

            // Save the changes to the database
            $model->save();

            // Return a success response
            return response()->json(['data'=>$model,'message' => 'User or admin updated successfully']);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            // Handle the case where the user or admin with the given ID is not found
            return response()->json(['error' => 'User or admin not found'], Response::HTTP_NOT_FOUND);
        } catch (\Throwable $e) {
            // Handle other exceptions
            return response()->json(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

    }


    public function requestReset(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        // Check if the email belongs to a user
        $user = User::where('email', $request->email)->first();
        if ($user) {
            $this->sendResetEmail($user);
            return response()->json(['message' => 'Password reset email sent successfully']);
        }

        // Check if the email belongs to an admin
        $admin = Admin::where('email', $request->email)->first();
        if ($admin) {
            // Implement admin password reset logic here
            // Example: $this->sendAdminResetEmail($admin);
            return response()->json(['message' => 'Admin password reset email sent successfully']);
        }

        return response()->json(['error' => 'Email not found'], 404);
    }

    public function resetPassword(Request $request)
{
    $request->validate([
        'email' => 'required|email',
        'password' => 'required|string|min:8',
    ]);

    // Check if the email belongs to a user
    $user = User::where('email', $request->email)->first();
    if ($user) {
        // Update the user's password
        $user->password = $request->password;
        $user->save();
        return response()->json(['message' => 'User password reset successfully']);
    }

    // Check if the email belongs to an admin
    $admin = Admin::where('email', $request->email)->first();
    if ($admin) {
        // Update the admin's password
        // Implement admin password reset logic here
        $admin->password = $request->password;
        $admin->save();
        return response()->json(['message' => 'Admin password reset successfully']);
    }

    return response()->json(['error' => 'Email not found'], 404);
}



}
