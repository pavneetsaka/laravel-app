<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Create Project
        </h2>
    </x-slot>

    <x-wrapper>
        <form class="m-auto py-8" method="POST" action="/projects" style="width:700px;">
            @csrf
            <x-projects.form />
        </form>
    </x-wrapper>
</x-app-layout>