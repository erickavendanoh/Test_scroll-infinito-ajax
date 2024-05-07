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
            @include('posts.load')
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"
        integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script>
        $(document).ready(function() {
            // console.log("jQuery is workinggg!");
            let nextPageUrl = '{{ $posts->nextPageUrl() }}'; //Se asigna valor del otro bloque con información siguiente (paginación, generado por Laravel)

            $(window).scroll(function() {
                if ($(window).scrollTop() + $(window).height() >= $(document).height() - 100) {
                    if (nextPageUrl) { //si sí tuvo valor esa variable, ósea que si se generó por Laravel otro bloque con información y su respectivo link
                        console.log("Sigue una página más");
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
                    beforeSend: function(){
                        nextPageUrl = '';
                    },
                    // "data" trae lo generado en PostController despues de hacer la peticion ajax y lo generado para "view" y "nextPageUrl" alli
                    success: function(data){

                        nextPageUrl = data.nextPageUrl;
                        $('#posts-container').append(data.view);

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
