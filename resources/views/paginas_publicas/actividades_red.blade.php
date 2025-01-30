<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Red Internacional de Cátedras Martianas</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lato:ital,wght@0,100;0,300;0,400;0,700;0,900;1,100;1,300;1,400;1,700;1,900&family=Open+Sans:ital,wght@0,300..800;1,300..800&family=Work+Sans:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/actividades.css') }}">
</head>
@include('componentes.header')
<body>
  @if(isset($actividad))
    <div class="container mt-4">
            <div class="card-header">
                {{ $actividad->header }}
            </div>
            <div class="card-body">
                <h3 class="card-title">{{ $actividad->titulo }}</h3>
                <p class="card-text">{!! $actividad->cuerpo !!}</p>
            </div>
    </div>
  @else
    <div class="container mb-2">
        <div class="row g-5 mt-2">
          <div class="col-md-8">
            <div class="container">
                <div class="row">
                    @foreach($actividades as $index => $actividad)
                        <div id="cartas" class="col-12 col-md-4 mb-3">
                          <a class="link-offset-2 link-underline link-underline-opacity-0" href="{{ route('visualizar_actividades', $actividad->id) }}">
                            <div class="card mb-3 h-100 d-flex flex-column" style="max-width: 18rem;">
                                <!-- Asignar un degradado dinámico según el índice -->
                                <div class="card-header @if($index % 3 == 0) degradado-1
                                                       @elseif($index % 3 == 1) degradado-2
                                                       @else degradado-3 @endif">
                                    {{ $actividad->header }}
                                </div>
                                <div class="card-body">
                                    <h5 class="card-title">{{ $actividad->titulo }}</h5>
                                    <p class="card-text">
                                      {!! $actividad->cuerpo_truncado !!}
                                    </p>
                                </div>
                            </div>
                          </a>  
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        
            <div class="col-md-4">
              @include('componentes.noticias', ['noticias' => $noticias])
            </div>
          </div>
    </div>    
  @endif
</body>
@include('componentes.footer')
<!-- Bootstrap JS, Popper.js, and jQuery -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
<script src="{{ asset('vendor/tinymce/tinymce.min.js') }}"></script>
<script>
  tinymce.init({
      selector: '#cuerpo', // Selector del textarea
      plugins: 'link image media table codesample fullscreen',
      toolbar: 'undo redo | styleselect | bold italic | link image media | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | table codesample fullscreen',
      height: 300,
      menubar: false,
      branding: false,
      automatic_uploads: true,
      /* images_upload_url: '/subir-imagen',
      images_upload_handler: function (blobInfo, success, failure) {
          let formData = new FormData();
          formData.append('file', blobInfo.blob(), blobInfo.filename());
          fetch('/subir-imagen', {
              method: 'POST',
              headers: {
                  'X-CSRF-TOKEN': '{{ csrf_token() }}'
              },
              body: formData,
          })
          .then(response => response.json())
          .then(data => success(data.location))
          .catch(error => failure('Error al subir la imagen: ' + error.message));
      } */
  });
</script>
</html>