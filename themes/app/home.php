<?php

$this->layout("_theme"); ?>

<?php if (!$current_plan): ?>
    <!--begin::Row-->
    <div class="row gx-5 gx-xl-10">
        <!--begin::Col - Planos-->
        <div class="col-xl-4 mb-10">
            <div class="card card-flush h-100 shadow-sm border-0">
                <div class="card-header bg-primary text-white py-5">
                    <h3 class="card-title fw-bold fs-2x mb-1">Nossos Planos de Internet</h3>
                    <span class="fs-6 opacity-75">Escolha o plano ideal e navegue com a qualidade da Sky Wave Fibra.</span>
                </div>
                <div class="card-body p-6">

                    <!-- Plano Básico -->
                    <div class="mb-6 p-5 rounded border hover-elevate-up transition bg-light">
                        <h4 class="fw-bold text-primary mb-1">100Mb</h4>
                        <p class="text-gray-600 mb-2">Ideal para uso diário</p>
                        <div class="fs-2 fw-bold text-dark mb-2">R$ 55 <small class="fs-6 text-gray-600">/ mês</small></div>
                        <ul class="list-unstyled text-gray-700 mb-0">
                            <li>• 100 Mega de Velocidade</li>
                            <li>• Wi-Fi incluso</li>
                            <li>• Suporte 24h</li>
                        </ul>
                    </div>

                    <!-- Plano Família -->
                    <div class="mb-6 p-5 rounded border hover-elevate-up transition bg-light">
                        <h4 class="fw-bold text-primary mb-1">500Mb</h4>
                        <p class="text-gray-600 mb-2">Perfeito para vários dispositivos</p>
                        <div class="fs-2 fw-bold text-dark mb-2">R$ 75 <small class="fs-6 text-gray-600">/ mês</small></div>
                        <ul class="list-unstyled text-gray-700 mb-0">
                            <li>• 500 Mega de Velocidade</li>
                            <li>• Wi-Fi de alta performance</li>
                            <li>• Suporte técnico prioritário</li>
                        </ul>
                    </div>

                    <!-- Plano Turbo -->
                    <div class="p-5 rounded border hover-elevate-up transition bg-light">
                        <h4 class="fw-bold text-primary mb-1">700Mb</h4>
                        <p class="text-gray-600 mb-2">Máxima performance</p>
                        <div class="fs-2 fw-bold text-dark mb-2">R$ 100 <small class="fs-6 text-gray-600">/ mês</small></div>
                        <ul class="list-unstyled text-gray-700 mb-0">
                            <li>• 700 Mega de Velocidade</li>
                            <li>• Wi-Fi avançado incluso</li>
                            <li>• Atendimento VIP</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <!--end::Col-->
        <!--begin::Col-->
        <div class="col-xl-8 mb-10">
            <!--begin::Engage widget 4-->
            <div class="card border-transparent w-100 shadow-sm sticky-top" data-bs-theme="light" style="background-color: #1C325E; top: 180px; z-index: 1;">
                <!-- <div class="card border-transparent w-100 shadow-sm" data-bs-theme="light" style="background-color: #1C325E;"> -->
                <!--begin::Body-->
                <div class="card-body d-flex ps-xl-15 position-relative">
                    <!--begin::Wrapper-->
                    <div class="m-0">
                        <!--begin::Title-->
                        <div class="position-relative fs-2x z-index-2 fw-bold text-white mb-7"
                            style="max-width: 480px; width: 100%;">
                            <span class="me-2">Navegue com a força da
                                <span class="position-relative d-inline-block text-warning">
                                    <a href="javascript:void(0);" class="text-warning opacity-75-hover">Sky Wave Fibra</a>
                                    <!--begin::Separator-->
                                    <span class="position-absolute opacity-50 bottom-0 start-0 border-4 border-warning border-bottom w-100"></span>
                                    <!--end::Separator-->
                                </span>
                            </span>
                            <br />Velocidade, estabilidade e suporte que te conectam a tudo!
                        </div>
                        <!--end::Title-->
                        <!--begin::Action-->
                        <div class="mb-3">
                            <a href="#contratar" class="btn btn-warning fw-semibold me-2 text-dark shadow" data-bs-toggle="modal" data-bs-target="#kt_modal_contract_plan">
                                💨 Contratar Agora
                            </a>
                        </div>
                        <!--end::Action-->
                    </div>
                    <!--end::Wrapper-->

                    <!--begin::Illustration-->
                    <img src="assets/media/illustrations/sigma-1/17-dark.png"
                        class="position-absolute me-3 bottom-0 end-0 h-400px d-none d-md-block"
                        alt="Sky Wave Fibra - Internet de Alta Velocidade" />
                    <!--end::Illustration-->
                </div>
                <!--end::Body-->
            </div>
            <!--end::Engage widget 4-->
        </div>
        <!--end::Col-->
    </div>
    <!--end::Row-->
<?php else: ?>
    <!--begin::Row-->
    <div class="row g-5 g-xxl-10">
        <!--begin::Col-->
        <div class="col-xxl-4 mb-xxl-10">
            <!--begin::Card Widget 22-->
            <div class="card card-reset mb-5 mb-xl-10">
                <!--begin::Body-->
                <div class="card-body p-0">
                    <!--begin::Row-->
                    <div class="row g-5 g-lg-9">
                        <!--begin::Col-->
                        <div class="col-6">
                            <!--begin::Card-->
                            <div class="card card-shadow">
                                <!--begin::Body-->
                                <div class="card-body p-0">
                                    <!--begin::Items-->
                                    <a href='<?= url("app/meu-plano") ?>' class="btn btn-active-color-primary px-7 py-6 text-start w-100">
                                        <!--begin::Icon-->
                                        <i class="ki-outline ki-element-11 fs-2x fs-lg-2hx text-gray-500 ms-n1"></i>
                                        <!--end::Icon-->
                                        <!--begin::Desc-->
                                        <div class="fw-bold fs-5 pt-4">Meu Plano</div>
                                        <!--end::Desc-->
                                    </a>
                                    <!--end::Items-->
                                </div>
                                <!--end::Body-->
                            </div>
                            <!--end::Card-->
                        </div>
                        <!--end::Col-->
                        <!--begin::Col-->
                        <div class="col-6">
                            <!--begin::Card-->
                            <div class="card card-shadow">
                                <!--begin::Body-->
                                <div class="card-body p-0">
                                    <!--begin::Items-->
                                    <a href='<?= url("app/meus-chamados") ?>' class="btn btn-active-color-prim  ?>ary px-7 py-6 text-start w-100">
                                        <!--begin::Icon-->
                                        <i class="ki-outline ki-rescue fs-2x fs-lg-2hx text-gray-500 ms-n1"></i>
                                        <!--end::Icon-->
                                        <!--begin::Desc-->
                                        <div class="fw-bold fs-5 pt-4">Suporte</div>
                                        <!--end::Desc-->
                                    </a>
                                    <!--end::Items-->
                                </div>
                                <!--end::Body-->
                            </div>
                            <!--end::Card-->
                        </div>
                        <!--end::Col-->
                        <!--begin::Col-->
                        <div class="col-6">
                            <!--begin::Card-->
                            <div class="card card-shadow">
                                <!--begin::Body-->
                                <div class="card-body p-0">
                                    <!--begin::Items-->
                                    <a href='#' class="btn btn-active-color-primary px-7 py-6 text-start w-100" data-bs-toggle="modal" data-bs-target="#kt_modal_upgrade_plan">
                                        <!--begin::Icon-->
                                        <i class="ki-outline ki-rocket fs-2x fs-lg-2hx text-gray-500 ms-n1"></i>
                                        <!--end::Icon-->
                                        <!--begin::Desc-->
                                        <div class="fw-bold fs-5 pt-4">Upgrade</div>
                                        <!--end::Desc-->
                                    </a>
                                    <!--end::Items-->
                                </div>
                                <!--end::Body-->
                            </div>
                            <!--end::Card-->
                        </div>
                        <!--end::Col-->
                        <!--begin::Col-->
                        <div class="col-6">
                            <!--begin::Card-->
                            <div class="card card-shadow">
                                <!--begin::Body-->
                                <div class="card-body p-0">
                                    <!--begin::Items-->
                                    <a href='<?= url("app/status-do-servidor") ?>' class="btn btn-active-color-primary px-7 py-6 text-start w-100">
                                        <!--begin::Icon-->
                                        <i class="ki-outline ki-chart-pie-3 fs-2x fs-lg-2hx text-gray-500 ms-n1"></i>
                                        <!--end::Icon-->
                                        <!--begin::Desc-->
                                        <div class="fw-bold fs-5 pt-4">Status</div>
                                        <!--end::Desc-->
                                    </a>
                                    <!--end::Items-->
                                </div>
                                <!--end::Body-->
                            </div>
                            <!--end::Card-->
                        </div>
                        <!--end::Col-->
                    </div>
                </div>
                <!--end::Body-->
            </div>
            <!--end::Card Widget 22-->
        </div>
        <!--end::Col-->
        <!--begin::Col-->
        <div class="col-xxl-8 mb-5 mb-xl-10">
            <!--begin::Engage widget 14-->
            <div class="card border-0 mb-5 mb-xl-11" data-bs-theme="light" style="background-color: #844AFF">
                <!--begin::Body-->
                <div class="card-body py-0">
                    <!--begin::Row-->
                    <div class="row align-items-center lh-1 h-100">
                        <!--begin::Col-->
                        <div class="col-7 ps-xl-10 pe-5">
                            <?php if ($suggested_plan): ?>
                                <!--begin::Title-->
                                <div class="fs-2qx fw-bold text-white mb-6">Faça agora o Upgrade</div>
                                <!--end::Title-->
                                <!--begin::Text-->
                                <span class="fw-semibold text-white fs-6 mb-10 d-block opacity-75">Precisa de mais velocidade? Deixa isso com a gente!</span>
                                <!--end::Text-->
                            <?php else: ?>
                                <!--begin::Title-->
                                <div class="fs-2qx fw-bold text-white mb-6">Parabéns!</div>
                                <!--end::Title-->
                                <!--begin::Text-->
                                <span class="fw-semibold text-white fs-6 mb-10 d-block opacity-75">Você já está navegando na velocidade máxima, aproveite!</span>
                                <!--end::Text-->
                            <?php endif; ?>
                            <!--begin::Items-->
                            <div class="d-flex align-items-center flex-wrap d-grid gap-2 mb-9">
                                <!--begin::Item-->
                                <div class="d-flex align-items-center me-5 me-xl-13">
                                    <!--begin::Symbol-->
                                    <div class="symbol symbol-30px symbol-circle me-3">
                                        <span class="symbol-label" style="background: rgba(255, 255, 255, 0.1)">
                                            <i class="ki-outline ki-abstract-41 fs-5 text-white"></i>
                                        </span>
                                    </div>
                                    <!--end::Symbol-->
                                    <!--begin::Info-->
                                    <div class="text-white">
                                        <span class="fw-semibold d-block fs-8 opacity-75 mb-2">Seu plano atual</span>
                                        <span class="fw-bold fs-7"><?= $current_plan->name; ?></span>
                                    </div>
                                    <!--end::Info-->
                                </div>
                                <!--end::Item-->
                                <?php if ($suggested_plan): ?>
                                    <!--begin::Item-->
                                    <div class="d-flex align-items-center">
                                        <!--begin::Symbol-->
                                        <div class="symbol symbol-30px symbol-circle me-3">
                                            <span class="symbol-label" style="background: rgba(255, 255, 255, 0.1)">
                                                <i class="ki-outline ki-abstract-26 fs-5 text-white"></i>
                                            </span>
                                        </div>
                                        <!--end::Symbol-->
                                        <!--begin::Info-->
                                        <div class="text-white">
                                            <span class="fw-semibold opacity-75 d-block fs-3 mb-2">Upgrade</span>
                                            <span class="fw-bold fs-3x"><?= $suggested_plan->name ?></span>
                                        </div>
                                        <!--end::Info-->
                                    </div>
                                    <!--end::Item-->
                                <?php endif; ?>
                            </div>
                            <!--end::Items-->
                            <?php if ($suggested_plan): ?>
                                <!--begin::Action-->
                                <div class="d-flex d-grid gap-2">
                                    <a href="#" class="btn btn-success me-lg-2" data-bs-toggle="modal" data-bs-target="#kt_modal_upgrade_plan">Faça agora o Upgrade</a>
                                </div>
                                <!--end::Action-->
                            <?php endif; ?>
                        </div>
                        <!--end::Col-->
                        <!--begin::Col-->
                        <div class="col-5 pt-5 pt-lg-15">
                            <!--begin::Illustration-->
                            <div class="bgi-no-repeat bgi-size-contain bgi-position-x-end bgi-position-y-bottom h-325px" style="background-image:url('assets/media/svg/illustrations/easy/8.svg"></div>
                            <!--end::Illustration-->
                        </div>
                        <!--end::Col-->
                    </div>
                    <!--end::Row-->
                </div>
                <!--end::Body-->
            </div>
            <!--end::Engage widget 14-->
        </div>
        <!--end::Col-->
    </div>
    <!--end::Row-->
<?php endif; ?>

<?php /* if ($suggested_plan): ?>
    <div class="row g-5 g-xxl-10">
        <div class="col-xxl-4 mb-xxl-10">
            <div class="card card-reset mb-5 mb-xl-10">
                <div class="card-body p-0">
                    <div class="row g-5 g-lg-9">
                        <div class="col-6">
                            <div class="card card-shadow">
                                <div class="card-body p-0">
                                    <a href='#' class="btn btn-active-color-primary px-7 py-6 text-start w-100">
                                        <i class="ki-outline ki-element-11 fs-2x fs-lg-2hx text-gray-500 ms-n1"></i>
                                        <div class="fw-bold fs-5 pt-4">Meu Plano</div>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                        </div>
                        <!--begin::Card-->
                        <div class="card card-shadow">
                            <!--begin::Body-->
                            <div class="card-body p-0">
                                <!--begin::Items-->
                                <a href='<?= url("app/criar-chamado") ?>' class="btn btn-active-color-primary px-7 py-6 text-start w-100">
                                    <!--begin::Icon-->
                                    <i class="ki-outline ki-rescue fs-2x fs-lg-2hx text-gray-500 ms-n1"></i>
                                    <!--end::Icon-->
                                    <!--begin::Desc-->
                                    <div class="fw-bold fs-5 pt-4">Suporte</div>
                                    <!--end::Desc-->
                                </a>
                                <!--end::Items-->
                            </div>
                            <!--end::Body-->
                        </div>
                        <!--end::Card-->
                    </div>
                    <!--end::Col-->
                    <!--begin::Col-->
                    <div class="col-6">
                        <div class="card card-shadow">
                            <div class="card-body p-0">
                                <a href='#' data-bs-toggle="modal" data-bs-target="#kt_modal_upgrade_plan" class="btn btn-active-color-primary px-7 py-6 text-start w-100">
                                    <i class="ki-outline ki-rocket fs-2x fs-lg-2hx text-gray-500 ms-n1"></i>
                                    <div class="fw-bold fs-5 pt-4">Upgrade</div>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xxl-8 mb-5 mb-xl-10">
        <div class="card border-0 mb-5 mb-xl-11" data-bs-theme="light" style="background-color: #844AFF">
            <div class="card-body py-0">
                <div class="row align-items-center lh-1 h-100">
                    <div class="col-7 ps-xl-10 pe-5">
                        <div class="fs-2qx fw-bold text-white mb-6">Faça agora o Upgrade</div>
                        <span class="fw-semibold text-white fs-6 mb-10 d-block opacity-75">Precisa de mais velocidade? Deixa isso com a gente!</span>

                        <div class="d-flex align-items-center flex-wrap d-grid gap-2 mb-9">
                            <div class="d-flex align-items-center me-5 me-xl-13">
                                <div class="symbol symbol-30px symbol-circle me-3">
                                    <span class="symbol-label" style="background: rgba(255, 255, 255, 0.1)">
                                        <i class="ki-outline ki-abstract-41 fs-5 text-white"></i>
                                    </span>
                                </div>
                                <div class="text-white">
                                    <span class="fw-semibold d-block fs-8 opacity-75 mb-2">Seu plano atual</span>
                                    <span class="fw-bold fs-7"><?= $current_plan->name; ?></span>
                                </div>
                            </div>
                            <div class="d-flex align-items-center">
                                <div class="symbol symbol-30px symbol-circle me-3">
                                    <span class="symbol-label" style="background: rgba(255, 255, 255, 0.1)">
                                        <i class="ki-outline ki-abstract-26 fs-5 text-white"></i>
                                    </span>
                                </div>
                                <div class="text-white">
                                    <span class="fw-semibold opacity-75 d-block fs-3 mb-2">Upgrade para</span>
                                    <span class="fw-bold fs-2"><?= $suggested_plan->name; ?></span>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex d-grid gap-2">
                            <a href="#" class="btn btn-success me-lg-2" data-bs-toggle="modal" data-bs-target="#kt_modal_upgrade_plan">Faça agora o Upgrade</a>
                        </div>
                    </div>
                    <div class="col-5 pt-5 pt-lg-15">
                        <div class="bgi-no-repeat bgi-size-contain bgi-position-x-end bgi-position-y-bottom h-325px" style="background-image:url('assets/media/svg/illustrations/easy/8.svg');"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php endif; */ ?>

<?php if ($isEmployee): ?>
    <!--begin::Row-->
    <div class="row gy-5 g-xl-10">

        <!-- ====================================================== -->
        <!--                      CARD ESQUERDO                     -->
        <!-- ====================================================== -->
        <div class="col-xl-4 mb-xl-10">

            <div class="card card-flush h-xl-100">
                <div class="card-header pt-7">
                    <h3 class="card-title flex-column">
                        <span class="card-label fw-bold text-gray-800">Funcionários</span>
                        <span class="text-gray-500 fs-7 mt-1">Total: <?= count($employees); ?></span>
                    </h3>

                    <div class="card-toolbar">
                        <a href="<?= url('/app/funcionario/associar'); ?>"
                            class="btn btn-sm btn-light-primary">
                            <i class="ki-outline ki-plus fs-3 me-1"></i> Novo
                        </a>
                    </div>
                </div>

                <div class="card-body pt-0 hover-scroll-overlay-y h-300px">

                    <?php foreach ($employees as $emp): ?>
                        <?php $p = $emp->person(); ?>

                        <div class="d-flex flex-stack py-4">

                            <div class="d-flex align-items-center">
                                <div class="symbol symbol-40px me-3">
                                    <img src="<?= $emp->photo(); ?>" alt="">
                                </div>

                                <div class="d-flex flex-column">
                                    <a href="<?= url('/app/funcionario/' . $emp->id); ?>"
                                        class="text-gray-800 fw-bold text-hover-primary fs-6">
                                        <?= $p->full_name; ?>
                                    </a>

                                    <span class="text-muted fs-7"><?= $emp->roleLabel()[0]; ?></span>
                                </div>
                            </div>

                            <a href="<?= url('/app/funcionario/' . $emp->id); ?>"
                                class="btn btn-sm btn-icon btn-bg-light btn-active-color-primary">
                                <i class="ki-outline ki-black-right fs-2"></i>
                            </a>

                        </div>

                        <div class="separator separator-dashed"></div>
                    <?php endforeach; ?>

                </div>
            </div>

        </div>



        <!-- ====================================================== -->
        <!--                      CARD DIREITO                       -->
        <!-- ====================================================== -->
        <div class="col-xl-8 mb-5 mb-xl-10">

            <div class="card card-flush h-xl-100">
                <div class="card-header pt-7">
                    <h3 class="card-title flex-column">
                        <span class="card-label fw-bold text-gray-800">Clientes por Plano</span>
                        <span class="text-gray-500 fs-7 mt-1">Agrupados por tipo de plano</span>
                    </h3>
                </div>

                <div class="card-body">
                    <!-- Abas -->
                    <ul class="nav nav-pills nav-pills-custom mb-4">
                        <?php foreach ($plansWithContracts as $i => $row): ?>
                            <!--begin::Item-->
                            <li class="nav-item mb-3 me-3 me-lg-6">
                                <!--begin::Link-->
                                <a class="nav-link btn btn-outline btn-flex btn-color-muted btn-active-color-primary flex-column overflow-hidden w-100px h-85px pt-5 pb-2 <?= $i == 0 ? 'active' : '' ?>" data-bs-toggle="pill" href="#plan_tab_<?= $row['plan']->id; ?>">
                                    <!--begin::Icon-->
                                    <div class="nav-icon mb-3">
                                        <i class="ki-outline ki-wifi fs-1"></i>
                                    </div>
                                    <!--end::Icon-->
                                    <!--begin::Title-->
                                    <span class="nav-text text-gray-800 fw-bold fs-6 lh-1 text-nowrap"><?= $row['plan']->name; ?></span>
                                    <!--end::Title-->
                                    <!--begin::Bullet-->
                                    <span class="bullet-custom position-absolute bottom-0 w-100 h-4px bg-primary"></span>
                                    <!--end::Bullet-->
                                </a>
                                <!--end::Link-->
                            </li>
                            <!--end::Item-->
                        <?php endforeach; ?>
                    </ul>

                    <!-- Conteúdo -->
                    <div class="tab-content hover-scroll-overlay-y h-300px">

                        <?php foreach ($plansWithContracts as $i => $row): ?>
                            <div class="tab-pane fade <?= $i == 0 ? 'show active' : '' ?>"
                                id="plan_tab_<?= $row['plan']->id; ?>">

                                <div class="table-responsive">
                                    <table class="table table-row-dashed align-middle gy-4">
                                        <tbody>

                                            <?php if (!$row['contracts']): ?>
                                                <tr>
                                                    <td class="text-center py-10 text-muted">Nenhum cliente neste plano</td>
                                                </tr>
                                            <?php endif; ?>

                                            <?php foreach ($row['contracts'] as $contract): ?>
                                                <?php $person = $contract->Person(); ?>

                                                <tr>
                                                    <!-- COLUNA 1: FOTO + NOME + SUBTEXTO -->
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <div class="symbol symbol-40px me-3">
                                                                <img src="<?= $person->account()->photo(); ?>" alt="">
                                                            </div>

                                                            <div class="d-flex flex-column">
                                                                <a href="<?= url('/app/cliente/' . $contract->customer_id); ?>"
                                                                    class="text-gray-900 fw-bold text-hover-primary mb-1 fs-6">
                                                                    <?= $person->full_name; ?>
                                                                </a>

                                                                <span class="text-muted fw-semibold d-block fs-7">
                                                                    CPF: <?= $person->document; ?>
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </td>

                                                    <!-- COLUNA 2: DADOS DO CONTRATO -->
                                                    <td>
                                                        <span class="text-gray-800 fw-bold d-block mb-1 fs-6">
                                                            <?= date('d/m/Y', strtotime($contract->start_date)); ?>
                                                        </span>
                                                        <span class="fw-semibold text-gray-500 d-block fs-7">Início</span>
                                                    </td>

                                                    <!-- COLUNA 3: STATUS DO CONTRATO -->
                                                    <td>
                                                        <span class="text-gray-900 fw-bold text-hover-primary d-block mb-1 fs-6">
                                                            <?= ucfirst($contract->status); ?>
                                                        </span>
                                                        <span class="text-muted fw-semibold d-block fs-7">Status</span>
                                                    </td>

                                                    <!-- COLUNA 4: RATING/INDICADOR -->
                                                    <td>

                                                    </td>

                                                    <!-- COLUNA 5: BOTÃO -->
                                                    <td class="text-end">
                                                        <a href="<?= url('/app/cliente/' . $contract->customer_id); ?>"
                                                            class="btn btn-sm btn-icon btn-bg-light btn-active-color-primary w-30px h-30px">
                                                            <i class="ki-outline ki-black-right fs-2 text-gray-500"></i>
                                                        </a>
                                                    </td>
                                                </tr>


                                            <?php endforeach; ?>

                                        </tbody>
                                    </table>
                                </div>

                            </div>
                        <?php endforeach; ?>

                    </div>

                </div>
            </div>

        </div>


    </div>
    <!--end::Row-->
<?php endif; ?>