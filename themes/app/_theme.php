<?php

use Source\Models\Auth;
use Source\Models\App\Plan; // <--- AQUI ESTÁ A CORREÇÃO

// Busca todos os planos do banco, ordenados por preço, para o modal
$modal_plans = (new Plan())->find()->order("price ASC")->fetch(true);
?>
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
	<!--begin::Vendor Stylesheets(used for this page only)-->
	<link href="assets/plugins/custom/fullcalendar/fullcalendar.bundle.css" rel="stylesheet" type="text/css" />
	<!-- Carrega o DataTables CSS principal de um CDN -->
	<link href="https://cdn.datatables.net/1.13.8/css/jquery.dataTables.min.css" rel="stylesheet" type="text/css" />
	<!--end::Vendor Stylesheets-->
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

<body id="kt_app_body" data-kt-app-header-fixed-mobile="true" data-kt-app-toolbar-enabled="true" class="app-default">

	<!-- Adicionado jQuery explicitamente para garantir o carregamento antes de outros scripts -->
	<script src="<?= url("/shared/assets/libs/jquery/dist/jquery.min.js") ?>"></script>

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
	<!--begin::App-->
	<div class="d-flex flex-column flex-root app-root" id="kt_app_root">
		<!--begin::Page-->
		<div class="app-page flex-column flex-column-fluid" id="kt_app_page">
			<!--begin::Header-->
			<div id="kt_app_header" class="app-header" data-kt-sticky="true" data-kt-sticky-activate="{default: false, lg: true}" data-kt-sticky-name="app-header-sticky" data-kt-sticky-offset="{default: false, lg: '300px'}">
				<!--begin::Header container-->
				<div class="app-container container-xxl d-flex align-items-stretch justify-content-between" id="kt_app_header_container">
					<!--begin::Header mobile toggle-->
					<div class="d-flex align-items-center d-lg-none ms-n2 me-2" title="Show sidebar menu">
						<div class="btn btn-icon btn-active-color-primary w-35px h-35px" id="kt_app_header_menu_toggle">
							<i class="ki-outline ki-abstract-14 fs-2"></i>
						</div>
					</div>
					<!--end::Header mobile toggle-->
					<!--begin::Logo-->
					<div class="d-flex align-items-center flex-grow-1 flex-lg-grow-1 me-lg-13">
						<a href="<?= url("app") ?>">
							<img alt="Logo" src="../../assets/images/front/headerdark.png" class="h-20px h-lg-60px theme-light-show" />
							<img alt="Logo" src="../../assets/images/front/header.png" class="h-20px h-lg-60px theme-dark-show" />
						</a>
					</div>
					<!--end::Logo-->
					<!--begin::Header wrapper-->
					<div class="d-flex align-items-stretch justify-content-between flex-lg-grow-1" id="kt_app_header_wrapper">

						<?php $this->insert("views/menu") ?>

						<!--begin::Navbar-->
						<div class="app-navbar flex-shrink-0">
							<!--begin::User menu-->
							<div class="app-navbar-item" id="kt_header_user_menu_toggle">
								<!--begin::Menu wrapper-->
								<div class="d-flex align-items-center border border-dashed border-gray-300 rounded p-2" data-kt-menu-trigger="{default: 'click', lg: 'hover'}" data-kt-menu-attach="parent" data-kt-menu-placement="bottom-end">
									<!--begin::User-->
									<div class="cursor-pointer symbol me-3 symbol-35px symbol-lg-45px">
										<img class="" src="<?= Auth::account()->photo(); ?>" alt="user" />
									</div>
									<!--end::User-->
									<!--begin:Info-->
									<div class="me-4">
										<a href="pages/user-profile/projects.html" class="text-gray-900 text-hover-primary fs-6 fw-bold"><?= Auth::account()->person()->full_name ?></a>
										<a href="pages/user-profile/overview.html" class="text-gray-500 text-hover-primary fs-7 fw-bold d-block"><?= Auth::account()->email ?></a>
									</div>
									<!--end:Info-->
									<i class="ki-outline ki-down fs-2 text-gray-500 pt-1"></i>
								</div>
								<!--begin::User account menu-->
								<div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg menu-state-color fw-semibold py-4 fs-6 w-275px" data-kt-menu="true">
									<!--begin::Menu item-->
									<div class="menu-item px-3">
										<div class="menu-content d-flex align-items-center px-3">
											<!--begin::Avatar-->
											<div class="symbol symbol-50px me-5">
												<img alt="Logo" src="<?= Auth::account()->photo(); ?>" />
											</div>
											<!--end::Avatar-->
											<!--begin::Username-->
											<div class="d-flex flex-column">
												<div class="fw-bold d-flex align-items-center fs-5"><?= Auth::account()->person()->full_name ?>

												</div>
												<a href="#" class="fw-semibold text-muted text-hover-primary fs-7"><?= Auth::account()->email ?></a>
											</div>
											<!--end::Username-->
										</div>
									</div>
									<!--end::Menu item-->
									<!--begin::Menu separator-->
									<div class="separator my-2"></div>
									<!--end::Menu separator-->
									<!--begin::Menu item-->
									<div class="menu-item px-5">
										<a href="<?= url("app/perfil") ?>" class="menu-link px-5">Meu Perfil</a>
									</div>
									<!--end::Menu item-->
									<!--begin::Menu item-->
									<div class="menu-item px-5" data-kt-menu-trigger="{default: 'click', lg: 'hover'}" data-kt-menu-placement="left-start" data-kt-menu-offset="-15px, 0">
										<a href="#" class="menu-link px-5">
											<span class="menu-title position-relative">Modo
												<span class="ms-5 position-absolute translate-middle-y top-50 end-0">
													<i class="ki-outline ki-night-day theme-light-show fs-2"></i>
													<i class="ki-outline ki-moon theme-dark-show fs-2"></i>
												</span></span>
										</a>
										<!--begin::Menu-->
										<div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-title-gray-700 menu-icon-gray-500 menu-active-bg menu-state-color fw-semibold py-4 fs-base w-150px" data-kt-menu="true" data-kt-element="theme-mode-menu">
											<!--begin::Menu item-->
											<div class="menu-item px-3 my-0">
												<a href="#" class="menu-link px-3 py-2" data-kt-element="mode" data-kt-value="light">
													<span class="menu-icon" data-kt-element="icon">
														<i class="ki-outline ki-night-day fs-2"></i>
													</span>
													<span class="menu-title">Claro</span>
												</a>
											</div>
											<!--end::Menu item-->
											<!--begin::Menu item-->
											<div class="menu-item px-3 my-0">
												<a href="#" class="menu-link px-3 py-2" data-kt-element="mode" data-kt-value="dark">
													<span class="menu-icon" data-kt-element="icon">
														<i class="ki-outline ki-moon fs-2"></i>
													</span>
													<span class="menu-title">Escuro</span>
												</a>
											</div>
											<!--end::Menu item-->
											<!--begin::Menu item-->
											<div class="menu-item px-3 my-0">
												<a href="#" class="menu-link px-3 py-2" data-kt-element="mode" data-kt-value="system">
													<span class="menu-icon" data-kt-element="icon">
														<i class="ki-outline ki-screen fs-2"></i>
													</span>
													<span class="menu-title">Sistema</span>
												</a>
											</div>
											<!--end::Menu item-->
										</div>
										<!--end::Menu-->
									</div>
									<!--end::Menu item-->
									<!--begin::Menu separator-->
									<div class="separator my-2"></div>
									<!--end::Menu separator-->
									<!--begin::Menu item-->
									<div class="menu-item px-5">
										<a href="<?= url("app/sair") ?>" class="menu-link px-5">Sair</a>
									</div>
									<!--end::Menu item-->
								</div>
								<!--end::User account menu-->
								<!--end::Menu wrapper-->
							</div>
							<!--end::User menu-->
							<!--begin::Sidebar menu toggle-->
							<!--end::Sidebar menu toggle-->
						</div>
						<!--end::Navbar-->
					</div>
					<!--end::Header wrapper-->
				</div>
				<!--end::Header container-->
			</div>
			<!--end::Header-->
			<!--begin::Wrapper-->
			<div class="app-wrapper flex-column flex-row-fluid" id="kt_app_wrapper">
				<!--begin::Toolbar-->
				<div id="kt_app_toolbar" class="app-toolbar pt-4 pt-lg-7 mb-n2 mb-lg-n3">
					<!--begin::Toolbar container-->
					<div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack flex-row-fluid">
						<!--begin::Toolbar container-->
						<div class="d-flex flex-stack flex-row-fluid">
							<!--begin::Toolbar container-->
							<div class="d-flex flex-column flex-row-fluid">
								<!--begin::Toolbar wrapper-->
								<!--begin::Breadcrumb-->
								<ul class="breadcrumb breadcrumb-separatorless fw-semibold mb-1 mb-lg-3 me-2 fs-7">
									<!--begin::Item-->
									<li class="breadcrumb-item text-gray-700 fw-bold lh-1">
										<a href="<?= url("app") ?>" class="text-white text-hover-primary">
											<i class="ki-outline ki-home text-gray-700 fs-6"></i>
										</a>
									</li>
									<!--end::Item-->
									<!--begin::Item-->
									<li class="breadcrumb-item">
										<i class="ki-outline ki-right fs-7 text-gray-700 mx-n1"></i>
									</li>
									<!--end::Item-->
									<!--begin::Item-->
									<li class="breadcrumb-item text-gray-700 fw-bold lh-1">Home</li>
									<!--end::Item-->
								</ul>
								<!--end::Breadcrumb-->
							</div>
							<!--end::Toolbar container-->
						</div>
						<!--end::Toolbar container-->
					</div>
					<!--end::Toolbar container-->
				</div>
				<!--end::Toolbar-->
				<!--begin::Wrapper container-->
				<div class="app-container container-xxl d-flex">
					<!--begin::Main-->
					<div class="app-main flex-column flex-row-fluid" id="kt_app_main">
						<!--begin::Content wrapper-->
						<div class="d-flex flex-column flex-column-fluid">
							<!--begin::Content-->
							<div id="kt_app_content" class="app-content">

								<?= $this->section("content") ?>

							</div>
							<!--end::Content-->
						</div>
						<!--end::Content wrapper-->
						<!--begin::Footer-->
						<div id="kt_app_footer" class="app-footer d-flex flex-column flex-md-row flex-center flex-md-stack pb-3">
							<!--begin::Copyright-->
							<div class="text-gray-900 order-2 order-md-1">
								<span class="text-muted fw-semibold me-1">2025&copy;</span>
								<a href="https://uniaene.edu.br/gti" target="_blank" class="text-gray-800 text-hover-primary">GTI UNIAENE | 6º Período</a>
							</div>
							<!--end::Copyright-->
							<!--begin::Menu-->
							<ul class="menu menu-gray-600 menu-hover-primary fw-semibold order-1">
								<li class="menu-item">
									<a href="#" class="menu-link px-2" data-bs-toggle="modal" data-bs-target="#aboutUsModal">Sobre Nós</a>
								</li>
								<li class="menu-item">
									<a href="<?= url("app/meus-chamados") ?>" class="menu-link px-2">Suporte</a>
								</li>
							</ul>
							<!--end::Menu-->
						</div>
						<!--end::Footer-->
					</div>
					<!--end:::Main-->
				</div>
				<!--end::Wrapper container-->
			</div>
			<!--end::Wrapper-->
		</div>
		<!--end::Page-->
	</div>
	<!--end::App-->
	<!--begin::Drawers-->

	<!--begin::Modal - Sobre Nós-->
	<div class="modal fade" id="aboutUsModal" tabindex="-1" aria-hidden="true">
		<div class="modal-dialog modal-lg modal-dialog-centered">
			<div class="modal-content rounded shadow">

				<!-- Header -->
				<div class="modal-header">
					<h3 class="modal-title fw-bold">Sobre Nós</h3>
					<button type="button" class="btn btn-sm btn-icon" data-bs-dismiss="modal">
						<i class="ki-outline ki-cross fs-2"></i>
					</button>
				</div>

				<!-- Body -->
				<div class="modal-body py-5 px-10">

					<div class="text-center mb-5">
						<i class="ki-outline ki-information fs-1 text-primary mb-3"></i>
						<h4 class="fw-bold text-gray-800">Quem Somos</h4>
					</div>

					<p class="fs-5 text-gray-700">
						A SkyWave Fibra é uma provedora de internet dedicada a entregar velocidade,
						estabilidade e atendimento de excelência. Nosso compromisso é conectar pessoas
						e empresas com a melhor tecnologia disponível no mercado.
					</p>

					<p class="fs-6 text-gray-600 mt-3">
						Temos orgulho de oferecer suporte humanizado, profissionais capacitados e
						infraestrutura moderna, garantindo a melhor experiência para nossos clientes.
					</p>

				</div>

				<!-- Footer -->
				<div class="modal-footer justify-content-end">
					<button type="button" class="btn btn-primary rounded-pill" data-bs-dismiss="modal">
						Fechar
					</button>
				</div>

			</div>
		</div>
	</div>
	<!--end::Modal-->


	<!--end::Drawers-->
	<!--begin::Scrolltop-->
	<div id="kt_scrolltop" class="scrolltop" data-kt-scrolltop="true">
		<i class="ki-outline ki-arrow-up"></i>
	</div>
	<!--end::Scrolltop-->
	<!--begin::Modals-->


	<!--begin::Modal - Upgrade plan-->
	<div class="modal fade" id="kt_modal_upgrade_plan" tabindex="-1" aria-hidden="true">
		<div class="modal-dialog modal-xl">
			<div class="modal-content rounded">
				<form action="<?= url("app/upgrade/plan") ?>" method="post">
					<div class="modal-header justify-content-end border-0 pb-0">
						<div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
							<i class="ki-outline ki-cross fs-1"></i>
						</div>
					</div>

					<div class="modal-body pt-0 pb-15 px-5 px-xl-20">
						<div class="mb-13 text-center">
							<h1 class="mb-3">FAZER UPGRADE DO PLANO</h1>
							<div class="text-muted fw-semibold fs-5">
								Se você precisar de mais informações, por favor, verifique
								<a href="#" class="link-primary fw-bold">Regras de Preços</a>.
							</div>
						</div>

						<div class="d-flex flex-column">
							<div class="nav-group nav-group-outline mx-auto" data-kt-buttons="true">
								<!-- <button class="btn btn-color-gray-500 btn-active btn-active-secondary px-6 py-3 me-2 active" data-kt-plan="month">Mensal</button>
								<button class="btn btn-color-gray-500 btn-active btn-active-secondary px-6 py-3" data-kt-plan="annual">Anual</button> -->
							</div>

							<div class="row mt-10">
								<!-- Coluna esquerda -->
								<div class="col-lg-6 mb-10 mb-lg-0">
									<div class="nav flex-column">
										<?php if (!empty($modal_plans)): foreach ($modal_plans as $index => $plan): ?>
												<label class="nav-link btn btn-outline btn-outline-dashed btn-color-dark btn-active btn-active-primary d-flex flex-stack text-start p-6 mb-6 <?= ($index == 0) ? 'active' : '' ?>"
													data-bs-toggle="tab" data-bs-target="#kt_upgrade_plan_<?= $plan->id; ?>">
													<div class="d-flex align-items-center me-2">
														<div class="form-check form-check-custom form-check-solid form-check-success flex-shrink-0 me-6">
															<input class="form-check-input" type="radio" name="plan" <?= ($index == 0) ? 'checked="checked"' : '' ?> value="<?= $plan->id; ?>" />
														</div>
														<div class="flex-grow-1">
															<div class="d-flex align-items-center fs-2 fw-bold flex-wrap"><?= $plan->name; ?></div>
															<div class="fw-semibold opacity-75">
																<?= $plan->download_speed; ?> Mbps Down / <?= $plan->upload_speed; ?> Mbps Up
															</div>
														</div>
													</div>
													<div class="ms-5">
														<span class="mb-2">R$</span>
														<span class="fs-3x fw-bold"
															data-kt-plan-price-month="<?= $plan->price; ?>"
															data-kt-plan-price-annual="<?= ($plan->price * 12 * 0.9); ?>">
															<?= number_format($plan->price, 2, ',', '.'); ?>
														</span>
														<span class="fs-7 opacity-50">/
															<span data-kt-element="period">Mês</span>
														</span>
													</div>
												</label>
										<?php endforeach;
										endif; ?>

										<label class="nav-link btn btn-outline btn-outline-dashed btn-color-dark btn-active btn-active-primary d-flex flex-stack text-start p-6 mb-6" data-bs-toggle="tab" data-bs-target="#kt_upgrade_plan_custom">
											<div class="d-flex align-items-center me-2">
												<div class="form-check form-check-custom form-check-solid form-check-success flex-shrink-0 me-6">
													<input class="form-check-input" type="radio" name="plan" value="custom" />
												</div>
												<div class="flex-grow-1">
													<div class="d-flex align-items-center fs-2 fw-bold flex-wrap">Link Dedicado</div>
													<div class="fw-semibold opacity-75">Solicitar uma licença personalizada</div>
												</div>
											</div>
											<div class="ms-5">
												<a href="<?= url("app/contato") ?>" class="btn btn-sm btn-success">Contate-nos</a>
											</div>
										</label>
									</div>
								</div>

								<!-- Coluna direita -->
								<div class="col-lg-6">
									<div class="tab-content rounded h-100 bg-light p-10">
										<?php if (!empty($modal_plans)): foreach ($modal_plans as $index => $plan): ?>
												<div class="tab-pane fade <?= ($index == 0) ? 'show active' : '' ?>" id="kt_upgrade_plan_<?= $plan->id; ?>">
													<div class="pb-5">
														<h2 class="fw-bold text-gray-900"><?= $plan->name; ?></h2>
														<div class="text-muted fw-semibold"><?= $plan->description; ?></div>
													</div>
													<div class="pt-1">
														<div class="d-flex align-items-center mb-7">
															<span class="fw-semibold fs-5 text-gray-700 flex-grow-1">Velocidade</span>
															<span class="fw-semibold fs-5 text-gray-900"><?= $plan->speedFormatted(); ?></span>
														</div>
														<div class="d-flex align-items-center mb-7">
															<span class="fw-semibold fs-5 text-gray-700 flex-grow-1">Franquia</span>
															<span class="fw-semibold fs-5 text-gray-900"><?= $plan->data_cap ? "{$plan->data_cap} GB" : 'Ilimitado'; ?></span>
														</div>
														<div class="d-flex align-items-center mb-7">
															<span class="fw-semibold fs-5 text-gray-700 flex-grow-1">Suporte Técnico</span>
															<i class="ki-outline ki-check-circle fs-1 text-success"></i>
														</div>
													</div>
												</div>
										<?php endforeach;
										endif; ?>

										<div class="tab-pane fade" id="kt_upgrade_plan_custom">
											<div class="pb-5">
												<h2 class="fw-bold text-gray-900">O que há no plano Personalizado?</h2>
												<div class="text-muted fw-semibold">Soluções corporativas sob medida para sua empresa.</div>
											</div>
											<div class="pt-1">
												<!-- Itens -->
												<div class="d-flex align-items-center mb-7"><span class="fw-semibold fs-5 text-gray-700 flex-grow-1">Link Dedicado (Fibra)</span><i class="ki-outline ki-check-circle fs-1 text-success"></i></div>
												<div class="d-flex align-items-center mb-7"><span class="fw-semibold fs-5 text-gray-700 flex-grow-1">Velocidade Simétrica (Upload/Download)</span><i class="ki-outline ki-check-circle fs-1 text-success"></i></div>
												<div class="d-flex align-items-center mb-7"><span class="fw-semibold fs-5 text-gray-700 flex-grow-1">IP Fixo (Bloco /29)</span><i class="ki-outline ki-check-circle fs-1 text-success"></i></div>
												<div class="d-flex align-items-center mb-7"><span class="fw-semibold fs-5 text-gray-700 flex-grow-1">SLA Premium (Garantia de 99.9%)</span><i class="ki-outline ki-check-circle fs-1 text-success"></i></div>
												<div class="d-flex align-items-center mb-7"><span class="fw-semibold fs-5 text-gray-700 flex-grow-1">Suporte Técnico Prioritário 24/7</span><i class="ki-outline ki-check-circle fs-1 text-success"></i></div>
												<div class="d-flex align-items-center mb-7"><span class="fw-semibold fs-5 text-gray-700 flex-grow-1">Monitoramento de Rede Proativo</span><i class="ki-outline ki-check-circle fs-1 text-success"></i></div>
												<div class="d-flex align-items-center"><span class="fw-semibold fs-5 text-gray-700 flex-grow-1">Soluções de Firewall Gerenciado</span><i class="ki-outline ki-check-circle fs-1 text-success"></i></div>
											</div>
										</div>

										<!--begin::Actions-->
										<div class="d-flex flex-center flex-row-fluid pt-12">
											<button type="reset" class="btn btn-light me-3" data-bs-dismiss="modal">Cancelar</button>
											<button type="submit" class="btn btn-primary">
												<span class="indicator-label">Upgrade Plano</span>
												<span class="indicator-progress">Aguarde...
													<span class="spinner-border spinner-border-sm align-middle ms-2"></span>
												</span>
											</button>
										</div>
										<!--end::Actions-->
									</div>
								</div>
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
	<!--end::Modal - Upgrade plan-->


	<!--begin::Modal - Contratar plano-->
	<div class="modal fade" id="kt_modal_contract_plan" tabindex="-1" aria-hidden="true">
		<div class="modal-dialog modal-xl">
			<div class="modal-content rounded">
				<form action="<?= url("app/contract/plan") ?>" method="post">
					<div class="modal-header justify-content-end border-0 pb-0">
						<div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
							<i class="ki-outline ki-cross fs-1"></i>
						</div>
					</div>

					<div class="modal-body pt-0 pb-15 px-5 px-xl-20">
						<div class="mb-13 text-center">
							<h1 class="mb-3">CONTRATAR UM PLANO</h1>
							<div class="text-muted fw-semibold fs-5">
								Escolha o plano ideal para você e comece a navegar com qualidade.
								Caso precise de ajuda, entre em contato com nossa equipe de suporte.
							</div>
						</div>

						<div class="d-flex flex-column">
							<div class="nav-group nav-group-outline mx-auto" data-kt-buttons="true">
								<!-- <button class="btn btn-color-gray-500 btn-active btn-active-secondary px-6 py-3 me-2 active" data-kt-plan="month">Mensal</button>
								<button class="btn btn-color-gray-500 btn-active btn-active-secondary px-6 py-3" data-kt-plan="annual">Anual</button> -->
							</div>

							<div class="row mt-10">
								<!-- Coluna esquerda -->
								<div class="col-lg-6 mb-10 mb-lg-0">
									<div class="nav flex-column">
										<?php if (!empty($modal_plans)): foreach ($modal_plans as $index => $plan): ?>
												<label class="nav-link btn btn-outline btn-outline-dashed btn-color-dark btn-active btn-active-primary d-flex flex-stack text-start p-6 mb-6 <?= ($index == 0) ? 'active' : '' ?>"
													data-bs-toggle="tab" data-bs-target="#kt_contract_plan_<?= $plan->id; ?>">
													<div class="d-flex align-items-center me-2">
														<div class="form-check form-check-custom form-check-solid form-check-success flex-shrink-0 me-6">
															<input class="form-check-input" type="radio" name="plan" <?= ($index == 0) ? 'checked="checked"' : '' ?> value="<?= $plan->id; ?>" />
														</div>
														<div class="flex-grow-1">
															<div class="d-flex align-items-center fs-2 fw-bold flex-wrap"><?= $plan->name; ?></div>
															<div class="fw-semibold opacity-75">
																<?= $plan->download_speed; ?> Mbps Down / <?= $plan->upload_speed; ?> Mbps Up
															</div>
														</div>
													</div>
													<div class="ms-5">
														<span class="mb-2">R$</span>
														<span class="fs-3x fw-bold"
															data-kt-plan-price-month="<?= $plan->price; ?>"
															data-kt-plan-price-annual="<?= ($plan->price * 12 * 0.9); ?>">
															<?= number_format($plan->price, 2, ',', '.'); ?>
														</span>
														<span class="fs-7 opacity-50">/
															<span data-kt-element="period">Mês</span>
														</span>
													</div>
												</label>
										<?php endforeach;
										endif; ?>

										<label class="nav-link btn btn-outline btn-outline-dashed btn-color-dark btn-active btn-active-primary d-flex flex-stack text-start p-6 mb-6"
											data-bs-toggle="tab" data-bs-target="#kt_contract_plan_custom">
											<div class="d-flex align-items-center me-2">
												<div class="form-check form-check-custom form-check-solid form-check-success flex-shrink-0 me-6">
													<input class="form-check-input" type="radio" name="plan" value="custom" />
												</div>
												<div class="flex-grow-1">
													<div class="d-flex align-items-center fs-2 fw-bold flex-wrap">Link Dedicado</div>
													<div class="fw-semibold opacity-75">Solicitar uma proposta personalizada</div>
												</div>
											</div>
											<div class="ms-5">
												<a href="<?= url("app/contato") ?>" class="btn btn-sm btn-success">Contate-nos</a>
											</div>
										</label>
									</div>
								</div>

								<!-- Coluna direita -->
								<div class="col-lg-6">
									<div class="tab-content rounded h-100 bg-light p-10">
										<?php if (!empty($modal_plans)): foreach ($modal_plans as $index => $plan): ?>
												<div class="tab-pane fade <?= ($index == 0) ? 'show active' : '' ?>" id="kt_contract_plan_<?= $plan->id; ?>">
													<div class="pb-5">
														<h2 class="fw-bold text-gray-900"><?= $plan->name; ?></h2>
														<div class="text-muted fw-semibold"><?= $plan->description; ?></div>
													</div>
													<div class="pt-1">
														<div class="d-flex align-items-center mb-7">
															<span class="fw-semibold fs-5 text-gray-700 flex-grow-1">Velocidade</span>
															<span class="fw-semibold fs-5 text-gray-900"><?= $plan->speedFormatted(); ?></span>
														</div>
														<div class="d-flex align-items-center mb-7">
															<span class="fw-semibold fs-5 text-gray-700 flex-grow-1">Franquia</span>
															<span class="fw-semibold fs-5 text-gray-900"><?= $plan->data_cap ? "{$plan->data_cap} GB" : 'Ilimitado'; ?></span>
														</div>
														<div class="d-flex align-items-center mb-7">
															<span class="fw-semibold fs-5 text-gray-700 flex-grow-1">Suporte Técnico</span>
															<i class="ki-outline ki-check-circle fs-1 text-success"></i>
														</div>
													</div>
												</div>
										<?php endforeach;
										endif; ?>

										<div class="tab-pane fade" id="kt_contract_plan_custom">
											<div class="pb-5">
												<h2 class="fw-bold text-gray-900">Plano Corporativo Personalizado</h2>
												<div class="text-muted fw-semibold">Soluções empresariais sob medida para sua empresa.</div>
											</div>
											<div class="pt-1">
												<div class="d-flex align-items-center mb-7"><span class="fw-semibold fs-5 text-gray-700 flex-grow-1">Link Dedicado (Fibra)</span><i class="ki-outline ki-check-circle fs-1 text-success"></i></div>
												<div class="d-flex align-items-center mb-7"><span class="fw-semibold fs-5 text-gray-700 flex-grow-1">Velocidade Simétrica</span><i class="ki-outline ki-check-circle fs-1 text-success"></i></div>
												<div class="d-flex align-items-center mb-7"><span class="fw-semibold fs-5 text-gray-700 flex-grow-1">IP Fixo (Bloco /29)</span><i class="ki-outline ki-check-circle fs-1 text-success"></i></div>
												<div class="d-flex align-items-center mb-7"><span class="fw-semibold fs-5 text-gray-700 flex-grow-1">SLA Premium (99.9%)</span><i class="ki-outline ki-check-circle fs-1 text-success"></i></div>
												<div class="d-flex align-items-center mb-7"><span class="fw-semibold fs-5 text-gray-700 flex-grow-1">Suporte Prioritário 24/7</span><i class="ki-outline ki-check-circle fs-1 text-success"></i></div>
												<div class="d-flex align-items-center"><span class="fw-semibold fs-5 text-gray-700 flex-grow-1">Monitoramento e Firewall Gerenciado</span><i class="ki-outline ki-check-circle fs-1 text-success"></i></div>
											</div>
										</div>

										<!--begin::Actions-->
										<div class="d-flex flex-center flex-row-fluid pt-12">
											<button type="reset" class="btn btn-light me-3" data-bs-dismiss="modal">Cancelar</button>
											<button type="submit" class="btn btn-success">
												<span class="indicator-label">Contratar Plano</span>
												<span class="indicator-progress">Processando...
													<span class="spinner-border spinner-border-sm align-middle ms-2"></span>
												</span>
											</button>
										</div>
										<!--end::Actions-->
									</div>
								</div>
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
	<!--end::Modal - Contratar plano-->



	<!--end::Modals-->
	<!--begin::Javascript-->
	<script>
		var hostUrl = "assets/";
	</script>
	<!--begin::Global Javascript Bundle(mandatory for all pages)-->
	<script src="assets/plugins/global/plugins.bundle.js"></script>
	<script src="assets/js/scripts.bundle.js"></script>
	<!--end::Global Javascript Bundle-->
	<!--begin::Vendors Javascript(used for this page only)-->
	<script src="assets/plugins/custom/fullcalendar/fullcalendar.bundle.js"></script>
	<script src="https://cdn.amcharts.com/lib/5/index.js"></script>
	<script src="https://cdn.amcharts.com/lib/5/xy.js"></script>
	<script src="https://cdn.amcharts.com/lib/5/percent.js"></script>
	<script src="https://cdn.amcharts.com/lib/5/radar.js"></script>
	<script src="https://cdn.amcharts.com/lib/5/themes/Animated.js"></script>
	<script src="https://cdn.amcharts.com/lib/5/map.js"></script>
	<script src="https://cdn.amcharts.com/lib/5/geodata/worldLow.js"></script>
	<script src="https://cdn.amcharts.com/lib/5/geodata/continentsLow.js"></script>
	<script src="https://cdn.amcharts.com/lib/5/geodata/usaLow.js"></script>
	<script src="https://cdn.amcharts.com/lib/5/geodata/worldTimeZonesLow.js"></script>
	<script src="https://cdn.amcharts.com/lib/5/geodata/worldTimeZoneAreasLow.js"></script>
	<!-- Carrega o DataTables JS principal de um CDN -->
	<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
	<!--end::Vendors Javascript-->
	<!--begin::Custom Javascript(used for this page only)-->
	<script src="assets/js/widgets.bundle.js"></script>
	<script src="assets/js/custom/widgets.js"></script>
	<script src="assets/js/custom/apps/chat/chat.js"></script>
	<script src="assets/js/custom/utilities/modals/create-app.js"></script>
	<script src="assets/js/custom/utilities/modals/new-card.js"></script>
	<script src="assets/js/custom/utilities/modals/upgrade-plan.js"></script>
	<script src="assets/js/custom/utilities/modals/users-search.js"></script>
	<!--end::Custom Javascript-->
	<!--end::Javascript-->

	<script src="<?= url("/shared/assets/js/custom.js") ?>"></script>

	<?= $this->section("scripts"); ?>
</body>
<!--end::Body-->

</html>