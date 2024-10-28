<x-layout>
    <main class="container max-w-xl mx-auto space-y-8 mt-8 px-2 md:px-0 min-h-full">
        <!-- resources/views/users/search_results.blade.php -->
        @if ($users->isEmpty())
            <p>No users found.</p>
        @else
            @foreach ($users as $user)
                <!-- Barta Card -->

                <article class="bg-white border-2 border-black rounded-lg shadow mx-auto max-w-96 px-4 py-5 sm:px-6">
                    <!-- user Card-->
                        <div class="flex items-center justify-between">
                        <!-- User Avatar -->
                        <div class="flex-shrink-0">
                            <!-- User Info -->
                            <div class="text-gray-900 flex flex-col min-w-0 flex-1">
                                <img class="h-32 w-32 rounded-full object-cover"
                                src="{{ asset('storage/' . $user->profile_picture) }}" alt="{{ $user->name }}">
                            </div>
                            <!-- /User Info -->
                        </div>
                            <div class="flex items-center space-x-3">
                                <div class="flex-shrink-0">
                                    <!-- User Info -->
                                    <div class="text-gray-900 flex flex-col min-w-0 flex-1">
                                        <a href="#" class="hover:underline font-semibold line-clamp-1">
                                            {{ $user->username }}
                                        </a>

                                        <a href="profile" class="hover:underline text-sm text-gray-500 line-clamp-1">
                                            {{ $user->name }}
                                        </a>
                                        <a href="profile" class="hover:underline text-sm text-gray-500 line-clamp-1">
                                            {{ $user->email }}
                                        </a>
                                    </div>
                                    <!-- /User Info -->
                                </div>
                            </div>
                </article>

                <!-- /Barta Card -->
            @endforeach

        @endif
    </main>
</x-layout>
