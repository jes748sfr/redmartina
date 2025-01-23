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
    <style>
    html {
        scroll-behavior: smooth;
    }
    .img-corner {
      position: absolute;
      top: 10px; /* Espacio desde la parte superior */
      right: 10px; /* Espacio desde la derecha */
      width: 50px; /* Tamaño de la imagen */
      height: 50px; /* Tamaño de la imagen */
      border-radius: 50%; /* Hace la imagen circular */
      object-fit: cover; /* Ajusta la imagen dentro del contenedor */
    }
    .border-dot{
      border-style: double;
      border-color: #4DA1A9;
      border-width: 4px;
    }
    </style>

</head>
@include('componentes.header')
<body>
    <div class="container mb-2">
        <div class="row g-5 mt-2">
            <div class="col-md-8">
        
              <article class="blog-post">
                <h2 class="display-5 link-body-emphasis mb-1">Bienvenidos a la página de la Red Internacional de Cátedras Martianas</h2>
        
                <p>Hemos creado con la colaboración de la Universidad de Guadalajara el portal de de la Red Internacional de Cátedras Martianas con el cual se pretende tener un espacio martiano de enlace en el que se difundirán nuestras actividades, proyectos y textos así como acuerdos y convenios de nuestra red.</p>
                <hr>
                <p>La página de la Red Internacional de Cátedras Martianas será una herramienta de comunicación muy adecuada y necesaria, para generar puntos de encuentro. Este nuevo sitio coadyuvará a formar una gran comunidad de cercanía martiana de acuerdo a los valores y postulados sobre la integración latinoamericana y mundial.</p>
                <h2>Nuestra historia</h2>
                <p>La Red de Cátedras Martianas se fortalece con los Convenios de colaboración e intercambio firmados entre Universidades y otras instituciones de manera bilateral, y en particular con la Oficina del Programa Martiano, y el Centro de Estudios Martianos, de Cuba, institución ésta –única en el espectro académico internacional- que trabaja con una planta de investigadores reconocidos y cuyos hallazgos vienen a revitalizar las investigaciones en marcha de la Red Internacional, a la vez que se estimula la creación de proyectos nuevos y estudios sobre los problemas de nuestro tiempo.</p>
                <p>Hasta el año 2018 se han celebrado quince Encuentros internacionales de Cátedras Martianas, el más reciente, se llevó a cabo en la Universidad de El salvador , en la ciudad de San Salvador los días 23, 24 y 25 de noviembre.</p>
                <blockquote class="blockquote">
                  <p>La Red Internacional de Cátedras Martianas cuenta con un directorio que ha ido creciendo, el cual facilita la comunicación inmediata. </p>
                </blockquote>

                <h3>Directorio.</h3>
                <div class="container">
                  <div class="row g-3">
                    <div class="col-md-4">
                      <div class="card shadow-lg">
                          <div class="card-body">
                            <div class="d-flex justify-content-center">
                              <img class="img-fluid rounded-circle border-dot" style="width: 50%; height: auto;" src="https://media.licdn.com/dms/image/v2/C5603AQHXJg1w-Hz2Mw/profile-displayphoto-shrink_400_400/profile-displayphoto-shrink_400_400/0/1524142435954?e=1743033600&v=beta&t=hANFI-HCPzsIfuOepQsP83cgdbOrztZvpS-W83brMu4" alt="">
                            </div>
                            <h6 class="card-subtitle mb-2 text-muted">Coordinador de la Red Internacional de Cátedras Martianas</h6>
                            <p class="card-text">Dr. Mario Alberto Nájera Espinoza</p>
                          </div>
                      </div>
                    </div>  
                    <div class="col-md-4">
                      <div class="card shadow-lg">
                        <div class="card-body">
                          <div class="d-flex justify-content-center">
                            <img class="img-fluid rounded-circle border-dot" style="width: 50%; height: auto;" src="https://media.licdn.com/dms/image/v2/C5603AQHXJg1w-Hz2Mw/profile-displayphoto-shrink_400_400/profile-displayphoto-shrink_400_400/0/1524142435954?e=1743033600&v=beta&t=hANFI-HCPzsIfuOepQsP83cgdbOrztZvpS-W83brMu4" alt="">
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
              <div class="position-sticky" style="top: 2rem;">
        
                <div>
                  <h4 class="fst-italic">Noticias</h4>
                  <ul class="list-unstyled">
                    <li>
                      <a class="d-flex flex-column flex-lg-row gap-3 align-items-start align-items-lg-center py-3 link-body-emphasis text-decoration-none border-top" href="#">
                        <svg class="bd-placeholder-img" width="100%" height="96" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" preserveAspectRatio="xMidYMid slice" focusable="false"><rect width="100%" height="100%" fill="#777"></rect></svg>
                        <div class="col-lg-8">
                          <h6 class="mb-0">Ejemplo de noticia</h6>
                          <small class="text-body-secondary">January 15, 2024</small>
                        </div>
                      </a>
                    </li>
                    <li>
                      <a class="d-flex flex-column flex-lg-row gap-3 align-items-start align-items-lg-center py-3 link-body-emphasis text-decoration-none border-top" href="#">
                        <svg class="bd-placeholder-img" width="100%" height="96" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" preserveAspectRatio="xMidYMid slice" focusable="false"><rect width="100%" height="100%" fill="#777"></rect></svg>
                        <div class="col-lg-8">
                          <h6 class="mb-0">Ejemplo de noticia</h6>
                          <small class="text-body-secondary">January 14, 2024</small>
                        </div>
                      </a>
                    </li>
                    <li>
                      <a class="d-flex flex-column flex-lg-row gap-3 align-items-start align-items-lg-center py-3 link-body-emphasis text-decoration-none border-top" href="#">
                        <svg class="bd-placeholder-img" width="100%" height="96" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" preserveAspectRatio="xMidYMid slice" focusable="false"><rect width="100%" height="100%" fill="#777"></rect></svg>
                        <div class="col-lg-8">
                          <h6 class="mb-0">Ejemplo de noticia</h6>
                          <small class="text-body-secondary">January 13, 2024</small>
                        </div>
                      </a>
                    </li>
                  </ul>
                </div>
        
                <div class="p-4">
                  <h4 class="fst-italic">Red Universitaria</h4>
                  <ol class="list-unstyled mb-0">
                    <li class="views-row views-row-1 views-row-odd views-row-first">  
                        <div class="views-field views-field-field-banner">
                            <div class="field-content">
                                <a href="http://catedramarti.srp.ucr.ac.cr/marti2.html">
                                    <img typeof="foaf:Image" src="http://redmartiana.cucsh.udg.mx/sites/default/files/univ.costarica.jpg" width="280" height="97" alt="">
                                </a>
                            </div>  
                        </div>
                    </li>
                    <li class="views-row views-row-2 views-row-even">  
                        <div class="views-field views-field-field-banner">
                            <div class="field-content">
                                <a href="http://www.martiano.cu/">
                                    <img typeof="foaf:Image" src="http://redmartiana.cucsh.udg.mx/sites/default/files/sociedadcultura.jpg" width="280" height="97" alt="">
                                </a>
                            </div>
                        </div>
                    </li>
                    <li class="views-row views-row-3 views-row-odd">  
                        <div class="views-field views-field-field-banner">
                            <div class="field-content">
                                <a href="http://www.josemarti.cu/">
                                    <img typeof="foaf:Image" src="http://redmartiana.cucsh.udg.mx/sites/default/files/banner2.jpg" width="280" height="97" alt="">
                                </a>
                            </div>
                        </div>
                    </li>
                    <li class="views-row views-row-4 views-row-even views-row-last">  
                        <div class="views-field views-field-field-banner">
                            <div class="field-content">
                                <a href="http://www.cmarti.cucsh.udg.mx/">
                                    <img typeof="foaf:Image" src="http://redmartiana.cucsh.udg.mx/sites/default/files/josemarti.jpg" width="280" height="97" alt="">
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