<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Posts') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                {{-- solo las partes del sitio que queremos  que sean reactivas se crearan con componentes de livewire --}}
                @livewire('posts.table-posts', ['title' => 'Listado'])             
            </div>
        </div>
    </div>
</x-app-layout>
