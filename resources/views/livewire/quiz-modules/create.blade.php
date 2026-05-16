<div class="p-6">
    <h1 class="text-2xl font-bold mb-6">
        Create Quiz Module
    </h1>

    <form wire:submit="save" class="space-y-4">

        <div>
            <label class="block mb-1 font-medium">
                Title
            </label>

            <input
                type="text"
                wire:model="title"
                class="w-full rounded-lg border-gray-300"
            >

            @error('title')
                <p class="text-red-500 text-sm mt-1">
                    {{ $message }}
                </p>
            @enderror
        </div>

        <div>
            <label class="block mb-1 font-medium">
                Description
            </label>

            <textarea
                wire:model="description"
                rows="4"
                class="w-full rounded-lg border-gray-300"
            ></textarea>
        </div>

        <div>
            <label class="block mb-1 font-medium">
                Minimum Questions
            </label>

            <input
                type="number"
                wire:model="minimum_questions"
                class="w-full rounded-lg border-gray-300"
            >
        </div>

        <div class="flex items-center gap-2">
            <input
                type="checkbox"
                wire:model="is_active"
            >

            <label>
                Active
            </label>
        </div>

        <button
            type="submit"
            class="bg-blue-600 text-white px-4 py-2 rounded-lg"
        >
            Save Module
        </button>

    </form>
</div>