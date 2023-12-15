<?php

namespace App\Http\Livewire\Posts;

use App\Models\Post;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class EditPost extends Component
{
    use WithFileUploads;

    public $open =  false;
    public $post;
    public $image, $identificador;

    protected $rules = [
        'post.title' => 'required|max:15',
        'post.content' => 'required|max:150',
        'image' => 'nullable|image|max:2048',
      ];

    public function mount(Post $post) {
        $this->post = $post;
        $this->identificador = rand();
    }

    public function render()
    {
        return view('livewire.posts.edit-post');
    }

    public function save() {
        $this->validate();

       
        if( $this->image ){       
            Storage::delete( $this->post->image );
            $this->post->image = $this->image->store('public/posts');
        }

        $this->post->save();
  
        $this->reset(['open','image']);
        $this->identificador = rand();
  
        //Este metodo sera emitido para ser escuchado por el componente padre que lo contiene
        //Al usar solo el evento emit se volveran a renderizar todos todos los componentes que esten escuchando ese  metodo
        //$this->emit('render');
        
        //Al usar el evento emitTo se puede especificar cual componente sera el que se renderice
        $this->emitTo('posts.table-posts', 'render');
        $this->emit('alert', 'El post se modifico satisfactoriamente');
    }
}
