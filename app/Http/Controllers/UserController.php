<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * Return a reponse object containing the status of the operation that has been performed, and the message alongside it.
     *
     * @return JSON The response containing the status of the operation.
     */
    private function operationResponse($status, $message)
    {
        return response()->json([
            'status' => $status,
            'message' => $message
        ]);
    }

    /**
     * Gets the list of all users from the database.
     *
     * @return JSON The JSON data containing the list of users.
     */
    public function getUserList(Request $request)
    {
        $users = User::all();

        return Response()->json($users);
    }

    /**
     * Insert a new user to the database with the data provided in the request object.
     *
     * @return JSON The JSON data containing the status of the operation.
     */
    public function createUser(Request $request)
    {
        // Validate the request object to contain the neccesary inputs
        $validator = Validator::make($request->all(), [
            'username' => 'required|unique:users,username',
            'password' => 'required'
        ]);

        if ($validator->fails())
            return $this->operationResponse(Response::HTTP_BAD_REQUEST, $validator->messages());

        // Create the user when the validation passes
        $newUser = new User();
        $newUser->username = $request->username;
        $newUser->password = Hash::make($request->password);
        $newUser->save();

        return $this->operationResponse(Response::HTTP_OK, 'Successfully created new user!');
    }

    /**
     * Update a existing user with the data provided in the request object.
     *
     * @return JSON The JSON data containing the status of the operation.
     */
    public function updateUser(Request $request)
    {
        // Validate the incoming request object to contain the required inputs
        $validator = Validator::make($request->all(), [
            'id' => 'required|numeric',
            'password' => 'required|confirmed',
            'new_password' => 'required',
        ]);

        if ($validator->fails())
            return $this->operationResponse(Response::HTTP_BAD_REQUEST, $validator->messages());

        // Get the user with the specified id. If none are found, return a not found response.
        $user = User::find($request->id);

        if ($user == null)
            return $this->operationResponse(Response::HTTP_NOT_FOUND, 'User not found!');

        // If the given password does not match the one in the database, then return an unauthorized response.
        if (!Hash::check($request->password, $user->password))
            return $this->operationResponse(Response::HTTP_UNAUTHORIZED, 'User password does not match');

        // Change user password if passes all validation
        $user->password = Hash::make($request->new_password);
        $user->save();

        return $this->operationResponse(Response::HTTP_OK, 'Successfully updated the user.');
    }

    /**
     * Delete an existing user from the database if one exists.
     *
     * @return JSON The JSON data containing the status of the operation.
     */
    public function deleteUser(Request $request)
    {
        // Validate the incoming request object to contain the required inputs
        $validator = Validator::make($request->all(), [
            'id' => 'required|numeric',
            'password' => 'required'
        ]);

        if ($validator->fails())
            return $this->operationResponse(Response::HTTP_BAD_REQUEST, $validator->messages());

        // Get the user with the specified id. If none are found, return a not found response.
        $user = User::find($request->id);

        if ($user == null)
            return $this->operationResponse(Response::HTTP_NOT_FOUND, 'User not found!');

        // If the given password does not match the one in the database, then return an unauthorized response.
        if (!Hash::check($request->password, $user->password))
            return $this->operationResponse(Response::HTTP_UNAUTHORIZED, 'User password does not match');

        // Soft deletes the user from the database if passes all validation
        $user->delete();

        return $this->operationResponse(Response::HTTP_OK, 'Successfully deleted the user.');
    }
}
