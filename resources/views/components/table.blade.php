<div class="relative overflow-x-auto shadow-md sm:rounded-lg">
    <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
            <tr>
                <th scope="col" class="px-6 py-3">
                    Title
                </th>
                <th scope="col" class="px-6 py-3">
                    <div class="flex items-center">
                        Content                        
                    </div>
                </th>
                <th scope="col" class="px-6 py-3">
                    <div class="flex items-center">
                        Image
                    </div>
                </th>
                <th scope="col" class="px-6 py-3">
                    <span class="sr-only">actions</span>
                </th>
            </tr>
        </thead>
        <tbody>
            @foreach ($posts as $post)
            <tr>
                <td>{{ $post->title}}</td>
                <td>{{ $post->content}}</td>
                <td class="flex justify-center items-center">                  
                 <img src="{{ $post->image }}" alt="{{ $post->title }}" class="object-fill h-9 w-9 rounded-full" />
                </td>
                <td class="px-6 py-4 text-right">
                    <a href="#" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Edit</a>
                </td>
            </tr>
            @endforeach
            
        </tbody>
    </table>
</div>