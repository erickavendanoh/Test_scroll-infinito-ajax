<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use Illuminate\Support\Facades\Response;

class PostController extends Controller
{
    public function index(Request $request){
        $posts = Post::latest()->paginate(10);
        //Cuando se pagina un modelo y se asigna a una variable el redultado, esa variable por defecto ya contará con propiedades (funciones) por defecto como "nextPageUrl()" que ya genera el link con la siguiente página con el siguiente bloque de información (# de registros definidos dentro del "::paginate()") si es que aún quedan. "links()" que tendrá hipervinculos, etc.
        if($request->ajax()){
            $view = view('posts.load', compact('posts'))->render();
            return Response::json(['view'=>$view, 'nextPageUrl'=>$posts->nextPageUrl()]);
        }
        return view('posts.index', ['posts'=>$posts]); //Solo entra acá la primera vez, cuando se carga la página por primera vez, ya después estará entrando en el if, por lo de que jQuery detectará lo del find el scroll y se hará la petición ajax
    }
}
