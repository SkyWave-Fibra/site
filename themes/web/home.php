<!DOCTYPE html>
<html lang="en">
<!--begin::Head-->

<head>
    <base href="<?= url(CONF_THEME) ?>/" />

    <?= $head ?>

    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <!--Favicon-->
    <link rel="icon" type="image/png" href="../../assets/images/favicon/favicon-96x96.png" sizes="96x96" />
    <link rel="icon" type="image/svg+xml" href="../../assets/images/favicon/favicon.svg" />
    <link rel="shortcut icon" href="../../assets/images/favicon/favicon.ico" />
    <link rel="apple-touch-icon" sizes="180x180" href="../../assets/images/favicon/apple-touch-icon.png" />
    <meta name="apple-mobile-web-app-title" content="MyWebSite" />
    <link rel="manifest" href="../../assets/images/favicon/site.webmanifest" />

    <!--begin::Fonts(mandatory for all pages)-->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700" />
    <!--end::Fonts-->
    <!--begin::Global Stylesheets Bundle(mandatory for all pages)-->
    <link href="assets/plugins/global/plugins.bundle.css" rel="stylesheet" type="text/css" />
    <link href="assets/css/style.bundle.css" rel="stylesheet" type="text/css" />
    <!--end::Global Stylesheets Bundle-->
    <script>
        // Frame-busting to prevent site from being loaded within a frame without permission (click-jacking) if (window.top != window.self) { window.top.location.replace(window.self.location.href); }
    </script>
</head>
<!--end::Head-->
<!--begin::Body-->

<body id="kt_body" data-bs-spy="scroll" data-bs-target="#kt_landing_menu" class="bg-body position-relative app-blank">
    <!--begin::Theme mode setup on page load-->
    <script>
        var defaultThemeMode = "light";
        var themeMode;
        if (document.documentElement) {
            if (document.documentElement.hasAttribute("data-bs-theme-mode")) {
                themeMode = document.documentElement.getAttribute("data-bs-theme-mode");
            } else {
                if (localStorage.getItem("data-bs-theme") !== null) {
                    themeMode = localStorage.getItem("data-bs-theme");
                } else {
                    themeMode = defaultThemeMode;
                }
            }
            if (themeMode === "system") {
                themeMode = window.matchMedia("(prefers-color-scheme: dark)").matches ? "dark" : "light";
            }
            document.documentElement.setAttribute("data-bs-theme", themeMode);
        }
    </script>
    <!--end::Theme mode setup on page load-->
    <!--begin::Root-->
    <div class="d-flex flex-column flex-root" id="kt_app_root">
        <!--begin::Header Section-->
        <div class="mb-0" id="home">
            <!--begin::Wrapper-->
            <div class="bgi-no-repeat bgi-size-contain bgi-position-x-center bgi-position-y-bottom landing-dark-bg" style="background-image: url(assets/media/svg/illustrations/landing.svg)">
                <!--begin::Header-->
                <div class="landing-header" data-kt-sticky="true" data-kt-sticky-name="landing-header" data-kt-sticky-offset="{default: '200px', lg: '300px'}">
                    <!--begin::Container-->
                    <div class="container">
                        <!--begin::Wrapper-->
                        <div class="d-flex align-items-center justify-content-between">
                            <!--begin::Logo-->
                            <div class="d-flex align-items-center flex-equal">
                                <!--begin::Mobile menu toggle-->
                                <button class="btn btn-icon btn-active-color-primary me-3 d-flex d-lg-none" id="kt_landing_menu_toggle">
                                    <i class="ki-outline ki-abstract-14 fs-2hx"></i>
                                </button>
                                <!--end::Mobile menu toggle-->
                                <!--begin::Logo image-->
                                <a href="<?= url() ?>">
                                    <img alt="Logo" src="../../assets/images/front/header.png" class="logo-default h-60px h-lg-60px rounded" />
                                    <img alt="Logo" src="../../assets/images/front/headerdark.png" class="logo-sticky h-60px h-lg-60px rounded" />
                                </a>
                                <!--end::Logo image-->
                            </div>
                            <!--end::Logo-->
                            <!--begin::Menu wrapper-->
                            <div class="d-lg-block" id="kt_header_nav_wrapper">
                                <div class="d-lg-block p-5 p-lg-0" data-kt-drawer="true" data-kt-drawer-name="landing-menu" data-kt-drawer-activate="{default: true, lg: false}" data-kt-drawer-overlay="true" data-kt-drawer-width="200px" data-kt-drawer-direction="start" data-kt-drawer-toggle="#kt_landing_menu_toggle" data-kt-swapper="true" data-kt-swapper-mode="prepend" data-kt-swapper-parent="{default: '#kt_body', lg: '#kt_header_nav_wrapper'}">
                                    <!--begin::Menu-->
                                    <div class="menu menu-column flex-nowrap menu-rounded menu-lg-row menu-title-gray-600 menu-state-title-primary nav nav-flush fs-5 fw-semibold" id="kt_landing_menu">

                                        <!--begin::Menu item-->
                                        <div class="menu-item">
                                            <a class="menu-link nav-link active py-3 px-4 px-xxl-6"
                                                href="<?= url() ?>" data-kt-scroll-toggle="true" data-kt-drawer-dismiss="true">
                                                Início
                                            </a>
                                        </div>
                                        <!--end::Menu item-->

                                        <div class="menu-item">
                                            <a class="menu-link nav-link py-3 px-4 px-xxl-6"
                                                href="#how-it-works" data-kt-scroll-toggle="true" data-kt-drawer-dismiss="true">
                                                Como Funciona
                                            </a>
                                        </div>

                                        <div class="menu-item">
                                            <a class="menu-link nav-link py-3 px-4 px-xxl-6"
                                                href="#achievements" data-kt-scroll-toggle="true" data-kt-drawer-dismiss="true">
                                                Nossos Números
                                            </a>
                                        </div>

                                        <div class="menu-item">
                                            <a class="menu-link nav-link py-3 px-4 px-xxl-6"
                                                href="#team" data-kt-scroll-toggle="true" data-kt-drawer-dismiss="true">
                                                Nosso Time
                                            </a>
                                        </div>

                                        <div class="menu-item">
                                            <a class="menu-link nav-link py-3 px-4 px-xxl-6"
                                                href="#pricing" data-kt-scroll-toggle="true" data-kt-drawer-dismiss="true">
                                                Planos
                                            </a>
                                        </div>

                                        <div class="menu-item">
                                            <a class="menu-link nav-link py-3 px-4 px-xxl-6"
                                                href="#clientsgti" data-kt-scroll-toggle="true" data-kt-drawer-dismiss="true">
                                                Depoimentos
                                            </a>
                                        </div>

                                        <div class="menu-item">
                                            <a class="menu-link nav-link py-3 px-4 px-xxl-6"
                                                href="#contactgti" data-kt-scroll-toggle="true" data-kt-drawer-dismiss="true">
                                                Contato
                                            </a>
                                        </div>
                                    </div>

                                    <!--end::Menu-->
                                </div>
                            </div>
                            <!--end::Menu wrapper-->
                            <!--begin::Toolbar-->
                            <div class="flex-equal text-end ms-1">
                                <a href="<?= url("entrar") ?>" class="btn btn-success">Área do Cliente</a>
                            </div>
                            <!--end::Toolbar-->
                        </div>
                        <!--end::Wrapper-->
                    </div>
                    <!--end::Container-->
                </div>
                <!--end::Header-->
                <!--begin::Landing hero-->
                <div class="d-flex flex-column flex-center w-100 min-h-350px min-h-lg-500px px-9">
                    <!--begin::Heading-->
                    <div class="text-center mb-5 mb-lg-10 py-10 py-lg-20">
                        <!--begin::Title-->
                        <<h1 class="text-white lh-base fw-bold fs-2x fs-lg-3x mb-15">
                            Internet de Ultra Velocidade <br />
                            com a qualidade da
                            <span style="background: linear-gradient(to right, #00c6ff 0%, #0072ff 100%);-webkit-background-clip: text;-webkit-text-fill-color: transparent;">
                                <span id="kt_landing_hero_text">Sky Wave Fibra</span>
                            </span>
                            </h1>
                            <!--end::Title-->
                            <!--begin::Action-->
                            <a href="#pricing" data-kt-scroll-toggle="true" class="btn btn-primary btn-lg">
                                Conheça nossos Planos
                            </a>
                            <!--end::Action-->
                    </div>
                    <!--end::Heading-->
                    <?php /*
                    <!--begin::Clients-->
                    <div class="d-flex flex-center flex-wrap position-relative px-5">
                        <!--begin::Client-->
                        <div class="d-flex flex-center m-3 m-md-6" data-bs-toggle="tooltip" title="Fujifilm">
                            <img src="assets/media/svg/brand-logos/fujifilm.svg" class="mh-30px mh-lg-40px" alt="" />
                        </div>
                        <!--end::Client-->
                        <!--begin::Client-->
                        <div class="d-flex flex-center m-3 m-md-6" data-bs-toggle="tooltip" title="Vodafone">
                            <img src="assets/media/svg/brand-logos/vodafone.svg" class="mh-30px mh-lg-40px" alt="" />
                        </div>
                        <!--end::Client-->
                        <!--begin::Client-->
                        <div class="d-flex flex-center m-3 m-md-6" data-bs-toggle="tooltip" title="KPMG International">
                            <img src="assets/media/svg/brand-logos/kpmg.svg" class="mh-30px mh-lg-40px" alt="" />
                        </div>
                        <!--end::Client-->
                        <!--begin::Client-->
                        <div class="d-flex flex-center m-3 m-md-6" data-bs-toggle="tooltip" title="Nasa">
                            <img src="assets/media/svg/brand-logos/nasa.svg" class="mh-30px mh-lg-40px" alt="" />
                        </div>
                        <!--end::Client-->
                        <!--begin::Client-->
                        <div class="d-flex flex-center m-3 m-md-6" data-bs-toggle="tooltip" title="Aspnetzero">
                            <img src="assets/media/svg/brand-logos/aspnetzero.svg" class="mh-30px mh-lg-40px" alt="" />
                        </div>
                        <!--end::Client-->
                        <!--begin::Client-->
                        <div class="d-flex flex-center m-3 m-md-6" data-bs-toggle="tooltip" title="AON - Empower Results">
                            <img src="assets/media/svg/brand-logos/aon.svg" class="mh-30px mh-lg-40px" alt="" />
                        </div>
                        <!--end::Client-->
                        <!--begin::Client-->
                        <div class="d-flex flex-center m-3 m-md-6" data-bs-toggle="tooltip" title="Hewlett-Packard">
                            <img src="assets/media/svg/brand-logos/hp-3.svg" class="mh-30px mh-lg-40px" alt="" />
                        </div>
                        <!--end::Client-->
                        <!--begin::Client-->
                        <div class="d-flex flex-center m-3 m-md-6" data-bs-toggle="tooltip" title="Truman">
                            <img src="assets/media/svg/brand-logos/truman.svg" class="mh-30px mh-lg-40px" alt="" />
                        </div>
                        <!--end::Client-->
                    </div>
                    <!--end::Clients-->
                    */ ?>
                </div>
                <!--end::Landing hero-->
            </div>
            <!--end::Wrapper-->
            <!--begin::Curve bottom-->
            <div class="landing-curve landing-dark-color mb-10 mb-lg-20">
                <svg viewBox="15 12 1470 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M0 11C3.93573 11.3356 7.85984 11.6689 11.7725 12H1488.16C1492.1 11.6689 1496.04 11.3356 1500 11V12H1488.16C913.668 60.3476 586.282 60.6117 11.7725 12H0V11Z" fill="currentColor"></path>
                </svg>
            </div>
            <!--end::Curve bottom-->
        </div>
        <!--end::Header Section-->
        <!--begin::How It Works Section-->
        <div class="mb-n10 mb-lg-n20 z-index-2">
            <!--begin::Container-->
            <div class="container">
                <!--begin::Heading-->
                <div class="text-center mb-17">
                    <!--begin::Title-->
                    <h3 class="fs-2hx text-gray-900 mb-5" id="how-it-works" data-kt-scroll-offset="{default: 100, lg: 150}">
                        Como Funciona a Sky Wave Fibra
                    </h3>
                    <!--end::Title-->
                    <!--begin::Text-->
                    <div class="fs-5 text-muted fw-bold">
                        Conectar-se à internet de alta velocidade é simples e rápido.<br />
                        Confira os três passos abaixo:
                    </div>
                    <!--end::Text-->
                </div>
                <!--end::Heading-->
                <!--begin::Row-->
                <div class="row w-100 gy-10 mb-md-20">
                    <!--begin::Col-->
                    <div class="col-md-4 px-5">
                        <!--begin::Story-->
                        <div class="text-center mb-10 mb-md-0">
                            <!--begin::Illustration-->
                            <img src="assets/media/illustrations/sketchy-1/2.png" class="mh-125px mb-9" alt="" />
                            <!--end::Illustration-->
                            <!--begin::Heading-->
                            <div class="d-flex flex-center mb-5">
                                <!--begin::Badge-->
                                <span class="badge badge-circle badge-light-success fw-bold p-5 me-3 fs-3">1</span>
                                <!--end::Badge-->
                                <!--begin::Title-->
                                <div class="fs-5 fs-lg-3 fw-bold text-gray-900">Escolha seu Plano</div>
                                <!--end::Title-->
                            </div>
                            <!--end::Heading-->
                            <!--begin::Description-->
                            <div class="fw-semibold fs-6 fs-lg-4 text-muted">
                                Selecione o plano de internet que melhor atende às suas necessidades.
                            </div>
                            <!--end::Description-->
                        </div>
                        <!--end::Story-->
                    </div>
                    <!--end::Col-->
                    <!--begin::Col-->
                    <div class="col-md-4 px-5">
                        <!--begin::Story-->
                        <div class="text-center mb-10 mb-md-0">
                            <!--begin::Illustration-->
                            <img src="assets/media/illustrations/sketchy-1/8.png" class="mh-125px mb-9" alt="" />
                            <!--end::Illustration-->
                            <!--begin::Heading-->
                            <div class="d-flex flex-center mb-5">
                                <!--begin::Badge-->
                                <span class="badge badge-circle badge-light-success fw-bold p-5 me-3 fs-3">2</span>
                                <!--end::Badge-->
                                <!--begin::Title-->
                                <div class="fs-5 fs-lg-3 fw-bold text-gray-900">Agende a Instalação</div>
                                <!--end::Title-->
                            </div>
                            <!--end::Heading-->
                            <!--begin::Description-->
                            <div class="fw-semibold fs-6 fs-lg-4 text-muted">
                                Nossa equipe vai até você e instala a fibra óptica de forma rápida e sem complicação.
                            </div>
                            <!--end::Description-->
                        </div>
                        <!--end::Story-->
                    </div>
                    <!--end::Col-->
                    <!--begin::Col-->
                    <div class="col-md-4 px-5">
                        <!--begin::Story-->
                        <div class="text-center mb-10 mb-md-0">
                            <!--begin::Illustration-->
                            <img src="assets/media/illustrations/sketchy-1/12.png" class="mh-125px mb-9" alt="" />
                            <!--end::Illustration-->
                            <!--begin::Heading-->
                            <div class="d-flex flex-center mb-5">
                                <!--begin::Badge-->
                                <span class="badge badge-circle badge-light-success fw-bold p-5 me-3 fs-3">3</span>
                                <!--end::Badge-->
                                <!--begin::Title-->
                                <div class="fs-5 fs-lg-3 fw-bold text-gray-900">Navegue sem Limites</div>
                                <!--end::Title-->
                            </div>
                            <!--end::Heading-->
                            <!--begin::Description-->
                            <div class="fw-semibold fs-6 fs-lg-4 text-muted">
                                Conecte-se com ultra velocidade e aproveite a melhor internet da sua região.
                            </div>
                            <!--end::Description-->
                        </div>
                        <!--end::Story-->
                    </div>
                    <!--end::Col-->
                </div>
                <!--end::Row-->
                <!--begin::Product slider-->
                <div class="tns tns-default">
                    <!--begin::Slider-->
                    <div data-tns="true" data-tns-loop="true" data-tns-swipe-angle="false" data-tns-speed="2000" data-tns-autoplay="true" data-tns-autoplay-timeout="18000" data-tns-controls="true" data-tns-nav="false" data-tns-items="1" data-tns-center="false" data-tns-dots="false" data-tns-prev-button="#kt_team_slider_prev1" data-tns-next-button="#kt_team_slider_next1">
                        <!--begin::Item-->
                        <div class="text-center px-5 pt-5 pt-lg-10 px-lg-10">
                            <img src="assets/media/preview/demos/demo1/light-ltr.png" class="card-rounded shadow mh-lg-650px mw-100" alt="" />
                        </div>
                        <!--end::Item-->
                        <!--begin::Item-->
                        <div class="text-center px-5 pt-5 pt-lg-10 px-lg-10">
                            <img src="assets/media/preview/demos/demo2/light-ltr.png" class="card-rounded shadow mh-lg-650px mw-100" alt="" />
                        </div>
                        <!--end::Item-->
                        <!--begin::Item-->
                        <div class="text-center px-5 pt-5 pt-lg-10 px-lg-10">
                            <img src="assets/media/preview/demos/demo4/light-ltr.png" class="card-rounded shadow mh-lg-650px mw-100" alt="" />
                        </div>
                        <!--end::Item-->
                        <!--begin::Item-->
                        <div class="text-center px-5 pt-5 pt-lg-10 px-lg-10">
                            <img src="assets/media/preview/demos/demo5/light-ltr.png" class="card-rounded shadow mh-lg-650px mw-100" alt="" />
                        </div>
                        <!--end::Item-->
                    </div>
                    <!--end::Slider-->
                    <!--begin::Slider button-->
                    <button class="btn btn-icon btn-active-color-primary" id="kt_team_slider_prev1">
                        <i class="ki-outline ki-left fs-2x"></i>
                    </button>
                    <!--end::Slider button-->
                    <!--begin::Slider button-->
                    <button class="btn btn-icon btn-active-color-primary" id="kt_team_slider_next1">
                        <i class="ki-outline ki-right fs-2x"></i>
                    </button>
                    <!--end::Slider button-->
                </div>
                <!--end::Product slider-->
            </div>
            <!--end::Container-->
        </div>
        <!--end::How It Works Section-->
        <!--begin::Statistics Section-->
        <div class="mt-sm-n10">
            <!--begin::Curve top-->
            <div class="landing-curve landing-dark-color">
                <svg viewBox="15 -1 1470 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M1 48C4.93573 47.6644 8.85984 47.3311 12.7725 47H1489.16C1493.1 47.3311 1497.04 47.6644 1501 48V47H1489.16C914.668 -1.34764 587.282 -1.61174 12.7725 47H1V48Z" fill="currentColor"></path>
                </svg>
            </div>
            <!--end::Curve top-->
            <!--begin::Wrapper-->
            <div class="pb-15 pt-18 landing-dark-bg">
                <!--begin::Container-->
                <div class="container">
                    <!--begin::Heading-->
                    <div class="text-center mt-15 mb-18" id="achievements" data-kt-scroll-offset="{default: 100, lg: 150}">
                        <!--begin::Title-->
                        <h3 class="fs-2hx text-white fw-bold mb-5">Conectando Pessoas e Transformando Vidas</h3>
                        <!--end::Title-->
                        <!--begin::Description-->
                        <div class="fs-5 text-gray-700 fw-bold">
                            A Sky Wave Fibra cresce a cada dia levando internet de alta velocidade<br />
                            para lares e empresas da região.
                        </div>
                        <!--end::Description-->
                    </div>
                    <!--end::Heading-->

                    <!--begin::Statistics-->
                    <div class="d-flex flex-center">
                        <div class="d-flex flex-wrap flex-center justify-content-lg-between mb-15 mx-auto w-xl-900px">
                            <!--begin::Item-->
                            <div class="d-flex flex-column flex-center h-200px w-200px h-lg-250px w-lg-250px m-3 
                        bgi-no-repeat bgi-position-center bgi-size-contain"
                                style="background-image: url('assets/media/svg/misc/octagon.svg')">
                                <i class="ki-outline ki-element-11 fs-2tx text-white mb-3"></i>
                                <div class="mb-0">
                                    <div class="fs-lg-2hx fs-2x fw-bold text-white d-flex flex-center">
                                        <div class="min-w-70px" data-kt-countup="true" data-kt-countup-value="10" data-kt-countup-suffix="+">0</div>
                                    </div>
                                    <span class="text-gray-600 fw-semibold fs-5 lh-0">Cidades Atendidas</span>
                                </div>
                            </div>
                            <!--end::Item-->

                            <!--begin::Item-->
                            <div class="d-flex flex-column flex-center h-200px w-200px h-lg-250px w-lg-250px m-3 
                        bgi-no-repeat bgi-position-center bgi-size-contain"
                                style="background-image: url('assets/media/svg/misc/octagon.svg')">
                                <i class="ki-outline ki-chart-pie-4 fs-2tx text-white mb-3"></i>
                                <div class="mb-0">
                                    <div class="fs-lg-2hx fs-2x fw-bold text-white d-flex flex-center">
                                        <div class="min-w-70px" data-kt-countup="true" data-kt-countup-value="5000" data-kt-countup-suffix="+">0</div>
                                    </div>
                                    <span class="text-gray-600 fw-semibold fs-5 lh-0">Clientes Conectados</span>
                                </div>
                            </div>
                            <!--end::Item-->

                            <!--begin::Item-->
                            <div class="d-flex flex-column flex-center h-200px w-200px h-lg-250px w-lg-250px m-3 
                        bgi-no-repeat bgi-position-center bgi-size-contain"
                                style="background-image: url('assets/media/svg/misc/octagon.svg')">
                                <i class="ki-outline ki-basket fs-2tx text-white mb-3"></i>
                                <div class="mb-0">
                                    <div class="fs-lg-2hx fs-2x fw-bold text-white d-flex flex-center">
                                        <div class="min-w-70px" data-kt-countup="true" data-kt-countup-value="1000" data-kt-countup-suffix="Mb+">0</div>
                                    </div>
                                    <span class="text-gray-600 fw-semibold fs-5 lh-0">Velocidade de Conexão</span>
                                </div>
                            </div>
                            <!--end::Item-->
                        </div>
                    </div>
                    <!--end::Statistics-->

                    <!--begin::Testimonial-->
                    <div class="fs-2 fw-semibold text-muted text-center mb-3">
                        <span class="fs-1 lh-1 text-gray-700">“</span>
                        Nossa missão é levar conexão de qualidade e confiança para cada cliente.
                        <span class="fs-1 lh-1 text-gray-700">“</span>
                    </div>
                    <!--end::Testimonial-->

                    <!--begin::Author-->
                    <div class="fs-2 fw-semibold text-muted text-center">
                        <span class="fs-4 fw-bold text-gray-600">Equipe Sky Wave Fibra</span>
                    </div>
                    <!--end::Author-->
                </div>

                <!--end::Container-->
            </div>
            <!--end::Wrapper-->
            <!--begin::Curve bottom-->
            <div class="landing-curve landing-dark-color">
                <svg viewBox="15 12 1470 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M0 11C3.93573 11.3356 7.85984 11.6689 11.7725 12H1488.16C1492.1 11.6689 1496.04 11.3356 1500 11V12H1488.16C913.668 60.3476 586.282 60.6117 11.7725 12H0V11Z" fill="currentColor"></path>
                </svg>
            </div>
            <!--end::Curve bottom-->
        </div>
        <!--end::Statistics Section-->
        <!--begin::Team Section-->
        <div class="py-10 py-lg-20">
            <!--begin::Container-->
            <div class="container">
                <!--begin::Heading-->
                <div class="text-center mb-12">
                    <!--begin::Title-->
                    <h3 class="fs-2hx text-gray-900 mb-5" id="team" data-kt-scroll-offset="{default: 100, lg: 150}">
                        Nosso Time de Especialistas
                    </h3>
                    <!--end::Title-->

                    <!--begin::Sub-title-->
                    <div class="fs-5 text-muted fw-bold">
                        A Sky Wave Fibra conta com uma equipe dedicada e preparada<br />
                        para garantir a melhor experiência de conexão e atendimento a você.
                    </div>
                    <!--end::Sub-title-->

                </div>
                <!--end::Heading-->
                <!--begin::Slider-->
                <div class="tns tns-default" style="direction: ltr">
                    <!--begin::Wrapper-->
                    <div data-tns="true" data-tns-loop="true" data-tns-swipe-angle="false" data-tns-speed="2000" data-tns-autoplay="true" data-tns-autoplay-timeout="18000" data-tns-controls="true" data-tns-nav="false" data-tns-items="1" data-tns-center="false" data-tns-dots="false" data-tns-prev-button="#kt_team_slider_prev" data-tns-next-button="#kt_team_slider_next" data-tns-responsive="{1200: {items: 3}, 992: {items: 2}}">
                        <!--begin::Item-->
                        <div class="text-center">
                            <!--begin::Photo-->
                            <div class="octagon mx-auto mb-5 d-flex w-200px h-200px bgi-no-repeat bgi-size-contain bgi-position-center" style="background-image:url('assets/media/avatars/300-1.jpg')"></div>
                            <!--end::Photo-->
                            <!--begin::Person-->
                            <div class="mb-0">
                                <!--begin::Name-->
                                <a href="#" class="text-gray-900 fw-bold text-hover-primary fs-3">Paul Miles</a>
                                <!--end::Name-->
                                <!--begin::Position-->
                                <div class="text-muted fs-6 fw-semibold mt-1">Development Lead</div>
                                <!--begin::Position-->
                            </div>
                            <!--end::Person-->
                        </div>
                        <!--end::Item-->
                        <!--begin::Item-->
                        <div class="text-center">
                            <!--begin::Photo-->
                            <div class="octagon mx-auto mb-5 d-flex w-200px h-200px bgi-no-repeat bgi-size-contain bgi-position-center" style="background-image:url('assets/media/avatars/300-2.jpg')"></div>
                            <!--end::Photo-->
                            <!--begin::Person-->
                            <div class="mb-0">
                                <!--begin::Name-->
                                <a href="#" class="text-gray-900 fw-bold text-hover-primary fs-3">Melisa Marcus</a>
                                <!--end::Name-->
                                <!--begin::Position-->
                                <div class="text-muted fs-6 fw-semibold mt-1">Creative Director</div>
                                <!--begin::Position-->
                            </div>
                            <!--end::Person-->
                        </div>
                        <!--end::Item-->
                        <!--begin::Item-->
                        <div class="text-center">
                            <!--begin::Photo-->
                            <div class="octagon mx-auto mb-5 d-flex w-200px h-200px bgi-no-repeat bgi-size-contain bgi-position-center" style="background-image:url('assets/media/avatars/300-5.jpg')"></div>
                            <!--end::Photo-->
                            <!--begin::Person-->
                            <div class="mb-0">
                                <!--begin::Name-->
                                <a href="#" class="text-gray-900 fw-bold text-hover-primary fs-3">David Nilson</a>
                                <!--end::Name-->
                                <!--begin::Position-->
                                <div class="text-muted fs-6 fw-semibold mt-1">Python Expert</div>
                                <!--begin::Position-->
                            </div>
                            <!--end::Person-->
                        </div>
                        <!--end::Item-->
                        <!--begin::Item-->
                        <div class="text-center">
                            <!--begin::Photo-->
                            <div class="octagon mx-auto mb-5 d-flex w-200px h-200px bgi-no-repeat bgi-size-contain bgi-position-center" style="background-image:url('assets/media/avatars/300-20.jpg')"></div>
                            <!--end::Photo-->
                            <!--begin::Person-->
                            <div class="mb-0">
                                <!--begin::Name-->
                                <a href="#" class="text-gray-900 fw-bold text-hover-primary fs-3">Anne Clarc</a>
                                <!--end::Name-->
                                <!--begin::Position-->
                                <div class="text-muted fs-6 fw-semibold mt-1">Project Manager</div>
                                <!--begin::Position-->
                            </div>
                            <!--end::Person-->
                        </div>
                        <!--end::Item-->
                        <!--begin::Item-->
                        <div class="text-center">
                            <!--begin::Photo-->
                            <div class="octagon mx-auto mb-5 d-flex w-200px h-200px bgi-no-repeat bgi-size-contain bgi-position-center" style="background-image:url('assets/media/avatars/300-23.jpg')"></div>
                            <!--end::Photo-->
                            <!--begin::Person-->
                            <div class="mb-0">
                                <!--begin::Name-->
                                <a href="#" class="text-gray-900 fw-bold text-hover-primary fs-3">Ricky Hunt</a>
                                <!--end::Name-->
                                <!--begin::Position-->
                                <div class="text-muted fs-6 fw-semibold mt-1">Art Director</div>
                                <!--begin::Position-->
                            </div>
                            <!--end::Person-->
                        </div>
                        <!--end::Item-->
                        <!--begin::Item-->
                        <div class="text-center">
                            <!--begin::Photo-->
                            <div class="octagon mx-auto mb-5 d-flex w-200px h-200px bgi-no-repeat bgi-size-contain bgi-position-center" style="background-image:url('assets/media/avatars/300-12.jpg')"></div>
                            <!--end::Photo-->
                            <!--begin::Person-->
                            <div class="mb-0">
                                <!--begin::Name-->
                                <a href="#" class="text-gray-900 fw-bold text-hover-primary fs-3">Alice Wayde</a>
                                <!--end::Name-->
                                <!--begin::Position-->
                                <div class="text-muted fs-6 fw-semibold mt-1">Marketing Manager</div>
                                <!--begin::Position-->
                            </div>
                            <!--end::Person-->
                        </div>
                        <!--end::Item-->
                        <!--begin::Item-->
                        <div class="text-center">
                            <!--begin::Photo-->
                            <div class="octagon mx-auto mb-5 d-flex w-200px h-200px bgi-no-repeat bgi-size-contain bgi-position-center" style="background-image:url('assets/media/avatars/300-9.jpg')"></div>
                            <!--end::Photo-->
                            <!--begin::Person-->
                            <div class="mb-0">
                                <!--begin::Name-->
                                <a href="#" class="text-gray-900 fw-bold text-hover-primary fs-3">Carles Puyol</a>
                                <!--end::Name-->
                                <!--begin::Position-->
                                <div class="text-muted fs-6 fw-semibold mt-1">QA Managers</div>
                                <!--begin::Position-->
                            </div>
                            <!--end::Person-->
                        </div>
                        <!--end::Item-->
                    </div>
                    <!--end::Wrapper-->
                    <!--begin::Button-->
                    <button class="btn btn-icon btn-active-color-primary" id="kt_team_slider_prev">
                        <i class="ki-outline ki-left fs-2x"></i>
                    </button>
                    <!--end::Button-->
                    <!--begin::Button-->
                    <button class="btn btn-icon btn-active-color-primary" id="kt_team_slider_next">
                        <i class="ki-outline ki-right fs-2x"></i>
                    </button>
                    <!--end::Button-->
                </div>
                <!--end::Slider-->
            </div>
            <!--end::Container-->
        </div>
        <!--end::Team Section-->
        <!--begin::Pricing Section-->
        <div class="mt-sm-n20">
            <!--begin::Curve top-->
            <div class="landing-curve landing-dark-color">
                <svg viewBox="15 -1 1470 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M1 48C4.93573 47.6644 8.85984 47.3311 12.7725 47H1489.16C1493.1 47.3311 1497.04 47.6644 1501 48V47H1489.16C914.668 -1.34764 587.282 -1.61174 12.7725 47H1V48Z" fill="currentColor"></path>
                </svg>
            </div>
            <!--end::Curve top-->
            <!--begin::Wrapper-->
            <div class="py-20 landing-dark-bg">
                <!--begin::Container-->
                <div class="container">
                    <!--begin::Plans-->
                    <div class="d-flex flex-column container pt-lg-20">
                        <!--begin::Heading-->
                        <div class="mb-13 text-center">
                            <h1 class="fs-2hx fw-bold text-white mb-5" id="pricing" data-kt-scroll-offset="{default: 100, lg: 150}">
                                Nossos Planos de Internet
                            </h1>
                            <div class="text-gray-600 fw-semibold fs-5">
                                Escolha o plano ideal para sua casa ou empresa<br />
                                e navegue com a qualidade da Sky Wave Fibra.
                            </div>
                        </div>
                        <!--end::Heading-->

                        <!--begin::Pricing-->
                        <div class="text-center" id="kt_pricing">
                            <!--begin::Row-->
                            <div class="row g-10">
                                <!--begin::Col-->
                                <div class="col-xl-4">
                                    <div class="d-flex h-100 align-items-center">
                                        <!--begin::Option-->
                                        <div class="w-100 d-flex flex-column flex-center rounded-3 bg-body py-15 px-10">
                                            <!--begin::Heading-->
                                            <div class="mb-7 text-center">
                                                <h1 class="text-gray-900 mb-5 fw-boldest">Plano 100Mb</h1>
                                                <div class="text-gray-500 fw-semibold mb-5">Ideal para uso diário</div>
                                                <div class="text-center">
                                                    <span class="mb-2 text-primary">R$</span>
                                                    <span class="fs-3x fw-bold text-primary" data-kt-plan-price-month="89">55</span>
                                                    <span class="fs-7 fw-semibold opacity-50">/ mês</span>
                                                </div>
                                            </div>
                                            <!--end::Heading-->
                                            <!--begin::Features-->
                                            <div class="w-100 mb-10">
                                                <div class="d-flex flex-stack mb-5">
                                                    <span class="fw-semibold fs-6 text-gray-800 text-start pe-3">100 Mega de Velocidade</span>
                                                    <i class="ki-outline ki-check-circle fs-1 text-success"></i>
                                                </div>
                                                <div class="d-flex flex-stack mb-5">
                                                    <span class="fw-semibold fs-6 text-gray-800 text-start pe-3">Wi-Fi incluso</span>
                                                    <i class="ki-outline ki-check-circle fs-1 text-success"></i>
                                                </div>
                                                <div class="d-flex flex-stack">
                                                    <span class="fw-semibold fs-6 text-gray-800">Suporte 24h</span>
                                                    <i class="ki-outline ki-check-circle fs-1 text-success"></i>
                                                </div>
                                            </div>
                                            <!--end::Features-->
                                            <a href="<?= url("entrar") ?>" class="btn btn-primary">Assinar</a>
                                        </div>
                                        <!--end::Option-->
                                    </div>
                                </div>
                                <!--end::Col-->

                                <!--begin::Col-->
                                <div class="col-xl-4">
                                    <div class="d-flex h-100 align-items-center">
                                        <div class="w-100 d-flex flex-column flex-center rounded-3 bg-primary py-20 px-10">
                                            <div class="mb-7 text-center">
                                                <h1 class="text-white mb-5 fw-boldest">Plano 500Mb</h1>
                                                <div class="text-white opacity-75 fw-semibold mb-5">Perfeito para vários dispositivos</div>
                                                <div class="text-center">
                                                    <span class="mb-2 text-white">R$</span>
                                                    <span class="fs-3x fw-bold text-white" data-kt-plan-price-month="129">75</span>
                                                    <span class="fs-7 fw-semibold text-white opacity-75">/ mês</span>
                                                </div>
                                            </div>
                                            <div class="w-100 mb-10">
                                                <div class="d-flex flex-stack mb-5">
                                                    <span class="fw-semibold fs-6 text-white opacity-75 text-start pe-3">500 Mega de Velocidade</span>
                                                    <i class="ki-outline ki-check-circle fs-1 text-white"></i>
                                                </div>
                                                <div class="d-flex flex-stack mb-5">
                                                    <span class="fw-semibold fs-6 text-white opacity-75 text-start pe-3">Wi-Fi de alta performance</span>
                                                    <i class="ki-outline ki-check-circle fs-1 text-white"></i>
                                                </div>
                                                <div class="d-flex flex-stack">
                                                    <span class="fw-semibold fs-6 text-white opacity-75">Suporte técnico prioritário</span>
                                                    <i class="ki-outline ki-check-circle fs-1 text-white"></i>
                                                </div>
                                            </div>
                                            <a href="<?= url("entrar") ?>" class="btn btn-light">Assinar</a>
                                        </div>
                                    </div>
                                </div>
                                <!--end::Col-->

                                <!--begin::Col-->
                                <div class="col-xl-4">
                                    <div class="d-flex h-100 align-items-center">
                                        <div class="w-100 d-flex flex-column flex-center rounded-3 bg-body py-15 px-10">
                                            <div class="mb-7 text-center">
                                                <h1 class="text-gray-900 mb-5 fw-boldest">Plano 700Mb</h1>
                                                <div class="text-gray-500 fw-semibold mb-5">Máxima performance</div>
                                                <div class="text-center">
                                                    <span class="mb-2 text-primary">R$</span>
                                                    <span class="fs-3x fw-bold text-primary" data-kt-plan-price-month="199">100</span>
                                                    <span class="fs-7 fw-semibold opacity-50">/ mês</span>
                                                </div>
                                            </div>
                                            <div class="w-100 mb-10">
                                                <div class="d-flex flex-stack mb-5">
                                                    <span class="fw-semibold fs-6 text-gray-800 text-start pe-3">700 Mega de Velocidade</span>
                                                    <i class="ki-outline ki-check-circle fs-1 text-success"></i>
                                                </div>
                                                <div class="d-flex flex-stack mb-5">
                                                    <span class="fw-semibold fs-6 text-gray-800 text-start pe-3">Wi-Fi avançado incluso</span>
                                                    <i class="ki-outline ki-check-circle fs-1 text-success"></i>
                                                </div>
                                                <div class="d-flex flex-stack">
                                                    <span class="fw-semibold fs-6 text-gray-800">Atendimento VIP</span>
                                                    <i class="ki-outline ki-check-circle fs-1 text-success"></i>
                                                </div>
                                            </div>
                                            <a href="<?= url("entrar") ?>" class="btn btn-primary">Assinar</a>
                                        </div>
                                    </div>
                                </div>
                                <!--end::Col-->
                            </div>
                            <!--end::Row-->
                        </div>
                        <!--end::Pricing-->
                    </div>
                    <!--end::Plans-->
                </div>

                <!--end::Container-->
            </div>
            <!--end::Wrapper-->
            <!--begin::Curve bottom-->
            <div class="landing-curve landing-dark-color">
                <svg viewBox="15 12 1470 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M0 11C3.93573 11.3356 7.85984 11.6689 11.7725 12H1488.16C1492.1 11.6689 1496.04 11.3356 1500 11V12H1488.16C913.668 60.3476 586.282 60.6117 11.7725 12H0V11Z" fill="currentColor"></path>
                </svg>
            </div>
            <!--end::Curve bottom-->
        </div>
        <!--end::Pricing Section-->
        <!--begin::Testimonials Section-->
        <div class="mt-20 mb-n20 position-relative z-index-2" id="clientsgti">
            <!--begin::Container-->
            <div class="container">
                <!--begin::Heading-->
                <div class="text-center mb-17">
                    <!--begin::Title-->
                    <h3 class="fs-2hx text-gray-900 mb-5" data-kt-scroll-offset="{default: 125, lg: 150}">
                        O que Nossos Clientes Dizem
                    </h3>
                    <!--end::Title-->
                    <!--begin::Description-->
                    <div class="fs-5 text-muted fw-bold">
                        A satisfação dos nossos clientes é a nossa maior conquista.<br />
                        Veja alguns depoimentos reais de quem já tem a Sky Wave Fibra.
                    </div>
                    <!--end::Description-->
                </div>
                <!--end::Heading-->

                <!--begin::Row-->
                <div class="row g-lg-10 mb-10 mb-lg-20">
                    <!--begin::Col-->
                    <div class="col-lg-4">
                        <div class="d-flex flex-column justify-content-between h-lg-100 px-10 px-lg-0 pe-lg-10 mb-15 mb-lg-0">
                            <div class="mb-7">
                                <!--begin::Rating-->
                                <div class="rating mb-6">
                                    <div class="rating-label me-2 checked"><i class="ki-outline ki-star fs-5"></i></div>
                                    <div class="rating-label me-2 checked"><i class="ki-outline ki-star fs-5"></i></div>
                                    <div class="rating-label me-2 checked"><i class="ki-outline ki-star fs-5"></i></div>
                                    <div class="rating-label me-2 checked"><i class="ki-outline ki-star fs-5"></i></div>
                                    <div class="rating-label me-2 checked"><i class="ki-outline ki-star fs-5"></i></div>
                                </div>
                                <!--end::Rating-->
                                <div class="fs-2 fw-bold text-gray-900 mb-3">
                                    Internet rápida e estável
                                </div>
                                <div class="text-gray-500 fw-semibold fs-4">
                                    “Desde que contratei a Sky Wave Fibra, nunca mais tive problemas com quedas de conexão.
                                    A internet é muito rápida e confiável.”
                                </div>
                            </div>
                            <!--begin::Author-->
                            <div class="d-flex align-items-center">
                                <div class="symbol symbol-circle symbol-50px me-5">
                                    <img src="assets/media/avatars/300-1.jpg" alt="" />
                                </div>
                                <div class="flex-grow-1">
                                    <a href="#" class="text-gray-900 fw-bold text-hover-primary fs-6">Carlos Andrade</a>
                                    <span class="text-muted d-block fw-bold">Cliente Residencial</span>
                                </div>
                            </div>
                            <!--end::Author-->
                        </div>
                    </div>
                    <!--end::Col-->

                    <!--begin::Col-->
                    <div class="col-lg-4">
                        <div class="d-flex flex-column justify-content-between h-lg-100 px-10 px-lg-0 pe-lg-10 mb-15 mb-lg-0">
                            <div class="mb-7">
                                <div class="rating mb-6">
                                    <div class="rating-label me-2 checked"><i class="ki-outline ki-star fs-5"></i></div>
                                    <div class="rating-label me-2 checked"><i class="ki-outline ki-star fs-5"></i></div>
                                    <div class="rating-label me-2 checked"><i class="ki-outline ki-star fs-5"></i></div>
                                    <div class="rating-label me-2 checked"><i class="ki-outline ki-star fs-5"></i></div>
                                    <div class="rating-label me-2 checked"><i class="ki-outline ki-star fs-5"></i></div>
                                </div>
                                <div class="fs-2 fw-bold text-gray-900 mb-3">
                                    Atendimento excelente
                                </div>
                                <div class="text-gray-500 fw-semibold fs-4">
                                    “Precisei de suporte técnico e fui atendida em poucos minutos.
                                    Equipe atenciosa e preparada. Recomendo muito!”
                                </div>
                            </div>
                            <div class="d-flex align-items-center">
                                <div class="symbol symbol-circle symbol-50px me-5">
                                    <img src="assets/media/avatars/300-2.jpg" alt="" />
                                </div>
                                <div class="flex-grow-1">
                                    <a href="#" class="text-gray-900 fw-bold text-hover-primary fs-6">Fernanda Lima</a>
                                    <span class="text-muted d-block fw-bold">Cliente Comercial</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--end::Col-->

                    <!--begin::Col-->
                    <div class="col-lg-4">
                        <div class="d-flex flex-column justify-content-between h-lg-100 px-10 px-lg-0 pe-lg-10 mb-15 mb-lg-0">
                            <div class="mb-7">
                                <div class="rating mb-6">
                                    <div class="rating-label me-2 checked"><i class="ki-outline ki-star fs-5"></i></div>
                                    <div class="rating-label me-2 checked"><i class="ki-outline ki-star fs-5"></i></div>
                                    <div class="rating-label me-2 checked"><i class="ki-outline ki-star fs-5"></i></div>
                                    <div class="rating-label me-2 checked"><i class="ki-outline ki-star fs-5"></i></div>
                                    <div class="rating-label me-2 checked"><i class="ki-outline ki-star fs-5"></i></div>
                                </div>
                                <div class="fs-2 fw-bold text-gray-900 mb-3">
                                    Melhor custo-benefício
                                </div>
                                <div class="text-gray-500 fw-semibold fs-4">
                                    “Já tive outros provedores, mas a Sky Wave Fibra tem o melhor equilíbrio entre preço e qualidade.
                                    Estou muito satisfeito.”
                                </div>
                            </div>
                            <div class="d-flex align-items-center">
                                <div class="symbol symbol-circle symbol-50px me-5">
                                    <img src="assets/media/avatars/300-16.jpg" alt="" />
                                </div>
                                <div class="flex-grow-1">
                                    <a href="#" class="text-gray-900 fw-bold text-hover-primary fs-6">Marcos Oliveira</a>
                                    <span class="text-muted d-block fw-bold">Cliente Empresarial</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--end::Col-->
                </div>
                <!--end::Row-->

                <!--begin::Highlight-->
                <div class="d-flex flex-stack flex-wrap flex-md-nowrap card-rounded shadow p-8 p-lg-12 mb-n5 mb-lg-n13"
                    style="background: linear-gradient(90deg, #20AA3E 0%, #03A588 100%);">
                    <div class="my-2 me-5">
                        <div class="fs-1 fs-lg-2qx fw-bold text-white mb-2">
                            Venha para a Sky Wave Fibra,
                            <span class="fw-normal">e tenha a melhor conexão!</span>
                        </div>
                        <div class="fs-6 fs-lg-5 text-white fw-semibold opacity-75">
                            Milhares de clientes já estão conectados. E você, vai ficar de fora?
                        </div>
                    </div>
                    <a href="#planos" class="btn btn-lg btn-outline border-2 btn-outline-white flex-shrink-0 my-2">
                        Conheça nossos planos
                    </a>
                </div>
                <!--end::Highlight-->
            </div>
            <!--end::Container-->
        </div>
        <!--end::Testimonials Section-->
        <!--begin::Footer Section-->
        <div class="mb-0">
            <!--begin::Curve top-->
            <div class="landing-curve landing-dark-color">
                <svg viewBox="15 -1 1470 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M1 48C4.93573 47.6644 8.85984 47.3311 12.7725 47H1489.16C1493.1 47.3311 1497.04 47.6644 1501 48V47H1489.16C914.668 -1.34764 587.282 -1.61174 12.7725 47H1V48Z" fill="currentColor"></path>
                </svg>
            </div>
            <!--end::Curve top-->
            <!--begin::Wrapper-->
            <div class="landing-dark-bg pt-20">
                <!--begin::Container-->
                <div class="container">
                    <!--begin::Row-->
                    <div class="row py-10 py-lg-20">
                        <!--begin::Col-->
                        <div class="col-lg-6 pe-lg-16 mb-10 mb-lg-0">
                            <!--begin::Block-->
                            <div class="rounded landing-dark-border p-9 mb-10">
                                <!--begin::Title-->
                                <h2 class="text-white" id="contactgti">Precisa de Suporte Técnico?</h2>
                                <!--end::Title-->
                                <!--begin::Text-->
                                <span class="fw-normal fs-4 text-gray-700">
                                    Fale com nossa equipe pelo e-mail:
                                    <a href="mailto:suporte@skywavefibra.com.br" class="text-white opacity-50 text-hover-primary">
                                        suporte@skywavefibra.com.br
                                    </a>
                                </span>
                                <!--end::Text-->
                            </div>
                            <!--end::Block-->

                            <!--begin::Block-->
                            <div class="rounded landing-dark-border p-9">
                                <!--begin::Title-->
                                <h2 class="text-white">Atendimento Comercial</h2>
                                <!--end::Title-->
                                <!--begin::Text-->
                                <span class="fw-normal fs-4 text-gray-700">
                                    Quer contratar um plano? Entre em contato:
                                    <a href="mailto:contato@skywavefibra.com.br" class="text-white opacity-50 text-hover-primary">
                                        contato@skywavefibra.com.br
                                    </a>
                                </span>
                                <!--end::Text-->
                            </div>
                            <!--end::Block-->
                        </div>
                        <!--end::Col-->

                        <!--begin::Col-->
                        <div class="col-lg-6 ps-lg-16">
                            <!--begin::Navs-->
                            <div class="d-flex justify-content-center">
                                <!--begin::Links-->
                                <div class="d-flex fw-semibold flex-column me-20">
                                    <!--begin::Subtitle-->
                                    <h4 class="fw-bold text-gray-500 mb-6">Links Úteis</h4>
                                    <!--end::Subtitle-->
                                    <a href="#planos" class="text-white opacity-50 text-hover-primary fs-5 mb-6">Nossos Planos</a>
                                    <a href="#clients" class="text-white opacity-50 text-hover-primary fs-5 mb-6">Depoimentos</a>
                                    <a href="#contato" class="text-white opacity-50 text-hover-primary fs-5">Área do Cliente</a>
                                </div>
                                <!--end::Links-->

                                <!--begin::Links-->
                                <div class="d-flex fw-semibold flex-column ms-lg-20">
                                    <h4 class="fw-bold text-gray-500 mb-6">Siga a Sky Wave</h4>

                                    <a href="#" class="mb-6">
                                        <img src="assets/media/svg/brand-logos/facebook-4.svg" class="h-20px me-2" alt="Facebook" />
                                        <span class="text-white opacity-50 text-hover-primary fs-5">Facebook</span>
                                    </a>
                                    <a href="#" class="mb-6">
                                        <img src="assets/media/svg/brand-logos/instagram-2-1.svg" class="h-20px me-2" alt="Instagram" />
                                        <span class="text-white opacity-50 text-hover-primary fs-5">Instagram</span>
                                    </a>
                                    <a href="#" class="mb-6">
                                        <img src="assets/media/svg/brand-logos/twitter.svg" class="h-20px me-2" alt="Twitter" />
                                        <span class="text-white opacity-50 text-hover-primary fs-5">Twitter</span>
                                    </a>
                                    <a href="#" class="mb-6">
                                        <img src="../../assets/images/front/whatsapp.svg" class="h-20px me-2" alt="WhatsApp" />
                                        <span class="text-white opacity-50 text-hover-primary fs-5">WhatsApp</span>
                                    </a>
                                </div>
                                <!--end::Links-->
                            </div>
                            <!--end::Navs-->
                        </div>
                        <!--end::Col-->
                    </div>
                    <!--end::Row-->
                </div>

                <!--end::Container-->
                <!--begin::Separator-->
                <div class="landing-dark-separator"></div>
                <!--end::Separator-->
                <!--begin::Container-->
                <div class="container">
                    <!--begin::Wrapper-->
                    <div class="d-flex flex-column flex-md-row flex-stack py-7 py-lg-10">
                        <!--begin::Copyright-->
                        <div class="d-flex align-items-center order-2 order-md-1">
                            <!--begin::Logo-->
                            <a href="index.html">
                                <img alt="Sky Wave Fibra" src="../../assets/images/front/header.png" class="h-60px h-md-60px rounded" />
                            </a>
                            <!--end::Logo-->
                            <span class="mx-5 fs-6 fw-semibold text-gray-600 pt-1">
                                &copy; 2025 Sky Wave Fibra - CNPJ 00.000.000/0001-00
                            </span>
                        </div>
                        <!--end::Copyright-->

                        <!--begin::Menu-->
                        <ul class="menu menu-gray-600 menu-hover-primary fw-semibold fs-6 fs-md-5 order-1 mb-5 mb-md-0">
                            <li class="menu-item">
                                <a href="#planos" class="menu-link px-2">Planos</a>
                            </li>
                            <li class="menu-item">
                                <a href="#contato" class="menu-link px-2">Contato</a>
                            </li>
                        </ul>
                        <!--end::Menu-->
                    </div>
                    <!--end::Wrapper-->
                </div>

                <!--end::Container-->
            </div>
            <!--end::Wrapper-->
        </div>
        <!--end::Footer Section-->
        <!--begin::Scrolltop-->
        <div id="kt_scrolltop" class="scrolltop" data-kt-scrolltop="true">
            <i class="ki-outline ki-arrow-up"></i>
        </div>
        <!--end::Scrolltop-->
    </div>
    <!--end::Root-->
    <!--begin::Scrolltop-->
    <div id="kt_scrolltop" class="scrolltop" data-kt-scrolltop="true">
        <i class="ki-outline ki-arrow-up"></i>
    </div>
    <!--end::Scrolltop-->
    <!--begin::Javascript-->
    <script>
        var hostUrl = "assets/";
    </script>
    <!--begin::Global Javascript Bundle(mandatory for all pages)-->
    <script src="assets/plugins/global/plugins.bundle.js"></script>
    <script src="assets/js/scripts.bundle.js"></script>
    <!--end::Global Javascript Bundle-->
    <!--begin::Vendors Javascript(used for this page only)-->
    <script src="assets/plugins/custom/fslightbox/fslightbox.bundle.js"></script>
    <script src="assets/plugins/custom/typedjs/typedjs.bundle.js"></script>
    <!--end::Vendors Javascript-->
    <!--begin::Custom Javascript(used for this page only)-->
    <script src="assets/js/custom/landing.js"></script>
    <script src="assets/js/custom/pages/pricing/general.js"></script>
    <!--end::Custom Javascript-->
    <!--end::Javascript-->

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Pega todos os links que possuem href começando com #
            const links = document.querySelectorAll('a[href^="#"]');

            links.forEach(link => {
                link.addEventListener("click", function(e) {
                    e.preventDefault();

                    const targetId = this.getAttribute("href").substring(1);
                    const targetElement = document.getElementById(targetId);

                    if (targetElement) {
                        window.scrollTo({
                            top: targetElement.offsetTop - 80, // ajuste caso tenha header fixo
                            behavior: "smooth"
                        });
                    }
                });
            });
        });
    </script>



</body>
<!--end::Body-->

</html>