<!DOCTYPE html>
<html lang="pt-BR">

<!--begin::Head-->

<head>
    <base href="<?= url(CONF_THEME) ?>/" />

    <?= $head ?>

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

<body id="kt_body" class="app-blank bgi-size-cover bgi-attachment-fixed bgi-position-center bgi-no-repeat" data-kt-app-page-loading-enabled="true" data-kt-app-page-loading="on">

    <!--begin::Page loading(append to body)-->
    <div class="page-loader flex-column bg-dark bg-opacity-25">
        <span class="spinner-border text-primary" role="status"></span>
        <span class="text-gray-800 fs-6 fw-semibold mt-5"></span>
    </div>
    <!--end::Page loading-->

    <div id="url-global" data-url="<?= url() ?>"></div>
    <div id="alert-flash" class="d-none"><?= flash() ?></div>
    <div id="alert-container-toast" class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 999999 !important;"></div>

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
        <!--begin::Page bg image-->
        <style>
            body {
                background-image: url('assets/media/auth/bg10.jpeg');
            }

            [data-bs-theme="dark"] body {
                background-image: url('assets/media/auth/bg10-dark.jpeg');
            }
        </style>
        <!--end::Page bg image-->

        <!--begin::Authentication - Sign-in -->
        <div class="d-flex flex-column flex-lg-row flex-column-fluid">
            <!--begin::Aside (lado esquerdo com imagem e texto)-->
            <div class="d-flex flex-lg-row-fluid">
                <div class="d-flex flex-column flex-center pb-0 pb-lg-10 p-10 w-100">
                    <img class="theme-light-show mx-auto mw-100 w-150px w-lg-300px mb-10 mb-lg-20"
                        src="../../assets/images/auth/auth.png" alt="" />
                    <img class="theme-dark-show mx-auto mw-100 w-150px w-lg-300px mb-10 mb-lg-20"
                        src="../../assets/images/auth/auth.png" alt="" />
                    <div class="text-gray-600 fs-base text-center fw-semibold">
                        Faça tudo a partir do seu painel de controle.
                    </div>
                    <div class="text-center mt-10">
                        <a href="<?= url() ?>"
                            class="btn btn-primary px-10 py-4 fs-4 fw-bold rounded-pill shadow-sm"
                            style="
                                background: linear-gradient(90deg, #009EF7, #3A8DFF);
                                border: none;
                                transition: all .3s ease;
                            "
                            onmouseover="this.style.transform='scale(1.04)'"
                            onmouseout="this.style.transform='scale(1)'">
                            <i class="ki-outline ki-rocket fs-2 me-2"></i>
                            Página Inicial
                        </a>
                    </div>

                </div>
            </div>
            <!--end::Aside-->

            <!--begin::Body (lado direito com o formulário)-->
            <div class="d-flex flex-column-fluid flex-lg-row-auto justify-content-center justify-content-lg-end p-12">

                <?= $this->section("content"); ?>

            </div>
            <!--end::Body-->
        </div>
        <!--end::Authentication - Sign-in-->
    </div>
    <!--end::Root-->

    <!--begin::Javascript-->
    <script>
        var hostUrl = "assets/";
    </script>

    <!--begin::Global Javascript Bundle(mandatory for all pages)-->
    <script src="assets/plugins/global/plugins.bundle.js"></script>
    <script src="assets/js/scripts.bundle.js"></script>
    <!--end::Global Javascript Bundle-->

    <!--begin::Custom Javascript(used for this page only)-->
    <?= $this->section("scripts") ?>
    <!-- <script src="assets/js/custom/authentication/sign-in/general.js"></script> -->
    <script src="../../assets/js/custom.js"></script>
    <!--end::Custom Javascript-->

    <!--end::Javascript-->
</body>
<!--end::Body-->

</html>