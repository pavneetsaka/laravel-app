<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight flex items-center">
            <a href="{{ $project->path() }}" class="text-sm" title="">{{ $project->title }}</a>
            <span>&nbsp;/&nbsp;</span>Edit Project Page
        </h2>
        <a href="/projects" class="btn bg-gray-200 py-1 px-4 rounded text-sm" title="">My Projects</a>
    </x-slot>

    <x-wrapper>
        <form class="m-auto py-8" method="POST" action="{{ $project->path() }}" style="width:700px;">
            @csrf
            @method('PATCH')
            <x-projects.form :project=$project btn-text="Update" />
        </form>
    </x-wrapper>
</x-app-layout>