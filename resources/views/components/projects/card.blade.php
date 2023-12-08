@props(['project','descLength'])

<div class="p-5 bg-gray-100 rounded" style="min-height: 200px;">
    <h3 class="text-xl py-4 border-l-4 border-blue-400 -ml-5 pl-4">
        <a href="{{ $project->path() }}">{{ $project->title }}</a>
    </h3>
    <div class="mt-5 text-gray-400" {{ $attributes }}>
        {{ \Str::limit($project->description, ($descLength ?? 100)) }}
    </div>
    @can('manage', $project)
    <footer class="mt-4">
        <form action="{{ $project->path() }}" method="POST" class="text-right" accept-charset="utf-8">
            @csrf
            @method('DELETE')
            <button type="submit" class="px-4 py-2 text-sm bg-red-600 text-white rounded">Delete</button>
        </form>
    </footer>
    @endcan
</div>