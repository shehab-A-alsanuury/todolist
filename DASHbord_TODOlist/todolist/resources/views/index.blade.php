<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>To-Do List</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#6366F1',
                        accent: '#818CF8',
                    }
                }
            }
        }
    </script>
    <style>
        .todo-item {
            transition: all 0.2s ease;
        }
        .todo-item:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }
        .category-card {
            transition: transform 0.3s ease;
        }
        .category-card:hover {
            transform: scale(1.02);
        }
    </style>
</head>

<body class="bg-gray-50 min-h-screen p-4 md:p-8">
    <!-- Navigation -->
    <nav class="w-full max-w-7xl mx-auto bg-white p-4 rounded-lg shadow-lg mb-8 flex justify-between items-center">
        <div class="flex items-center space-x-4">
        <a href="/dashboard" class="px-6 py-3 bg-gradient-to-r from-indigo-500 to-purple-600 text-white font-medium rounded-lg shadow-lg transform hover:scale-105 hover:shadow-2xl transition-all duration-300 flex items-center space-x-3">
    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" viewBox="0 0 20 20" fill="currentColor">
        <path d="M4 3a2 2 0 00-2 2v5h16V5a2 2 0 00-2-2H4z" />
        <path fill-rule="evenodd" d="M18 12H2v3a2 2 0 002 2h12a2 2 0 002-2v-3z" clip-rule="evenodd" />
    </svg>
    <span>TOdoLIST</span>
</a>

        </div>
        <div class="flex items-center space-x-4">
    <!-- User Name with Icon -->
    <span class="flex items-center text-gray-600 dark:text-gray-300 space-x-2">
        <i class="fas fa-user"></i> <!-- User icon -->
        <span>{{ auth()->user()->name }}</span>
    </span>

    <!-- Logout Button with Icon -->
    <form action="{{ route('logout') }}" method="POST" class="inline">
        @csrf
        <button type="submit" class="flex items-center px-4 py-2 bg-gray-200 dark:bg-gray-700 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors text-gray-700 dark:text-gray-200 space-x-2">
            <i class="fas fa-sign-out-alt"></i> <!-- Logout icon -->
            <span>Logout</span>
        </button>
    </form>
</div>

    </nav>

    <main class="w-full max-w-7xl mx-auto space-y-8">
        <!-- Header -->
        <div class="bg-white p-6 rounded-lg shadow-lg text-center">
            <h1 class="text-4xl font-bold text-primary mb-4 flex items-center justify-center space-x-2">
                <i class="fas fa-list"></i>
                <span>To-Do List</span>
            </h1>
            <p class="text-lg text-gray-600">Organize your tasks with ease!</p>
        </div>

        <!-- Add Category Form -->
        <form action="/categories/store" method="POST" class="bg-white p-6 rounded-lg shadow-lg">
            @csrf
            <div class="flex gap-4 mb-4">
                <input type="text" 
                       name="name" 
                       placeholder="Category Name" 
                       class="flex-1 px-4 py-3 border-2 border-gray-200 rounded-lg focus:border-primary focus:ring-4 focus:ring-primary/20 transition-all outline-none"
                       required>
                <input type="color" 
                       name="color" 
                       class="w-16 h-12 p-1 border-2 border-gray-200 rounded-lg cursor-pointer">
                <button type="submit" 
                        class="px-6 py-3 bg-primary text-white font-medium rounded-lg hover:bg-indigo-600 shadow-md transition-all">
                    Add Category
                </button>
            </div>
        </form>

        <!-- Todo Form -->
        <form id="todo-form" action="/todos/store" method="POST" class="bg-white p-6 rounded-lg shadow-lg">
            @csrf
            <div class="flex gap-4 mb-4">
                <select name="category_id" 
                        class="px-4 py-3 border-2 border-gray-200 rounded-lg focus:border-primary focus:ring-4 focus:ring-primary/20 transition-all outline-none"
                        required>
                    <option value="">Select Category</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
                <input type="text" 
                       name="title" 
                       placeholder="To-Do Title" 
                       class="flex-1 px-4 py-3 border-2 border-gray-200 rounded-lg focus:border-primary focus:ring-4 focus:ring-primary/20 transition-all outline-none"
                       required>
                <button type="submit" 
                        class="px-6 py-3 bg-primary text-white font-medium rounded-lg hover:bg-indigo-600 shadow-md transition-all flex items-center space-x-2">
                    <i class="fas fa-plus"></i>
                    <span>Add</span>
                </button>
            </div>
            <textarea name="description" 
                      placeholder="Description" 
                      class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:border-primary focus:ring-4 focus:ring-primary/20 transition-all outline-none resize-none h-24"></textarea>
        </form>

        <!-- Tasks by Category -->
        <section class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ($categories as $category)
                <div class="category-card bg-white p-6 rounded-lg shadow-lg">
                    <h2 class="text-xl font-bold mb-4 flex items-center gap-2" style="color: {{ $category->color }}">
                        <span class="w-4 h-4 rounded-full" style="background-color: {{ $category->color }}"></span>
                        {{ $category->name }}
                    </h2>
                    <ul class="space-y-4">
                        @foreach ($category->todos as $todo)
                            <li class="todo-item bg-white p-4 rounded-lg border border-gray-100 flex justify-between items-center transition-all" 
                                style="border-left: 4px solid {{ $category->color }}">
                                <form action="/todos/toggle-complete/{{ $todo->id }}" method="POST" class="flex items-center flex-1">
                                    @csrf
                                    @method('PATCH')
                                    <input type="checkbox" 
                                           name="is_completed" 
                                           {{ $todo->is_completed ? 'checked' : '' }} 
                                           class="w-5 h-5 mr-4 accent-primary cursor-pointer"
                                           onchange="this.form.submit()">
                                    <span class="{{ $todo->is_completed ? 'line-through text-gray-400' : 'text-gray-900 font-semibold' }}">
                                        {{ $todo->title }}
                                        @if($todo->description)
                                            <p class="text-sm text-gray-500 font-normal">{{ $todo->description }}</p>
                                        @endif
                                    </span>
                                </form>
                                <form action="/todos/delete/{{ $todo->id }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-600 transition-colors opacity-0 group-hover:opacity-100">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endforeach
        </section>
    </main>
</body>
</html>