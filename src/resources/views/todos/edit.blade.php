@extends('layouts.app')

@section('title', 'タスク編集')

@section('content')
<div class="max-w-xl mx-auto">
    <div class="mb-4">
        <a href="{{ route('todos.index') }}" class="inline-flex items-center text-sm font-medium text-slate-500 hover:text-indigo-600 transition">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
            タスク一覧へ戻る
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
        <h2 class="text-lg font-bold text-slate-800 mb-6 flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
            </svg>
            タスクを編集
        </h2>

        <form action="{{ route('todos.update', $todo) }}" method="POST" class="space-y-5">
            @csrf
            @method('PUT')

            <div>
                <label for="title" class="block text-xs font-semibold text-slate-600 mb-1">
                    タスク名 <span class="text-rose-500">*</span>
                </label>
                <input
                    type="text"
                    name="title"
                    id="title"
                    value="{{ old('title', $todo->title) }}"
                    required
                    class="w-full px-3.5 py-2.5 rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 text-sm transition"
                >
            </div>

            <div>
                <label for="due_date" class="block text-xs font-semibold text-slate-600 mb-1">
                    期限日（任意）
                </label>
                <input
                    type="date"
                    name="due_date"
                    id="due_date"
                    value="{{ old('due_date', $todo->due_date ? $todo->due_date->format('Y-m-d') : '') }}"
                    class="w-full px-3.5 py-2 rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 text-sm transition"
                >
            </div>

            <div>
                <label for="description" class="block text-xs font-semibold text-slate-600 mb-1">
                    メモ・詳細（任意）
                </label>
                <textarea
                    name="description"
                    id="description"
                    rows="4"
                    placeholder="タスクの詳細や備考を入力"
                    class="w-full px-3.5 py-2 rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 text-sm transition"
                >{{ old('description', $todo->description) }}</textarea>
            </div>

            <div class="pt-2">
                <label class="inline-flex items-center cursor-pointer">
                    <input
                        type="checkbox"
                        name="is_completed"
                        value="1"
                        {{ old('is_completed', $todo->is_completed) ? 'checked' : '' }}
                        class="h-4 w-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500 transition"
                    >
                    <span class="ml-2 text-sm font-medium text-slate-700">完了済みにする</span>
                </label>
            </div>

            <div class="flex items-center justify-end space-x-3 pt-4 border-t border-slate-100">
                <a
                    href="{{ route('todos.index') }}"
                    class="px-4 py-2 rounded-lg border border-slate-300 text-slate-600 hover:bg-slate-50 text-sm font-medium transition"
                >
                    キャンセル
                </a>
                <button
                    type="submit"
                    class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium text-sm px-5 py-2 rounded-lg transition shadow-sm hover:shadow flex items-center"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    変更を保存
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
