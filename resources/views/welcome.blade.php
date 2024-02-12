<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>Alignment OKFN - Greece</title>
    <meta content="" name="description">
    <meta content="" name="keywords">



    <!-- Favicons -->
    <!-- <link href="assets/img/favicon.png" rel="icon">
  <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon"> -->

    <!-- Google Fonts -->
    <link href='//fonts.googleapis.com/css?family=Lato:300,400,700,300italic,400italic' rel='stylesheet'
        type='text/css'>
    <link href='//fonts.googleapis.com/css?family=Raleway:400,300,700' rel='stylesheet' type='text/css'>

    <!-- Vendor CSS Files -->
    <link href="./vendor/animate.css/animate.min.css" rel="stylesheet">
    <link href="./vendor/aos/aos.css" rel="stylesheet">
    <link href="./vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="./vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <link href="./vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
    <link href="./vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
    <link href="./vendor/remixicon/remixicon.css" rel="stylesheet">
    <link href="./vendor/swiper/swiper-bundle.min.css" rel="stylesheet">
    <!-- Template Main CSS File -->

    <link href="../public/css/style.css" rel="stylesheet">
    <link href="../public/css/custom.css" rel="stylesheet">


    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">

    <!-- =======================================================
  * Template Name: Selecao
  * Updated: Sep 18 2023 with Bootstrap v5.3.2
  * Template URL: https://bootstrapmade.com/selecao-bootstrap-template/
  * Author: BootstrapMade.com
  * License: https://bootstrapmade.com/license/
  ======================================================== -->
</head>

<body>

    <!-- ======= Header ======= -->
    <header id="header" class="fixed-top d-flex align-items-center  header-transparent ">
        <div class="container d-flex align-items-center justify-content-between">

            <div class="logo">
                <h1><a href="#">Alignment</a></h1>
                <!-- Uncomment below if you prefer to use an image logo -->
                <!-- <a href="index.html"><img src="assets/img/logo.png" alt="" class="img-fluid"></a>-->
            </div>

            <nav id="navbar" class="navbar">
                <ul>
                    <li><a class="nav-link scrollto" href="#about">About</a></li>
                    <li><a class="nav-link scrollto" href="#services">Services</a></li>
                    <li><a class="nav-link scrollto" href="#team">Team</a></li>
                    <li><a class="nav-link scrollto" href="#contact">Contact</a></li>
                    @if (Auth::guest())
                    <li><a href="{{ url('/admin') }}">Login</a></li>
                    @else
                    <li><a href="{{route('admin')}}">{{ Auth::user()->name }}</a></li>
                    @endif
                </ul>
                <i class="bi bi-list mobile-nav-toggle"></i>
            </nav><!-- .navbar -->

        </div>
    </header><!-- End Header -->

    <!-- ======= Hero Section ======= -->
    <section id="hero" class="d-flex flex-column justify-content-end align-items-center">
        <div id="heroCarousel" data-bs-interval="5000" class="container carousel carousel-fade" data-bs-ride="carousel">

            <!-- Slide 1 -->
            <div class="carousel-item active">
                <div class="carousel-container">
                    <h2 class="animate__animated animate__fadeInDown">Welcome to <span>Alignment</span></h2>
                    <p class="animate__animated fanimate__adeInUp">A tool to for semi-guided ontology alignment</p>
                    <a href="#about" class="btn-get-started animate__animated animate__fadeInUp scrollto">Get
                        Started!</a>
                </div>
            </div>
        </div>

        <svg class="hero-waves" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
            viewBox="0 24 150 28 " preserveAspectRatio="none">
            <defs>
                <path id="wave-path" d="M-160 44c30 0 58-18 88-18s 58 18 88 18 58-18 88-18 58 18 88 18 v44h-352z">
            </defs>
            <g class="wave1">
                <use xlink:href="#wave-path" x="50" y="3" fill="rgba(255,255,255, .1)">
            </g>
            <g class="wave2">
                <use xlink:href="#wave-path" x="50" y="0" fill="rgba(255,255,255, .2)">
            </g>
            <g class="wave3">
                <use xlink:href="#wave-path" x="50" y="9" fill="#fff">
            </g>
        </svg>

    </section><!-- End Hero -->

    <main id="main">

        <!-- ======= About Section ======= -->
        <section id="about" class="about">
            <div class="container">

                <div class="section-title" data-aos="zoom-out">
                    <h2>About</h2>
                    <p>Ontology matching</p>
                </div>

                <div class="row content" data-aos="fade-up">
                    <div class="col-lg-6">
                        <p>
                            Ontology matching is a crucial problem in the world of Semantic Web
                            and other distributed, open world applications. Diversity in tools,
                            knowledge, habits, language, interests and usually level of detail
                            may drive in heterogeneity. Thus, many automated applications have
                            <!DOCTYPE html>
                            <html lang="en">

                            <head>
                                <meta charset="utf-8">
                                <meta content="width=device-width, initial-scale=1.0" name="viewport">

                                <title>Welcome to Alignment</title>
                                <meta content="" name="description">
                                <meta content="" name="keywords">

                                <!-- Vendor CSS Files -->
                                <link href="{{ asset('vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
                                <link href="{{ asset('vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
                                <link href="{{ asset('vendor/aos/aos.css') }}" rel="stylesheet">
                                <link href="{{ asset('vendor/remixicon/remixicon.css') }}" rel="stylesheet">
                                <link href="{{ asset('vendor/swiper/swiper-bundle.min.css') }}" rel="stylesheet">
                                <link href="{{ asset('vendor/glightbox/css/glightbox.min.css') }}" rel="stylesheet">

                                <!-- Template Main CSS File -->
                                <link href="{{ asset('css/style.css') }}" rel="stylesheet">

                                <!-- =======================================================
                                * Template Name: Selecao - v4.3.0
                                * Template URL: https://bootstrapmade.com/selecao-bootstrap-template/
                                * Author: BootstrapMade.com
                                * License: https://bootstrapmade.com/license/
                                ======================================================== -->
                            </head>

                            <body>

                                <!-- ======= Header ======= -->
                                <header id="header" class="fixed-top">
                                    <div class="container d-flex align-items-center justify-content-between">

                                        <h1 class="logo"><a href="index.html">Alignment</a></h1>
                                        <!-- Uncomment below if you prefer to use an image logo -->
                                        <!-- <a href="index.html" class="logo"><img src="assets/img/logo.png" alt="" class="img-fluid"></a>-->

                                        <nav id="navbar" class="navbar">
                                            <ul>
                                                <li><a class="nav-link scrollto active" href="#hero">Home</a></li>
                                                <li><a class="nav-link scrollto" href="#about">About</a></li>
                                                <li><a class="nav-link scrollto" href="#services">Services</a></li>
                                                <li><a class="nav-link scrollto" href="#cta">Register</a></li>
                                                <li><a class="nav-link scrollto" href="#team">Team</a></li>
                                                <li><a class="nav-link scrollto" href="#screenshot">Screenshots</a></li>
                                                <li><a class="nav-link scrollto" href="#contact">Contact</a></li>
                                            </ul>
                                            <i class="bi bi-list mobile-nav-toggle"></i>
                                        </nav><!-- .navbar -->

                                    </div>
                                </header><!-- End Header -->

                                <!-- ======= Hero Section ======= -->
                                <section id="hero" class="d-flex align-items-center">
                                    <div class="container">
                                        <div class="row">
                                            <div class="col-lg-6 d-flex flex-column justify-content-center">
                                                <h1 data-aos="fade-up">Welcome to Alignment</h1>
                                                <h2 data-aos="fade-up" data-aos-delay="400">A tool for semi-guided ontology alignment</h2>
                                                <div data-aos="fade-up" data-aos-delay="600">
                                                    <div class="text-center text-lg-start">
                                                        <a href="#about" class="btn-get-started scrollto d-inline-flex align-items-center justify-content-center align-self-center">
                                                            <span>Get Started</span>
                                                            <i class="bi bi-arrow-right"></i>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-6 hero-img" data-aos="zoom-out" data-aos-delay="200">
                                                <img src="{{ asset('img/hero-img.png') }}" class="img-fluid" alt="">
                                            </div>
                                        </div>
                                    </div>
                                </section><!-- End Hero -->

                                <main id="main">

                                    <!-- ======= About Section ======= -->
                                    <section id="about" class="about">
                                        <div class="container">

                                            <div class="section-title" data-aos="zoom-out">
                                                <h2>About</h2>
                                                <p>What is Alignment?</p>
                                            </div>

                                            <div class="row content">
                                                <div class="col-lg-6" data-aos="fade-right" data-aos-delay="100">
                                                    <h3>Collaborative Ontology Matching Application</h3>
                                                    <p>Ontology matching is a key task in many applications, such as data integration, query answering, and ontology merging. Over the years, various ontology matching systems have been developed, implementing a large variety of matching techniques and similarity measures, with impressive results. However, there are situations where this is not enough and there must be human decision in order to create a link. We present Alignment, a collaborative, system aided, user-driven ontology matching application. Alignment offers a simple GUI environment for matching two ontologies/vocabularies with the aid of configurable similarity algorithms. We undertake research for the evaluation and validation of the default settings, taking into account expert users' feedback. Multiple users can work on the same project simultaneously. The application also offers social features, as users can vote, providing feedback, on the produced linksets. The linksets are available through a SPARQL endpoint and an API. Alignment is the outcome of the experience working with heterogeneous public budget data and has been used to align SKOS Vocabularies describing budget data across diverse levels of administrations of the EU and its member states.</p>
                                                </div>
                                                <div class="col-lg-6 pt-4 pt-lg-0" data-aos="fade-left" data-aos-delay="200">
                                                    <p>
                                                        <img src="{{ asset('img/about.jpg') }}" class="img-fluid" alt="">
                                                    </p>
                                                </div>
                                            </div>

                                        </div>
                                    </section><!-- End About Section -->

                                    <!-- ======= Services Section ======= -->
                                    <section id="services" class="services">
                                        <div class="container">

                                            <div class="section-title" data-aos="zoom-out">
                                                <h2>Services</h2>
                                                <p>Build to Connect</p>
                                            </div>

                                            <div class="row">
                                                <div class="col-lg-4 col-md-6">
                                                    <div class="icon-box" data-aos="zoom-in-left">
                                                        <div class="icon"><i class="bi bi-people"></i></div>
                                                        <h4 class="title"><a>Community</a></h4>
                                                        <p class="description">See GitHub project, post issues, and pull requests</p>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-md-6 mt-5 mt-md-0">
                                                    <div class="icon-box" data-aos="zoom-in-left" data-aos-delay="100">
                                                        <div class="icon"><i class="bi bi-gear"></i></div>
                                                        <h4 class="title"><a>Easy Configurable</a></h4>
                                                        <p class="description">Easy configuration through panels.</p>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-md-6 mt-5 mt-lg-0">
                                                    <div class="icon-box" data-aos="zoom-in-left" data-aos-delay="200">
                                                        <div class="icon"><i class="bi bi-link-45deg"></i></div>
                                                        <h4 class="title"><a href="">Silk Framework Integration</a></h4>
                                                        <p class="description">Silk Linking Framework Integration as it is a Linked Data standard.</p>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </section><!-- End Services Section -->

                                    <!-- ======= Cta Section ======= -->
                                    <section id="cta" class="cta">
                                        <div class="container">

                                            <div class="row" data-aos="zoom-out">
                                                <div class="col-lg-9 text-center text-lg-start">
                                                    <h3>Register</h3>
                                                    <p>If you are a new member and you do not have an account, you can create one easily and quickly!</p>
                                                </div>
                                                <div class="col-lg-3 cta-btn-container text-center">
                                                    <a class="cta-btn align-middle" href="{{ url('/register') }}">Register</a>
                                                </div>
                                            </div>

                                        </div>
                                    </section><!-- End Cta Section -->

                                    <!-- ======= Team Section ======= -->
                                    <section id="team" class="team">
                                        <div class="container">

                                            <div class="section-title" data-aos="zoom-out">
                                                <h2>Team</h2>
                                                <p>Meet Our Team</p>
                                            </div>

                                            <div class="row">
                                                <div class="col-lg-3 col-md-6 d-flex align-items-stretch">
                                                    <div class="member" data-aos="fade-up">
                                                        <div class="member-img">
                                                            <a href="https://okfn.gr" target="_blank" rel="noopener noreferrer">
                                                                <img src="{{ asset('img/okfgr.png') }}" class="img-fluid" alt="OKFGR Logo">
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </section><!-- End Team Section -->

                                    <!-- ======= Screenshot Section ======= -->
                                    <section id="screenshot" class="screenshot">
                                        <div class="container">

                                            <div class="section-title" data-aos="zoom-out">
                                                <h2>Screenshots</h2>
                                            </div>

                                            <div class="row">
                                                <div class="col-lg-12" data-aos="fade-up">
                                                    <ul class="list-unstyled d-flex justify-content-center">
                                                        <li><img src="{{ asset('img/gui.png') }}" class="img-fluid" alt="GUI Screenshot"></li>
                                                        <li><img src="{{ asset('img/flowchart.png') }}" class="img-fluid" alt="Flowchart Screenshot"></li>
                                                        <li><img src="{{ asset('img/checked_venn.png') }}" class="img-fluid" alt="Checked Venn Screenshot"></li>
                                                    </ul>
                                                </div>
                                            </div>

                                        </div>
                                    </section><!-- End Screenshot Section -->

                                    <!-- ======= Contact Section ======= -->
                                    <section id="contact" class="contact">
                                        <div class="container">

                                            <div class="section-title" data-aos="zoom-out">
                                                <h2>Contact</h2>
                                                <p>Contact Us</p>
                                            </div>

                                            <div class="row mt-5">
                                                <div class="col-lg-4" data-aos="fade-right">
                                                    <div class="info">
                                                        <div class="address">
                                                            <i class="bi bi-geo-alt"></i>
                                                            <h4>Location:</h4>
                                                            <p>International Hellenic University of Thessaloniki</p>
                                                        </div>
                                                        <div class="email">
                                                            <i class="bi bi-envelope"></i>
                                                            <h4>Email:</h4>
                                                            <p>info@example.com</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </section><!-- End Contact Section -->

                                </main><!-- End #main -->

                                <!-- ======= Footer ======= -->
                                <footer id="footer">
                                    <div class="container">
                                        <h3>Alignment</h3>
                                        <p>A tool for semi-guided ontology alignment</p>
                                        <div class="social-links">
                                            <a href="#" class="twitter"><i class="bi bi-twitter"></i></a>
                                            <a href="#" class="facebook"><i class="bi bi-facebook"></i></a>
                                            <a href="#" class="linkedin"><i class="bi bi-linkedin"></i></a>
                                        </div>
                                        <div class="credits">
                                            <p>
                                                <strong>Alignment &copy; {{ date("Y") }} <a href="http://www.okfn.gr">OKFN GREECE</a>.</strong>
                                            </p>
                                        </div>
                                    </div>
                                </footer><!-- End Footer -->

                                <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

                                <!-- Vendor JS Files -->
                                <script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
                                <script src="{{ asset('vendor/aos/aos.js') }}"></script>
                                <script src="{{ asset('vendor/glightbox/js/glightbox.min.js') }}"></script>
                                <script src="{{ asset('vendor/isotope-layout/isotope.pkgd.min.js') }}"></script>
                                <script src="{{ asset('vendor/swiper/swiper-bundle.min.js') }}"></script>
                                <script src="{{ asset('vendor/php-email-form/validate.js') }}"></script>

                                <!-- Template Main JS File -->
                                <script src="{{ asset('js/main.js') }}"></script>

                            </body>

                            </html>
