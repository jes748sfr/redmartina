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

                <div class="d-flex justify-content-center p-4">
                  <h3 id="directorio">Directorio.</h3>
                </div>
                <hr>
                <div class="container pb-3">
                  <div class="row g-3 d-flex justify-content-center">
                    <div class="col-md-4">
                      <div class="card shadow-lg">
                          <div class="card-body">
                            <div class="d-flex justify-content-center pb-3">
                              <div class="image-container rounded-circle border-dot">
                                <img class="img-fluid" src="{{ asset('img/assets/icono.jpg') }}" alt="">
                              </div>
                            </div>
                            <h6 class="card-subtitle mb-2 text-muted">Coordinador de la Red Internacional de Cátedras Martianas</h6>
                            <p class="card-text">Dr. Mario Alberto Nájera Espinoza</p>
                          </div>
                      </div>
                    </div>  
                    <div class="col-md-4">
                      <div class="card shadow-lg">
                        <div class="card-body">
                          <div class="d-flex justify-content-center pb-3">
                            <div class="image-container rounded-circle border-dot">
                              <img class="img-fluid" src="https://media.licdn.com/dms/image/v2/C5603AQHXJg1w-Hz2Mw/profile-displayphoto-shrink_400_400/profile-displayphoto-shrink_400_400/0/1524142435954?e=1743033600&v=beta&t=hANFI-HCPzsIfuOepQsP83cgdbOrztZvpS-W83brMu4" alt="">
                            </div>
                          </div>
                          <h6 class="card-subtitle mb-2 text-muted">Asistente de coordinación de la Red Internacional de Cátedras Martianas</h6>
                          <p class="card-text">Mtra. Oralia Chantal Rodríguez Saucedo</p>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
            </div>
        
            <div class="col-md-4">
              @include('componentes.noticias', ['noticias' => $noticias])
            </div>
          </div>
    </div>    

</body>
@include('componentes.footer')
<!-- Bootstrap JS, Popper.js, and jQuery -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
</html>