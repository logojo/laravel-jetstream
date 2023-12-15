<div>
    <h1 class="px-4 py-2 mb-4">{{ $titulo }}</h1> 
    {{-- con la directiva wire:model vinculas la variable definida la funcion  al input --}}
    <div class="px-6 py-4">
        {{-- input que viene de los componentes de jetstream --}}
        <x-jet-input 
            type="text" 
            class="w-full mb-5"
            placeholder="Buscar post..."
            wire:model="search" 
        />

      <div class="flex justify-between items-center">
        <div class="mr-4">
            <span>Mostrar</span>
            <select name="" class="form-control" wire:model="show">
                <option value="10">10</option>
                <option value="25">25</option>
                <option value="50">50</option>
                <option value="100">100</option>
            </select>
            <span>Entradas</span>
        </div>

        @livewire('posts.create-post')
        
      </div>

    </div>   

    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
    
        @if ( $posts->count() )
            <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-6 py-3 cursor-pointer flex" wire:click="order('id')">
                            <div class="flex justify-between items-center">
                                <span>#</span>
                                @if ($sort == 'id')
                                    @if ($direction == 'asc')
                                        <i class="fa-solid fa-sort-alpha-up ml-2"></i>
                                    @else
                                        <i class="fa-solid fa-sort-alpha-down ml-2"></i>
                                    @endif 
                                @else  
                                    <i class="fa-solid fa-sort ml-2"></i>                                   
                                @endif   
                            </div>
                        </th>
                        <th scope="col" class="px-6 py-3 cursor-pointer " wire:click="order('title')">
                            <div class="flex justify-between items-center">
                                <span>Title</span>
                                @if ($sort == 'title')                                   
                                    @if ($direction == 'asc')
                                     <i class="fa-solid fa-sort-alpha-up"></i>
                                    @else
                                        <i class="fa-solid fa-sort-alpha-down"></i>
                                    @endif 
                                @else  
                                 <i class="fa-solid fa-sort"></i>                                   
                                @endif                                
                            </div>
                        </th>
                        <th scope="col" class="px-6 py-3 cursor-pointer" wire:click="order('content')">
                            <div class="flex justify-between items-center">
                                <span>Content</span>
                                @if ($sort == 'content')
                                    @if ($direction == 'asc')
                                        <i class="fa-solid fa-sort-alpha-up"></i>
                                    @else
                                        <i class="fa-solid fa-sort-alpha-down"></i>
                                    @endif 
                                @else  
                                    <i class="fa-solid fa-sort"></i>                                   
                                @endif                      
                            </div>
                        </th>
                        <th scope="col" class="px-6 py-3 cursor-pointer">
                            <div class="flex items-center">
                                Image
                            </div>
                        </th>
                        <th scope="col" class="px-6 py-3">
                            <span class="sr-only">actions</span>
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach ($posts as $item)
                        <tr class="text-xs">
                            <td class="pl-5">{{ $item->id}}</td>
                            <td>{{ $item->title}}</td>
                            {{-- {!! evita que se imprima elementos html --}}
                            <td>{!! $item->content !!}</td>
                            <td class="flex justify-center items-center"> 
                                @if(str_contains( $item->image, 'https'))  
                                    <img src="{{ $item->image }}" alt="{{ $item->title }}" class="object-fill h-9 w-9 rounded-full" />        
                                @else
                                <img src="{{ Storage::url( $item->image ) }}" alt="{{ $item->title }}" class="object-fill h-9 w-9 rounded-full" />
                                @endif
                            </td>
                            <td>                         
                                <i class="fa-solid fa-pen fa-lg text-blue-500 cursor-pointer" wire:click="edit({{$item}})"></i>
                                <i class="fa-solid fa-trash fa-lg text-red-500 cursor-pointer" wire:click="$emit('deletePost', {{ $item->id }})"></i>
                            </td>
                        </tr>
                    @endforeach
                    
                </tbody>
                <tfoot class="pb-5">
                    <tr>
                        <td colspan="5"></td>
                    </tr>
                </tfoot>
            </table>
        @else
            <div class="text-red-500 text-center p-4">No existen registros con los criterios de busqueda</div>
        @endif

        {{-- para saber si tiene mas de 1 pagina --}}
        @if($posts->hasPages())
            <div class="px-6 py-3">
                {{ $posts->links() }}
            </div>
        @endif
    </div>

    <x-jet-dialog-modal wire:model="open_edit">
        <x-slot name="title">
            Editar post
        </x-slot>
    
        <x-slot name="content">

             {{-- div que se muestra mientras se carga la imagen --}}    
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" wire:loading wire:target='image'>
                <strong class="font-bold">!Imagen cargando¡</strong>
                <span class="block sm:inline">Espere un momento hasta que la imagen se halla procesado</span>
                <span class="absolute top-0 bottom-0 right-0 px-4 py-3">
                <i class="fa-solid fa-xmark"></i>
                </span>
            </div>

            @if( $image )
             <div class="w-full flex justify-center">
                <img src="{{ $image->temporaryUrl() }}" class="object-fill h-24 w-24 rounded-full" />
             </div>  
            @elseif( $post->image )         
              <div class="w-full flex justify-center">
                @if(str_contains( $post->image, 'https'))  
                    <img src="{{ $post->image }}" alt="{{ $post->title }}" class="object-fill h-24 w-24 rounded-full" />        
                @else
                    <img src="{{ Storage::url( $post->image ) }}" alt="{{ $post->title }}" class="object-fill h-24 w-24 rounded-full" />
                @endif
             </div>         
            @endif
    
            {{-- wire:model.defer permite vincular al input pero evita que se recargue el componente cada vez que se teclee --}}
            {{-- para usar la funcion updated de la clase debemos usar la propiedad wire:model sin el defer --}}
            <div class="mb-4">
                <x-jet-label value="Title"/>
                <x-jet-input
                    type="text"
                    class="w-full"
                    wire:model="post.title"
                />    
                <x-jet-input-error for='post.title' />     
            </div>
     
            <div class="mb-4">
                <x-jet-label value="Content"/>
                <textarea class="form-control w-full" rows="6"   wire:model="post.content"></textarea>          
                <x-jet-input-error for='post.content' />  
            </div>
    
            <div class="mb-4">
                <x-jet-label value="Imagen"/>
                <x-jet-input
                    type="file"
                    class="w-full"
                    wire:model='image'
                    id="{{ $identificador }}"
                />
                <x-jet-input-error for='image' />  
            </div>
        </x-slot>
    
        <x-slot name="footer">
            <x-jet-secondary-button wire:click="$set('open_edit', false)">
                <i class="fa-solid fa-xmark mr-2"></i>
                cancel
            </x-jet-secondary-button>
    
            {{-- 
                wire:loading me permite mostrar elementos mientras se esta realizando la peticion
                wire:target me permite indicar que metodo utilizara wire:loading para mostrar el elemento 
                wire:loading por defecto le asigna la clase css display:inline-block pero se puede modificar wire:loading.flex
            --}}
    
            <x-success-button class="ml-2" wire:click="update()" wire:loading.attr='disabled' wire:target='update' >
                <i class="fa-solid fa-save " wire:loading.remove wire:target='save'></i>
                
                <div role="status" wire:loading wire:target='update'>
                    <svg aria-hidden="true" class="w-4 h-4 text-gray-200 animate-spin dark:text-gray-600 fill-blue-600" viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z" fill="currentColor"/>
                        <path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="currentFill"/>
                    </svg>
                    <span class="sr-only">Loading...</span>
                </div>
                <span class="ml-2">save</span>
            </x-success-button>
        </x-slot>
    </x-jet-dialog-modal>

       @push('scripts')
        <script>
             Livewire.on('deletePost', post_id => {
                Swal.fire({
                    title: "Are you sure?",
                    text: "You won't be able to revert this!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Yes, delete it!"
                }).then((result) => {
                    if (result.isConfirmed) {
                        Livewire.emitTo('posts.table-posts', 'delete', post_id)
                        Swal.fire({
                            title: "Deleted!",
                            text: "Your file has been deleted.",
                            icon: "success"
                        });
                    }
                });
             });
             
        </script>
       @endpush
</div>
