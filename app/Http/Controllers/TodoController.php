<?php

namespace App\Http\Controllers;

use App\Models\Todo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TodoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Get the authenticated user's ID
        $userId = Auth::user()->id;

        // Fetch todos belonging to the logged-in user
        $todos = Todo::where('user_id', $userId)->get();

        // Pass the todos to the view
        return view('todo.list', ['todos' => $todos]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('todo.add'); // Show the "Add Todo" form
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate incoming request data
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:pending,completed',
        ]);

        // Get the authenticated user's ID
        $userId = Auth::user()->id;

        // Store the new todo
        $todo = Todo::create([
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'status' => $request->input('status'),
            'user_id' => $userId,
        ]);

        // Redirect with success message
        return redirect()->route('todo.index')->with('success', 'Todo added successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        // Get the authenticated user's ID
        $userId = Auth::user()->id;

        // Find the todo by ID for the logged-in user
        $todo = Todo::where('user_id', $userId)->find($id);

        // If not found, redirect with error
        if (!$todo) {
            return redirect()->route('todo.index')->with('error', 'Todo not found!');
        }

        // Return the view with todo details
        return view('todo.view', ['todo' => $todo]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        // Get the authenticated user's ID
        $userId = Auth::user()->id;

        // Find the todo by ID for the logged-in user
        $todo = Todo::where('user_id', $userId)->find($id);

        // If not found, redirect with error
        if (!$todo) {
            return redirect()->route('todo.index')->with('error', 'Todo not found!');
        }

        // Return the edit form with the todo data
        return view('todo.edit', ['todo' => $todo]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // Validate the incoming data
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:pending,completed',
        ]);

        // Get the authenticated user's ID
        $userId = Auth::user()->id;

        // Find the todo by ID for the logged-in user
        $todo = Todo::where('user_id', $userId)->find($id);

        // If not found, redirect with error
        if (!$todo) {
            return redirect()->route('todo.index')->with('error', 'Todo not found!');
        }

        // Update the todo with the new data
        $todo->update([
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'status' => $request->input('status'),
        ]);

        // Redirect back to the todo list with a success message
        return redirect()->route('todo.index')->with('success', 'Todo updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        // Get the authenticated user's ID
        $userId = Auth::user()->id;

        // Find the todo by ID for the logged-in user
        $todo = Todo::where('user_id', $userId)->find($id);

        // If not found, redirect with error
        if (!$todo) {
            return redirect()->route('todo.index')->with('error', 'Todo not found!');
        }

        // Delete the todo
        $todo->delete();

        // Redirect back to the todo list with a success message
        return redirect()->route('todo.index')->with('success', 'Todo deleted successfully!');
    }
}
