@props([
    'btnText' => 'Submit',
    'project' => new \App\Models\Project
])

<div class="mb-4">
    <label class="block mb-2" for="title">Title</label>
    <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" type="text" name="title" value="{{ $project->title }}" placeholder="Title">
    <x-input-error class="mt-2" :messages="$errors->get('title')" />
</div>
<div class="mb-4">
    <label class="block mb-2" for="description">Description</label>
    <x-textarea name="description" rows="5" cols="15" placeholder="Description">{{ $project->description }}</x-textarea>
    <x-input-error class="mt-2" :messages="$errors->get('description')" />
</div>
<div>
    <button class="btn bg-gray-200 rounded py-2 px-4 text-sm" type="submit">{{ $btnText }}</button>
</div>