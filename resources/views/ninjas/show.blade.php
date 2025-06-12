<x-layout>
    <h2>{{ $ninja->name }}</h2>
    <div class="bg-gray-200 p-4 rounded">
        <p><strong>Skill level:</strong> {{ $ninja->skill }}</p>
        <p><strong>Abount me:</strong></p>
        <p>{{ $ninja->bio }}</p>
    </div>
    
    <div class="border-2 border-dashed bg-white px-4 pb-4 my-4 rounded">
        <h3>Dojo Information</h3>
        <p><strong>Dojo Name:</strong> {{ $ninja->dojo->name }}</p>
        <p><strong>Location:</strong> {{ $ninja->dojo->location }}</p>
        <p><strong>About the Dojo:</strong></p>
        <p>{{ $ninja->dojo->description }}</p>
    </div>
    <form action="{{ route('ninjas.destroy', $ninja->id) }}" method="POST">
        @csrf
        @method('DELETE')
        <button class="btn my-4" type="submit">Delete Ninja</button>
    </form>
</x-layout>    