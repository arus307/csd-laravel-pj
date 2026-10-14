<?php

namespace App\Http\Controllers;

use App\Models\Todo;
use Illuminate\Http\Request;

class TodoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $status = $request->query('status', 'all');

        $query = Todo::query();

        if ($status === 'pending') {
            $query->where('is_completed', false);
        } elseif ($status === 'completed') {
            $query->where('is_completed', true);
        }

        $todos = $query->orderBy('is_completed', 'asc')
            ->orderBy('due_date', 'asc')
            ->orderBy('created_at', 'desc')
            ->paginate(10)
            ->withQueryString();

        $counts = [
            'all' => Todo::count(),
            'pending' => Todo::where('is_completed', false)->count(),
            'completed' => Todo::where('is_completed', true)->count(),
        ];

        return view('todos.index', compact('todos', 'status', 'counts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return redirect()->route('todos.index');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'due_date' => 'nullable|date',
        ]);

        Todo::create($validated);

        return redirect()->route('todos.index')->with('success', 'Todoを作成しました。');
    }

    /**
     * Display the specified resource.
     */
    public function show(Todo $todo)
    {
        return redirect()->route('todos.edit', $todo);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Todo $todo)
    {
        return view('todos.edit', compact('todo'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Todo $todo)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'due_date' => 'nullable|date',
            'is_completed' => 'nullable|boolean',
        ]);

        $validated['is_completed'] = $request->has('is_completed');

        $todo->update($validated);

        return redirect()->route('todos.index')->with('success', 'Todoを更新しました。');
    }

    /**
     * Toggle the completion status of the resource.
     */
    public function toggle(Todo $todo)
    {
        $todo->update([
            'is_completed' => ! $todo->is_completed,
        ]);

        $message = $todo->is_completed ? 'Todoを完了にしました。' : 'Todoを未完了に戻しました。';

        return back()->with('success', $message);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Todo $todo)
    {
        $todo->delete();

        return redirect()->route('todos.index')->with('success', 'Todoを削除しました。');
    }
}
