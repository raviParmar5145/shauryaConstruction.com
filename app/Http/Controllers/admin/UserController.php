<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserController extends Controller
{
    public function index(Request $request) {
        // Get the currently authenticated admin
        $admin = Auth::guard('admin')->user();
    
        // Start building the query to retrieve users
        $usersQuery = User::where('id', '!=', $admin->id)->latest();
    
        // Check if there's a search query
        if ($request->has('table_search')) {
            $searchQuery = $request->input('table_search');
            
            // Add a condition to filter users by name, email, role, or phone using OR conjunction
            $usersQuery->where(function($query) use ($searchQuery) {
                $query->where('name', 'like', '%' . $searchQuery . '%')
                      ->orWhere('email', 'like', '%' . $searchQuery . '%')
                      ->orWhere('role', 'like', '%' . $searchQuery . '%')
                      ->orWhere('phone', 'like', '%' . $searchQuery . '%');
            });
        }
    
        // Paginate the results
        $users = $usersQuery->paginate(10);
    
        // Pass the users to the view
        return view('admin.users.list', compact('users'));
    }
    
    
    
    public function create() {
        return view('admin.users.create');
    }

    public function store(Request $request) {
        // Validate request data
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|digits:10|unique:users,phone', // 10 digits phone number
            'password' => 'required|min:6',
            'role' => 'required|in:1,2', // 1 for Customer, 2 for Admin
        ]);
    
        // If validation fails, return errors
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    
        // Create new user
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->password = bcrypt($request->password);
        $user->role = $request->role; // Either 1 or 2 based on your requirements
        $user->save();
    
        // Flash a success message to the session (optional, you can handle success messages differently if preferred)
        $request->session()->flash('success', 'User added successfully.');
    
        // Return success response
        return response()->json([
            'status' => true,
            'message' => 'User added successfully.'
        ]);
    }
        
    public function edit($usersId, Request $request) {
        $users = User::find($usersId);
     
        if (empty($users)) {
            return redirect()->route('users.index');
        }
        return view('admin.users.edit', compact('users'));
    }

    public function update(Request $request, $userId)
    {
        // Find the user by ID
        $user = User::find($userId);
        
        // Check if the user exists
        if (empty($user)) {
            // Flash an error message and return a JSON response indicating the user was not found
            $request->session()->flash('error', 'User not found');
            return response()->json([
                'status' => true,
                'notFound' => true,
                'message' => 'User not found'
            ]);
        }
    
        // Define validation rules
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'required|digits:10|unique:users,phone,' . $user->id,
            'role' => 'required|in:1,2', // Validate the role, allowing only 1 (customer) or 2 (admin)
        ]);
    
        // Check if the validation passes
        if ($validator->passes()) {
            // Update user details
            $user->name = $request->input('name');
            $user->email = $request->input('email');
            $user->phone = $request->input('phone');
            $user->role = $request->input('role');
            
            // Save the user
            $user->save();
    
            // Flash a success message
            $request->session()->flash('success', 'User updated successfully');
    
            // Return a successful JSON response
            return response()->json([
                'status' => true,
                'message' => 'User updated successfully'
            ]);
        } else {
            // Return JSON response with validation errors
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }
    
    public function show($usersId, Request $request) {
        $users = User::find($usersId);
    
        if (empty($users)) {
            return redirect()->route('users.index');
        }
        
        return view('admin.users.show', compact('users'));
    }
    

    public function destroy($usersId, Request $request) {
        $users = User::find($usersId);

        if (empty($users)) {
            $request->session()->flash('error', 'User not found');
            return response()->json([
                'status' => true,
                'message' => 'User not found'
            ]);
        }

        $users->delete();
        $request->session()->flash('success', 'User deleted successfully');

        return response()->json([
            'status' => true,
            'message' => 'User deleted successfully'
        ]);
    }
}
