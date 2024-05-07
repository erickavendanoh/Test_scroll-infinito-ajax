<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Scroll infinito AJAX</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>

<body>
    <div class="container mt-5">
        <h2 class="mb-4">Posts</h2>
        <div id="posts-container">
            {{-- La parte HTML (y con php (blade.php)) que muestra los registros correspondientes a cada paginación --}}
            @include('posts.load')
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"
        integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script>
        $(document).ready(function() {
            // console.log("jQuery is workinggg!");
            let nextPageUrl = '{{ $posts->nextPageUrl() }}'; //Se asigna valor del otro bloque con información siguiente (paginación, generado por Laravel) si es que aún quedan registros por mostrar

            $(window).scroll(function() { //se ejecuta cada que se hace scroll a la página
                if ($(window).scrollTop() + $(window).height() >= $(document).height() - 100) { //detecta cuando se llega al final
                    if (nextPageUrl) { //Valida si sí tuvo valor esa variable, ósea que si se generó por Laravel otro bloque con información y su respectivo link, por lo que en la asignación de ese valor a variable "nextPageUrl" no fue null
                        // console.log("Sigue una página más");
                        loadMorePosts();
                    }
                }
            });

            function loadMorePosts() {
                console.log(nextPageUrl);
                // alert("load more");
                //Se hace petición ajax (la cual se detectará en el if del PostController), con respecto a la url (que corresponde a la generada por Laravel, con bloque de información siguiente con su hipervínculo), el tipo de petición (GET)
                $.ajax({
                    url: nextPageUrl,
                    type: 'get',
                    //Antes de mandar la petición...
                    beforeSend: function(){
                        nextPageUrl = ''; //se establece valor de variable "nextPageUrl" en vacío. Se "limpia"
                    },

                    //Ya en este punto como se hizo una petición (ajax) el PostController la detecta en su método index() y se vuelve a ejecutar lo de dentro de él, que es que se hace la siguiente paginación (también por ende se genera el siguiente "nextPageUrl()") y se entra al if que detecta y "procesa" la petición ajax
                    // "data" trae lo generado en PostController despues de hacer la peticion ajax y lo generado para "view" (la generación de la vista de load.blade.php con los "posts" de la siguiente paginación) y "nextPageUrl" allí, y acá ya se emplean esas variables y sus valores
                    success: function(data){
                        nextPageUrl = data.nextPageUrl;
                        $('#posts-container').append(data.view); //se agrega a ese div la vista (que llega mediante un callback, con relación a la respuesta)
                    },
                    error: function(xhr, status, error){
                        console.error("Error loading more posts:", error);
                        alert("error: " + error);
                    }
                });
            }
        });
    </script>
</body>
</html>
