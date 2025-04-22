<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    // User list
    public function index()
    {   
        $users = User::all();
        if ($users->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Data not found'
            ], 404);
        }

        return response()->json(['success' => true, 'message' => 'User List', 'data' => $users], 200);
    }

    // User detail
    public function show($id)
    {
        $user = User::find($id);
        if (!$user) return response()->json(['success' => false, 'message' => 'User not found'], 404);
        return response()->json(['success' => true, 'message' => 'User details', 'data' => $user], 200);
    }

    // Create user
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => md5($request->password),
        ]);

        $user->assignRole('employee');

        return response()->json([
            'success' => true,
            'message' => 'User created successfully.',
            'data' => $user,
        ], 200);
    }

    // Update user
    public function update(Request $request, $id)
    {
        $user = User::find($id);
        if (!$user) return response()->json(['success' => false,'message' => 'User not found'], 404);

        $validator = Validator::make($request->all(), [
            'name'  => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|email|unique:users,email,' . $id,
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user->update($request->only('name', 'email'));
        return response()->json(['success' => true, 'message' => 'User updated successfully.', 'data' => $user], 200);
    }

    // Delete user
    public function destroy($id)
    {   
        $user = User::find($id);
        if (!$user) return response()->json(['success' => false,'message' => 'User not found'], 404);

        $user->delete();
        return response()->json(['success' => true, 'message' => 'User deleted successfully.']);
    }
}

