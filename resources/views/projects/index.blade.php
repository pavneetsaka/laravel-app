<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Projects
        </h2>
        <a href="/projects/create" class="btn bg-gray-200 py-1 px-4 rounded text-sm" title="">Create</a>
    </x-slot>

    <x-wrapper>
        <div class="p-6 text-gray-900">
            <div class="lg:flex lg:flex-wrap -mx-3">
                @forelse($projects as $project)
                    <div class="lg:w-1/3 px-3 pb-6">
                        <x-projects.card :project="$project" />
                    </div>
                @empty
                    <div>
                        No projects found
                    </div>
                @endforelse
            </div>
        </div>
    </x-wrapper>
</x-app-layout>