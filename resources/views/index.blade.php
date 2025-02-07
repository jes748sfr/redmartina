<!DOCTYPE html>
<html lang="es">
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
    <link rel="stylesheet" href="{{ asset('css/index.css') }}">

</head>
@include('componentes.header')
<body>
      <div class="container-fluid">
        <div class="row justify-content-center">
          <div class="col-md-8 col-lg-12 text-center heading-section ftco-animate">
              <h2 class="mb-4"><span>Noticias</span> Recientes</h2>
          </div>
          <hr style="width: 80%;">
        </div>
        <div class="row justify-content-center w-100">
          @forelse($noticias as $noticia)
            <div class="card mx-3 mb-3" style="width: 18rem;">
              {{-- <img src="..." class="card-img-top" alt="..."> --}}
              @php
                $imagen = $noticia->documentacionAs->isNotEmpty() 
                          ? 'documentacion_actividades/' .$noticia->documentacionAs->first()->archivo 
                          : 'img/assets/icono.jpg'; // Imagen por defecto si no hay imágenes
                $nombreArchivo = pathinfo($imagen, PATHINFO_FILENAME);
                $extension = pathinfo($imagen, PATHINFO_EXTENSION);

                $imagen = ($extension == 'pdf') 
                          ? 'img/assets/icono.jpg' 
                          : $imagen;
              @endphp
              <img src="{{ asset($imagen) }}" class="card-img-top" alt="Imagen de la actividad" style="width: 100%; height: 180px; object-fit: cover;">
              <div class="card-body d-flex flex-column">
                <!-- Contenido principal -->
                <div class="flex-grow-1">
                    <h5 class="card-title">{{ Str::limit($noticia->titulo, 50, '...') }}</h5>
                    <p class="card-text">{!! $noticia->cuerpo_truncado !!}</p>
                </div>
            
                <!-- Metadatos (fecha y botón) alineados al final -->
                <div class="mt-auto">
                    <p class="card-text text-muted mb-2">{{ $noticia->fecha }}</p>
                    <a href="{{ route('visualizar_actividades', ['id' => $noticia->id]) }}" class="btn btn-primary">Continuar leyendo</a>
                </div>
            </div>
            </div>
          @empty
            @for ($i = 0; $i < 4; $i++)
              <div class="card mx-3 mb-3" style="width: 18rem;" aria-hidden="true">
                <svg class="bd-placeholder-img card-img-top" width="100%" height="180" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Placeholder: Image cap" preserveAspectRatio="xMidYMid slice" focusable="false"><title>Cargando...</title><rect width="100%" height="100%" fill="#868e96"></rect></svg>
                <div class="card-body">
                  <h5 class="card-title placeholder-glow">
                    <span class="placeholder col-6"></span>
                  </h5>
                  <p class="card-text placeholder-glow">
                    <span class="placeholder col-7"></span>
                    <span class="placeholder col-4"></span>
                    <span class="placeholder col-4"></span>
                    <span class="placeholder col-6"></span>
                    <span class="placeholder col-8"></span>
                  </p>
                  <a href="#" tabindex="-1" class="btn btn-primary disabled placeholder col-6"></a>
                </div>
              </div>
            @endfor
          @endforelse
        </div>
      </div>
      <div class="container mb-2">
        <div class="row g-3 mt-2">
            <div class="col-md-8">
        
              <article class="blog-post">
                <div class="pb-5">
                  <h2 class="display-5 link-body-emphasis mb-1">Bienvenidos a la página de la Red Internacional de Cátedras Martianas</h2>
        
                <p>Hemos creado con la colaboración de la Universidad de Guadalajara el portal de de la Red Internacional de Cátedras Martianas con el cual se pretende tener un espacio martiano de enlace en el que se difundirán nuestras actividades, proyectos y textos así como acuerdos y convenios de nuestra red.</p>
                <p>La página de la Red Internacional de Cátedras Martianas será una herramienta de comunicación muy adecuada y necesaria, para generar puntos de encuentro. Este nuevo sitio coadyuvará a formar una gran comunidad de cercanía martiana de acuerdo a los valores y postulados sobre la integración latinoamericana y mundial.</p>
                </div>
                <div class="d-flex justify-content-center">
                  <h2 id="historia">Nuestra historia</h2>
                </div>
                <hr>
                <div class="d-flex justify-content-center">
                  <div class="p-3">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" width="75" height="75">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25" />
                    </svg>                    
                  </div>
                </div>
                <p>La Red de Cátedras Martianas se fortalece con los Convenios de colaboración e intercambio firmados entre Universidades y otras instituciones de manera bilateral, y en particular con la Oficina del Programa Martiano, y el Centro de Estudios Martianos, de Cuba, institución ésta –única en el espectro académico internacional- que trabaja con una planta de investigadores reconocidos y cuyos hallazgos vienen a revitalizar las investigaciones en marcha de la Red Internacional, a la vez que se estimula la creación de proyectos nuevos y estudios sobre los problemas de nuestro tiempo.</p>
                <div class="d-flex justify-content-center">
                  <div class="p-3">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" width="75" height="75">
                      <path stroke-linecap="round" stroke-linejoin="round" d="m6.115 5.19.319 1.913A6 6 0 0 0 8.11 10.36L9.75 12l-.387.775c-.217.433-.132.956.21 1.298l1.348 1.348c.21.21.329.497.329.795v1.089c0 .426.24.815.622 1.006l.153.076c.433.217.956.132 1.298-.21l.723-.723a8.7 8.7 0 0 0 2.288-4.042 1.087 1.087 0 0 0-.358-1.099l-1.33-1.108c-.251-.21-.582-.299-.905-.245l-1.17.195a1.125 1.125 0 0 1-.98-.314l-.295-.295a1.125 1.125 0 0 1 0-1.591l.13-.132a1.125 1.125 0 0 1 1.3-.21l.603.302a.809.809 0 0 0 1.086-1.086L14.25 7.5l1.256-.837a4.5 4.5 0 0 0 1.528-1.732l.146-.292M6.115 5.19A9 9 0 1 0 17.18 4.64M6.115 5.19A8.965 8.965 0 0 1 12 3c1.929 0 3.716.607 5.18 1.64" />
                    </svg>
                  </div>
                </div>
                <p>Hasta el año 2018 se han celebrado quince Encuentros internacionales de Cátedras Martianas, el más reciente, se llevó a cabo en la Universidad de El salvador , en la ciudad de San Salvador los días 23, 24 y 25 de noviembre.</p>
                <blockquote class="blockquote">
                  <p>La Red Internacional de Cátedras Martianas cuenta con un directorio que ha ido creciendo, el cual facilita la comunicación inmediata. </p>
                </blockquote>
            </div>
        
            <div class="col-md-4">
              <div class="position-sticky" style="top: 5rem;">
            
                <div class="p-4">
                  <h4 class="fst-italic">Red Universitaria</h4>
                  <ol class="list-unstyled mb-0">
                    <li class="views-row views-row-1 views-row-odd views-row-first p-2">  
                        <div class="views-field views-field-field-banner">
                            <div class="field-content">
                                <a href="http://catedramarti.srp.ucr.ac.cr/marti2.html">
                                    <img typeof="foaf:Image" src="{{ asset('img/assets/red_universitaria1.jpg') }}" width="280" height="97" alt="">
                                </a>
                            </div>  
                        </div>
                    </li>
                    <li class="views-row views-row-2 views-row-even p-2">  
                        <div class="views-field views-field-field-banner">
                            <div class="field-content">
                                <a href="http://www.martiano.cu/">
                                    <img typeof="foaf:Image" src="{{ asset('img/assets/red_universitaria2.jpg') }}" width="280" height="97" alt="">
                                </a>
                            </div>
                        </div>
                    </li>
                    <li class="views-row views-row-3 views-row-odd p-2">  
                        <div class="views-field views-field-field-banner">
                            <div class="field-content">
                                <a href="http://www.josemarti.cu/">
                                    <img typeof="foaf:Image" src="{{ asset('img/assets/red_universitaria3.jpg') }}" width="280" height="97" alt="">
                                </a>
                            </div>
                        </div>
                    </li>
                    <li class="views-row views-row-4 views-row-even views-row-last p-2">  
                        <div class="views-field views-field-field-banner">
                            <div class="field-content">
                                <a href="http://www.cmarti.cucsh.udg.mx/">
                                    <img typeof="foaf:Image" src="{{ asset('img/assets/red_universitaria4.jpg') }}" width="280" height="97" alt="">
                                </a>
                            </div>
                        </div>
                    </li>
                  </ol>
                </div>
            
              </div>
            </div>
          </div>
    </div>  

</body>
@include('componentes.footer')
<!-- Bootstrap JS, Popper.js, and jQuery -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
</html>