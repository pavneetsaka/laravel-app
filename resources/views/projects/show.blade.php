<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight flex items-center">
            <a href="/projects" class="text-sm" title="">My Projects</a>
            <span>&nbsp;/&nbsp;</span>
            {{ $project->title }}
        </h2>
        <a href="{{ $project->path().'/edit' }}" class="btn bg-gray-200 py-1 px-4 rounded text-sm" title="">Edit Project</a>
    </x-slot>

    <x-wrapper>
        <div class="p-6 text-gray-900">
            <div class="flex -mx-3">
                <div class="lg:w-3/4 px-3 mb-6">
                    <div class="mb-6">
                        <h2 class="text-lg text-gray mb-3">Tasks</h2>
                        @foreach($project->tasks as $task)
                            <div class="mb-3">
                                <form action="{{ $task->path() }}" method="POST" accept-charset="utf-8">
                                    @csrf
                                    @method('PATCH')
                                    <div class="flex justify-end items-center">
                                        <input type="text" name="body" value="{{ $task->body }}" class="w-full border-0 {{ $task->completed ? 'text-gray-400' : '' }}">
                                        <input type="checkbox" name="completed" {{ $task->completed ? 'checked':'' }} onchange="this.form.submit()">
                                    </div>
                                </form>
                            </div>
                        @endforeach
                        <form action="{{ $project->path().'/tasks' }}" method="POST" accept-charset="utf-8">
                            @csrf
                            <input type="text" name="body" class="w-full border-0" placeholder="Add new task">
                            <x-input-error class="mt-2" :messages="$errors->get('body')" />
                        </form>
                    </div>
                    <div>
                        <h2 class="text-lg text-gray mb-3"> General Notes</h2>
                        <form action="{{ $project->path() }}" method="post" accept-charset="utf-8">
                            @csrf
                            @method('PATCH')
                            <x-textarea class="mb-4" name="notes" placeholder="Anything special that you want to make a note of?" rows="5" cols="15">{{ $project->notes }}</x-textarea>
                            <x-input-error class="mt-2" :messages="$errors->get('notes')" />
                            <button type="submit" class="btn bg-gray-200 py-1 px-4 rounded text-sm">Save</button>
                        </form>
                    </div>
                </div>
                <div class="lg:w-1/4 px-3">
                    <x-projects.card :project="$project" :desc-length="255" />

                    <div class="mt-6">
                        <h2 class="text-lg text-gray mb-3">Activities</h2>
                        <ul class="text-sm">
                            @foreach($project->activity as $activity)
                                <li class="{{ $loop->last ? '' : 'mb-3' }}">
                                    <x-dynamic-component component="{{ 'activities.'.$activity->description }}" :activity="$activity" />
                                    <small class="text-gray-400">{{ $activity->created_at->diffForHumans(null, true) }}</small>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                    @can('manage', $project)
                    <div class="mt-6">
                        <h2 class="text-lg text-gray mb-3">Invite a User</h2>
                        <div class="mt-2">
                            <form action="{{ $project->path().'/invitation' }}" method="POST">
                                @csrf
                                <div class="flex items-center justify-between mb-4 text-sm">
                                    <input class="shadow appearance-none border rounded py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline lg:w-2/3" type="text" name="email" value="" placeholder="Email Address">
                                    <button class="btn bg-gray-200 rounded py-2 px-4" type="submit">Invite</button>
                                </div>
                                <x-input-error class="mt-2" :messages="$errors->get('email')" />
                                @if(Session::has('success'))
                                    <span class="text-sm text-green-600 space-y-1">{{ Session::get('success') }}</span>
                                @endif
                            </form>
                        </div>
                    </div>
                    @endcan
                </div>
            </div>
        </div>
    </x-wrapper>
</x-app-layout>