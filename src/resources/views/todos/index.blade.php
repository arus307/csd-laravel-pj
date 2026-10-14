@extends('layouts.app')

@section('title', 'タスク一覧')

@section('content')
<div class="space-y-6">
    <!-- 新規作成フォームカード -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
        <h2 class="text-lg font-bold text-slate-800 mb-4 flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            新しいタスクを追加
        </h2>
        <form action="{{ route('todos.store') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label for="title" class="block text-xs font-semibold text-slate-600 mb-1">
                    タスク名 <span class="text-rose-500">*</span>
                </label>
                <input
                    type="text"
                    name="title"
                    id="title"
                    value="{{ old('title') }}"
                    placeholder="例: Laravelのマイグレーションを実行する"
                    required
                    class="w-full px-3.5 py-2.5 rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 text-sm transition"
                >
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="due_date" class="block text-xs font-semibold text-slate-600 mb-1">
                        期限日（任意）
                    </label>
                    <input
                        type="date"
                        name="due_date"
                        id="due_date"
                        value="{{ old('due_date') }}"
                        class="w-full px-3.5 py-2 rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 text-sm transition"
                    >
                </div>

                <div>
                    <label for="description" class="block text-xs font-semibold text-slate-600 mb-1">
                        メモ・詳細（任意）
                    </label>
                    <input
                        type="text"
                        name="description"
                        id="description"
                        value="{{ old('description') }}"
                        placeholder="詳細や備考があれば入力"
                        class="w-full px-3.5 py-2 rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 text-sm transition"
                    >
                </div>
            </div>

            <div class="flex justify-end pt-2">
                <button
                    type="submit"
                    class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium text-sm px-5 py-2.5 rounded-lg transition shadow-sm hover:shadow flex items-center"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    タスクを登録
                </button>
            </div>
        </form>
    </div>

    <!-- フィルタタブ -->
    <div class="flex items-center space-x-2 border-b border-slate-200 pb-2">
        <a href="{{ route('todos.index', ['status' => 'all']) }}"
           class="px-4 py-2 text-sm font-medium rounded-lg transition {{ $status === 'all' ? 'bg-indigo-600 text-white shadow-sm' : 'text-slate-600 hover:bg-slate-200/60' }}">
            すべて
            <span class="ml-1.5 px-2 py-0.5 text-xs rounded-full {{ $status === 'all' ? 'bg-indigo-800 text-indigo-100' : 'bg-slate-200 text-slate-700' }}">
                {{ $counts['all'] }}
            </span>
        </a>
        <a href="{{ route('todos.index', ['status' => 'pending']) }}"
           class="px-4 py-2 text-sm font-medium rounded-lg transition {{ $status === 'pending' ? 'bg-indigo-600 text-white shadow-sm' : 'text-slate-600 hover:bg-slate-200/60' }}">
            未完了
            <span class="ml-1.5 px-2 py-0.5 text-xs rounded-full {{ $status === 'pending' ? 'bg-indigo-800 text-indigo-100' : 'bg-slate-200 text-slate-700' }}">
                {{ $counts['pending'] }}
            </span>
        </a>
        <a href="{{ route('todos.index', ['status' => 'completed']) }}"
           class="px-4 py-2 text-sm font-medium rounded-lg transition {{ $status === 'completed' ? 'bg-indigo-600 text-white shadow-sm' : 'text-slate-600 hover:bg-slate-200/60' }}">
            完了
            <span class="ml-1.5 px-2 py-0.5 text-xs rounded-full {{ $status === 'completed' ? 'bg-indigo-800 text-indigo-100' : 'bg-slate-200 text-slate-700' }}">
                {{ $counts['completed'] }}
            </span>
        </a>
    </div>

    <!-- Todo一覧リスト -->
    <div class="space-y-3">
        @forelse ($todos as $todo)
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-4 transition hover:border-slate-300 {{ $todo->is_completed ? 'bg-slate-50/70 border-dashed opacity-75' : '' }}">
                <div class="flex items-start justify-between gap-3">
                    <!-- 完了トグルボタン & タイトル・詳細 -->
                    <div class="flex items-start space-x-3 flex-grow min-w-0">
                        <form action="{{ route('todos.toggle', $todo) }}" method="POST" class="mt-0.5">
                            @csrf
                            @method('PATCH')
                            <button
                                type="submit"
                                title="{{ $todo->is_completed ? '未完了に戻す' : '完了にする' }}"
                                class="h-6 w-6 rounded-md border flex items-center justify-center transition {{ $todo->is_completed ? 'bg-emerald-500 border-emerald-500 text-white' : 'border-slate-300 hover:border-indigo-500 bg-white' }}"
                            >
                                @if ($todo->is_completed)
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                    </svg>
                                @endif
                            </button>
                        </form>

                        <div class="flex-grow min-w-0">
                            <h3 class="text-base font-semibold leading-snug break-words {{ $todo->is_completed ? 'line-through text-slate-400' : 'text-slate-800' }}">
                                {{ $todo->title }}
                            </h3>

                            @if ($todo->description)
                                <p class="text-xs text-slate-500 mt-1 whitespace-pre-line break-words {{ $todo->is_completed ? 'text-slate-400' : '' }}">
                                    {{ $todo->description }}
                                </p>
                            @endif

                            <div class="flex flex-wrap items-center gap-2 mt-2 text-xs text-slate-500">
                                @if ($todo->due_date)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded font-medium {{ $todo->due_date->isPast() && !$todo->is_completed ? 'bg-rose-100 text-rose-700 font-semibold' : 'bg-slate-100 text-slate-600' }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        期限: {{ $todo->due_date->format('Y-m-d') }}
                                        @if ($todo->due_date->isPast() && !$todo->is_completed)
                                            （期限超過）
                                        @endif
                                    </span>
                                @endif

                                <span>作成: {{ $todo->created_at->format('Y/m/d H:i') }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- 操作アクション（編集・削除） -->
                    <div class="flex items-center space-x-1.5 flex-shrink-0">
                        <a
                            href="{{ route('todos.edit', $todo) }}"
                            class="p-2 text-slate-400 hover:text-indigo-600 hover:bg-slate-100 rounded-lg transition"
                            title="編集"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                        </a>

                        <form action="{{ route('todos.destroy', $todo) }}" method="POST" onsubmit="return confirm('このタスクを削除しますか？');">
                            @csrf
                            @method('DELETE')
                            <button
                                type="submit"
                                class="p-2 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition"
                                title="削除"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="bg-white rounded-xl border border-slate-200 border-dashed p-12 text-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-12 w-12 text-slate-300 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                </svg>
                <p class="text-sm font-medium text-slate-600">タスクがありません</p>
                <p class="text-xs text-slate-400 mt-1">上のフォームから新しいタスクを追加してください。</p>
            </div>
        @endforelse

        <!-- ページネーション -->
        <div class="pt-4">
            {{ $todos->links() }}
        </div>
    </div>
</div>
@endsection
