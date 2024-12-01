<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 leading-tight flex items-center gap-2">
            <i class="fas fa-tachometer-alt text-green-500"></i>
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-100 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-lg rounded-lg overflow-hidden">
                <div class="p-8 text-gray-900">
                    <!-- Category Management -->
                    <h3 class="text-2xl font-bold text-gray-700 mb-4 flex items-center gap-2">
                        <i class="fas fa-folder text-yellow-500"></i> Manage Categories
                    </h3>

                    <!-- Add Category Form -->
                    <form action="{{ route('categories.store') }}" method="POST" class="mb-6 flex gap-3 items-center">
                        @csrf
                        <input type="text" name="name" placeholder="Category name"
                               class="border border-gray-300 p-3 rounded-lg flex-1 focus:ring-2 focus:ring-yellow-500 focus:outline-none" required>
                        <input type="color" name="color" class="border border-gray-300 p-3 rounded-lg w-16 h-12 cursor-pointer focus:ring-2 focus:ring-yellow-500" required>
                        <button type="submit" class="bg-green-500 hover:bg-green-600 text-white px-5 py-2 rounded-lg flex items-center gap-2 transition">
                            <i class="fas fa-plus-circle"></i> Add Category
                        </button>
                    </form>

                    <!-- Display Categories -->
                    <ul class="space-y-3">
                        @foreach(auth()->user()->categories as $category)
                            <li class="flex justify-between items-center p-4 bg-gray-50 border border-gray-200 rounded-lg hover:bg-gray-100 transition">
                                <div class="flex items-center gap-3">
                                    <div class="w-5 h-5 rounded-full" style="background-color: {{ $category->color }}"></div>
                                    <span class="font-medium text-gray-700">{{ $category->name }}</span>
                                </div>

                                <div class="flex items-center gap-3">
                                    <button onclick="toggleEditCategory('{{ $category->id }}')" 
                                            class="text-blue-500 hover:text-blue-600">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    
                                    <form action="{{ route('categories.destroy', $category->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-500 hover:text-red-600">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </div>

                                <!-- Edit Category Form (Hidden by default) -->
                                <form id="editCategory{{ $category->id }}" 
                                      action="{{ route('categories.update', $category->id) }}" 
                                      method="POST" 
                                      class="hidden mt-2 flex items-center gap-2">
                                    @csrf
                                    @method('PUT')
                                    <input type="text" name="name" value="{{ $category->name }}" 
                                           class="border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-yellow-500 focus:outline-none">
                                    <input type="color" name="color" value="{{ $category->color }}" 
                                           class="border border-gray-300 rounded-lg h-10 w-10 cursor-pointer focus:ring-2 focus:ring-yellow-500">
                                    <button type="submit" class="text-green-500 hover:text-green-600">
                                        <i class="fas fa-check"></i>
                                    </button>
                                    <button type="button" onclick="toggleEditCategory('{{ $category->id }}')" 
                                            class="text-red-500 hover:text-red-600">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </form>
                            </li>
                        @endforeach
                    </ul>

                    <!-- Display To-Do List -->
                    <h3 class="text-2xl font-bold text-gray-700 mb-4 mt-8 flex items-center gap-2">
                        <i class="fas fa-tasks text-blue-500"></i> Your To-Do List
                    </h3>

                    <!-- Add Todo Form -->
                    <form action="{{ route('todos.store') }}" method="POST" class="mb-6 flex gap-3 items-center">
                        @csrf
                        <input type="text" name="title" placeholder="Add new task..." 
                               class="border border-gray-300 p-3 rounded-lg flex-1 focus:ring-2 focus:ring-blue-500 focus:outline-none" required>
                        <select name="category_id" class="border border-gray-300 p-3 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">
                            <option value="">No Category</option>
                            @foreach(auth()->user()->categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                        <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-5 py-2 rounded-lg flex items-center gap-2 transition">
                            <i class="fas fa-plus"></i> Add Task
                        </button>
                    </form>

                    @if($todos->isEmpty())
                        <p class="text-gray-500 text-center py-4">No tasks found.</p>
                    @else
                        <ul class="space-y-3">
                            @foreach($todos as $todo)
                                <li class="p-4 bg-gray-50 border border-gray-200 rounded-lg hover:bg-gray-100 transition">
                                    <div class="flex items-center gap-4">
                                        <form action="{{ route('todos.update', $todo->id) }}" 
                                              method="POST" 
                                              class="flex-1 flex items-center gap-2">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit" name="is_completed" value="{{ $todo->is_completed ? '0' : '1' }}"
                                                    class="text-green-500 hover:text-green-600">
                                                <i class="fas {{ $todo->is_completed ? 'fa-check-circle' : 'fa-circle' }}"></i>
                                            </button>
                                            
                                            <input type="text" name="title" value="{{ $todo->title }}" 
                                                   class="border-0 bg-transparent flex-1 focus:outline-none focus:ring-2 focus:ring-blue-500 rounded-lg px-2 py-1"
                                                   onchange="this.form.submit()">
                                        </form>

                                        <div class="flex items-center gap-3">
                                            <button onclick="toggleEditTodo('{{ $todo->id }}')" 
                                                    class="text-blue-500 hover:text-blue-600">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            
                                            <form action="{{ route('todos.destroy', $todo->id) }}" 
                                                  method="POST" 
                                                  class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-500 hover:text-red-600">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </div>

                                    @if($todo->category)
                                        <div class="mt-2 flex items-center gap-2">
                                            <div class="w-3 h-3 rounded-full" style="background-color: {{ $todo->category->color }}"></div>
                                            <span class="text-sm text-gray-600">{{ $todo->category->name }}</span>
                                        </div>
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script>
        function toggleEditCategory(id) {
            const form = document.getElementById(`editCategory${id}`);
            form.classList.toggle('hidden');
        }

        function toggleEditTodo(id) {
            const todoItem = document.querySelector(`#todo${id}`);
            const editForm = document.querySelector(`#editTodo${id}`);
            todoItem.classList.toggle('hidden');
            editForm.classList.toggle('hidden');
        }

        document.addEventListener('DOMContentLoaded', () => {
            const inputs = document.querySelectorAll('input[type="text"]');
            inputs.forEach(input => {
                input.addEventListener('keypress', (e) => {
                    if (e.key === 'Enter') {
                        e.preventDefault();
                        const form = e.target.closest('form');
                        if (form) {
                            form.submit();
                        }
                    }
                });
            });
        });
    </script>
</x-app-layout>
