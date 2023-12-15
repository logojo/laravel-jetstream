<?php

namespace App\Http\Livewire\Posts;

use App\Models\Post;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class TablePosts extends Component
{   
    use WithFileUploads, WithPagination;

    public $titulo, $search = '', $show = '10';
    public $sort = 'id';
    public $direction = 'desc';

    public $post, $image, $identificador; //variables para uso del modal
    public $open_edit = false;


    //Esta propiedad escuchara los metodos emitidos por otros componentes
    //la primer propiedad representa el nombre del metodo emitido por el otro componente
    //la segunda representa el metodo que sera ejecutado en este componente
    //cuando el metodo que se emite se llama igual que el que se ejecuta se puede utilizar   -->  protected $listeners = ['render'];
    protected $listeners = ['render','delete'];

    protected $rules = [
        'post.title' => 'required|max:50',
        'post.content' => 'required|max:150',
        'image' => 'nullable|image|max:2048',
      ];

    //información que viajara por la url
    //except nos ayuda a que no aparescan en la url cuando los parametros tengan sus valores por default
    protected $queryString = [
        'show' => ['except' => '10'],
        'sort' => ['except' => 'id'],
        'direction' => ['except' => 'desc'],
        'search' => ['except' => '']
    ];

    #mount me sirve para modificar las variables enviadas de la vista
    #y para acceder a los parametros enviados desde la url
    public function mount($title) {
        $this->titulo = $title;
        //Se inicializa para evitar que marque error al iniciar la pagina
        $this->post = new Post();
        $this->identificador = rand();
    }

    public function render()
    {
        $posts = Post::where('title', 'like', '%'.$this->search.'%')
                     ->orWhere('content', 'like', '%'.$this->search.'%')
                     ->orderBy($this->sort, $this->direction)
                     ->paginate($this->show);

        return view('livewire.posts.table-posts', compact('posts'));
    }

    public function order( $value ) {
        if( $this->sort == $value )  {         
        
            if( $this->direction == 'desc' )
                $this->direction = 'asc';
            else
                $this->direction = 'desc';
        } else {
            $this->sort = $value;
            $this->direction = 'asc';
        }
    }

    public function edit( Post $post ) {
        $this->post = $post;
        $this->open_edit = true;
    }

    public function update() {
        $this->validate();
       
        if( $this->image ){    
            if ( $this->post->image ){ 
                Storage::delete( $this->post->image );
            }   
            $this->post->image = $this->image->store('public/posts');
        }

        $this->post->save();
  
        $this->reset(['open_edit','image']);
        $this->identificador = rand();
  
        //Este metodo sera emitido para ser escuchado por el componente padre que lo contiene
        //Al usar solo el evento emit se volveran a renderizar todos todos los componentes que esten escuchando ese  metodo
        //$this->emit('render');
        
        //Al usar el evento emitTo se puede especificar cual componente sera el que se renderice
        //Aqui no es necesario emitirlo ya que se esta realizar en el mismo componente
        //$this->emitTo('posts.table-posts', 'render');
        $this->emit('alert', 'El post se modifico satisfactoriamente');
    }

    public function delete(Post $post ) {
        $post->delete();
    }

}   
