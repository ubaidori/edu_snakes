<div class="p-6">

    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold">
            Quiz Modules
        </h1>

        <a
            href="{{ route('quiz-modules.create') }}"
            class="bg-blue-600 text-white px-4 py-2 rounded-lg"
        >
            Create Module
        </a>
    </div>

    <div class="bg-white rounded-lg shadow p-4">
        @forelse ($quizModules as $module)

            <div class="border-b py-3">
                <h2 class="font-semibold text-lg">
                    {{ $module->title }}
                </h2>

                <p class="text-sm text-gray-600">
                    Minimum Questions:
                    {{ $module->minimum_questions }}
                </p>
            </div>

        @empty

            <p>No quiz modules yet.</p>

        @endforelse
    </div>

</div>