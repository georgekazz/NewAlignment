<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alignment OKFN - Greece</title>

    <!-- Favicons -->
    <link rel="icon" href="../public/img/favicon-alignment.png">
    <link rel="apple-touch-icon" href="../public/img/favicon-alignment.png">

    <!-- Google Fonts -->
    <link href="//fonts.googleapis.com/css?family=Lato:300,400,700,300italic,400italic" rel="stylesheet"
        type="text/css">
    <link href="//fonts.googleapis.com/css?family=Raleway:400,300,700" rel="stylesheet" type="text/css">

    <!-- Vendor CSS Files -->
    <link href="./vendor/animate.css/animate.min.css" rel="stylesheet">
    <link href="./vendor/aos/aos.css" rel="stylesheet">
    <link href="./vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="./vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <link href="./vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
    <link href="./vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
    <link href="./vendor/remixicon/remixicon.css" rel="stylesheet">
    <link href="./vendor/swiper/swiper-bundle.min.css" rel="stylesheet">

    <!-- Main CSS File -->
    <link href="../public/css/style.css" rel="stylesheet">
    <link href="../public/css/custom.css" rel="stylesheet">

    <!-- Toastify CSS -->
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
</head>

<style>
    /* Προσαρμογή διαστάσεων των εικόνων στο carousel */
    #screenshotCarousel .carousel-item img {
        max-height: 600px;
        /* Μέγιστο ύψος */
        max-width: 600px;
        /* Μέγιστο πλάτος */
        margin: auto;
        /* Κεντράρισμα της εικόνας */
    }

    .carousel-control-prev-icon,
    .carousel-control-next-icon {
        background-color: orange;
        /* Πορτοκαλί χρώμα */
    }

    .carousel-caption {
        background-color: rgba(0, 0, 0, 0.8); /* Μαύρο ημι-διαφανές φόντο */
        padding: 10px; /* Εσωτερικό περιθώριο */
        border-radius: 5px; /* Στρογγυλεμένες γωνίες */
        bottom: 20px; /* Απόσταση από το κάτω μέρος της εικόνας */
    }

    .carousel-caption p {
        margin: 0;
        color: white; /* Χρώμα κειμένου λευκό */
        font-size: 1rem; /* Μέγεθος γραμματοσειράς */
    }
</style>

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
                    <li><a class="nav-link scrollto" href="#screenshot">Screenshot</a></li>
                    <li><a class="nav-link scrollto" href="#contact">Contact</a></li>
                    @if (Auth::guest())
                        <li><a href="{{ url('/admin') }}">Login</a></li>
                    @else
                        <li><a href="{{route('admin')}}">{{ Auth::user()->name }}</a></li>
                    @endif
                    <li><a class="nav-link scrollto" href="{{ url('/register') }}">Register</a></li>

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

        <!-- About Section -->
        <section id="about" class="about">
            <div class="container">

                <!-- About Section Title -->
                <div class="section-title" data-aos="zoom-out">
                    <h2>About</h2>
                    <p>Ontology matching</p>
                </div>

                <!-- About Section Content -->
                <div class="row content" data-aos="fade-up">
                    <div class="col-lg-6">
                        <p>
                            Ontology matching is a crucial problem in the world of Semantic Web
                            and other distributed, open world applications. Diversity in tools,
                            knowledge, habits, language, interests and usually level of detail
                            may drive in heterogeneity. Thus, many automated applications have
                            <!-- Αφαιρέθηκε ο επιπλέον HTML κώδικας εδώ -->
                        </p>
                    </div>
                </div>

            </div>
        </section><!-- End About Section -->

        <!-- Services Section -->
        <section id="services" class="services">
            <div class="container">

                <!-- Section Title -->
                <div class="section-title" data-aos="zoom-out">
                    <h2>Services</h2>
                    <p>Build to Connect</p>
                </div>

                <!-- Services Items -->
                <div class="row">
                    <!-- Service Item 1 -->
                    <div class="col-lg-4 col-md-6">
                        <div class="icon-box" data-aos="zoom-in-left">
                            <div class="icon"><i class="bi bi-people"></i></div>
                            <h4 class="title"><a href="#">Community</a></h4>
                            <p class="description">Join our GitHub community to collaborate, report issues, and request
                                features.</p>
                        </div>
                    </div>
                    <!-- Service Item 2 -->
                    <div class="col-lg-4 col-md-6 mt-5 mt-md-0">
                        <div class="icon-box" data-aos="zoom-in-left" data-aos-delay="100">
                            <div class="icon"><i class="bi bi-gear"></i></div>
                            <h4 class="title"><a href="#">Easy Configuration</a></h4>
                            <p class="description">Configure Alignment easily through intuitive panels.</p>
                        </div>
                    </div>
                    <!-- Service Item 3 -->
                    <div class="col-lg-4 col-md-6 mt-5 mt-lg-0">
                        <div class="icon-box" data-aos="zoom-in-left" data-aos-delay="200">
                            <div class="icon"><i class="bi bi-link-45deg"></i></div>
                            <h4 class="title"><a href="#">Silk Framework Integration</a></h4>
                            <p class="description">Integrate with the Silk Linking Framework, a Linked Data standard.
                            </p>
                        </div>
                    </div>
                </div>

            </div>
        </section><!-- End Services Section -->




        <!-- Team Section -->
        <section id="team" class="team">
            <div class="container">

                <!-- Section Title -->
                <div class="section-title" data-aos="zoom-out">
                    <h2>Our Team</h2>
                    <p>Meet Our Team</p>
                </div>

                <!-- Team Members -->
                <div class="row">
                    <!-- Team Member 1 -->
                    <div class="col-lg-3 col-md-6 d-flex align-items-stretch">
                        <div class="member" data-aos="fade-up">
                            <div class="member-img">
                                <a href="https://okfn.gr" target="_blank" rel="noopener noreferrer">
                                    <img src="{{ asset('img/okfngr.png') }}" class="img-fluid" alt="OKFGR Logo">
                                </a>
                            </div>
                        </div>
                    </div>
                    <!-- Repeat the above structure for additional team members -->
                </div>

            </div>
        </section><!-- End Team Section -->


        <!-- Screenshot Section -->
        <section id="screenshot" class="screenshot">
            <div class="container">

                <!-- Section Title -->
                <div class="section-title" data-aos="zoom-out">
                    <h2>Screenshots</h2>
                </div>

                <!-- Carousel -->
                <div id="screenshotCarousel" class="carousel slide" data-bs-ride="carousel">
                    <div class="carousel-inner">
                        <!-- Screenshot 1 -->
                        <div class="carousel-item active">
                            <img src="{{ asset('img/gui.jpg') }}" class="d-block w-100" alt="GUI Screenshot">
                            <div class="carousel-caption d-none d-md-block">
                                <p>New Admin Panel</p>
                            </div>
                        </div>
                        <!-- Screenshot 2 -->
                        <div class="carousel-item">
                            <img src="{{ asset('img/flowchart.jpg') }}" class="d-block w-100"
                                alt="Flowchart Screenshot">
                            <div class="carousel-caption d-none d-md-block">
                                <p>Brand new layout for alignment</p>
                            </div>
                        </div>
                        <!-- Screenshot 3 -->
                        <div class="carousel-item">
                            <img src="{{ asset('img/checked_venn.jpg') }}" class="d-block w-100"
                                alt="Checked Venn Screenshot">
                            <div class="carousel-caption d-none d-md-block">
                                <p>New page for graphs</p>
                            </div>
                        </div>
                        <!-- Screenshot 4 -->
                        <div class="carousel-item">
                            <img src="{{ asset('img/chart-img.jpg') }}" class="d-block w-100"
                                alt="Checked Venn Screenshot">
                            <div class="carousel-caption d-none d-md-block">
                                <p>Check your graphs with the power of Force Directed Tree</p>
                            </div>
                        </div>
                    </div>
                    <!-- Carousel Navigation -->
                    <button class="carousel-control-prev" type="button" data-bs-target="#screenshotCarousel"
                        data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Previous</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#screenshotCarousel"
                        data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Next</span>
                    </button>
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
                <a href="https://x.com/okfngr?lang=en&mx=2" class="twitter"><i class="bi bi-twitter"></i></a>
                <a href="https://www.facebook.com/okfngreece/?locale=el_GR" class="facebook"><i class="bi bi-facebook"></i></a>
                <a href="https://www.linkedin.com/company/okfngr/?originalSubdomain=gr" class="linkedin"><i class="bi bi-linkedin"></i></a>
            </div>
            <div class="credits">
                <p>
                    <strong>Alignment &copy; {{ date("Y") }} <a href="http://www.okfn.gr">OKFN GREECE</a>.</strong>
                </p>
            </div>
        </div>
    </footer><!-- End Footer -->

    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i
            class="bi bi-arrow-up-short"></i></a>

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