<?php

namespace App\Http\Livewire\Posts;

use App\Models\Post;
use Livewire\Component;
use Livewire\WithFileUploads;

class CreatePost extends Component
{
    use WithFileUploads;
  
    public $open = false;
    public $title, $content, $image, $identificador;

    protected $rules = [
      'title' => 'required|max:50',
      'content' => 'required|max:150',
      'image' => 'required|image|max:2048',
    ];

    
    //Este metodo se ejecutara cada vez que se modifique cada una de las propiedades del formulario a validar
    //y se usa para realizar validaciones en tiempo real
    public function updated( $propertyName ) {
      $this->validateOnly( $propertyName );
    }

    //este metodo se ejecuta cuando es montado el componente
    public function mount( ) {
    //esta variable nos sirve ára resetear los valores de input inmutables como los son los de tipo file
     $this->identificador = rand();
    }

    public function render()
    {
        return view('livewire.posts.create-post');
    }

    public function openModal() {
        $this->open = !$this->open; 
    }

    public function save() {
      $this->validate();

      $image = $this->image->store('public/posts');

      Post::create([
        'title' => $this->title,
        'content' => $this->content,
        'image' => $image
      ]);

      $this->reset(['open','title','content','image']);
      $this->identificador = rand();

      //Este metodo sera emitido para ser escuchado por el componente padre que lo contiene
      //Al usar solo el evento emit se volveran a renderizar todos todos los componentes que esten escuchando ese  metodo
      //$this->emit('render');
      
      //Al usar el evento emitTo se puede especificar cual componente sera el que se renderice
      $this->emitTo('posts.table-posts', 'render');
      $this->emit('alert', 'El post se creo satisfactoriamente');

    }

    // hook para borrar datos de modal cuando se cierre y no se guarden
    public function updatingOpen(  ) {
      $this->reset(['open','title','content','image']);
    }
}
