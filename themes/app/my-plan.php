<?php $this->layout("_theme"); ?>

<div class="card shadow-sm border-0">
    <div class="card-header bg-primary text-white py-6">
        <h2 class="fw-bold mb-0"><?= $plan->description ?? "Plano não encontrado"; ?></h2>
        <small class="opacity-75"><?= $plan->name ?? "Sem descrição disponível"; ?></small>
    </div>

    <div class="card-body py-6 px-8">
        <h4 class="fw-semibold text-gray-800 mb-4">📦 Informações do Plano</h4>

        <?php if ($plan): ?>
            <div class="row gy-4">
                <div class="col-md-4">
                    <div class="border rounded p-4 bg-light">
                        <div class="fw-bold text-gray-700">Tipo</div>
                        <div class="fs-4 fw-bolder text-dark"><?= ucfirst($plan->name ?? "—"); ?>
                            <br><small class="text-muted"><?= $plan->description ?></small>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="border rounded p-4 bg-light">
                        <div class="fw-bold text-gray-700">Velocidade</div>
                        <div class="fs-4 fw-bolder text-primary"><?= $plan->download_speed ?? "—"; ?></div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="border rounded p-4 bg-light">
                        <div class="fw-bold text-gray-700">Valor Mensal</div>
                        <div class="fs-4 fw-bolder text-success">
                            R$ <?= number_format($plan->price ?? 0, 2, ",", "."); ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <div class="alert alert-warning mt-4">Nenhum plano encontrado para este cliente.</div>
        <?php endif; ?>

        <hr class="my-6">

        <h4 class="fw-semibold text-gray-800 mb-4">🖥️ Equipamento Associado</h4>
        <?php if ($equipment): ?>
            <div class="border rounded p-4 bg-light">
                <div class="row gy-2">
                    <div class="col-md-6">
                        <strong>Tipo:</strong> <?= $equipment->type ?? "—"; ?><br>
                        <strong>Modelo:</strong> <?= $equipment->model ?? "—"; ?>
                    </div>
                    <div class="col-md-6">
                        <strong>Endereço IP:</strong> <?= $equipment->ip_address ?? "Dinâmico"; ?><br>
                        <strong>Número de Série:</strong> <?= $equipment->serial_number ?? "—"; ?>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <div class="alert alert-info">Nenhum equipamento associado a este plano.</div>
        <?php endif; ?>

        <hr class="my-6">

        <div class="d-flex flex-wrap gap-4 justify-content-center mt-6">
            <a href="<?= url("app/meus-chamados") ?>" class="btn btn-info btn-lg">
                <i class="ki-duotone ki-messages fs-2">
                    <span class="path1"></span>
                    <span class="path2"></span>
                    <span class="path3"></span>
                    <span class="path4"></span>
                    <span class="path5"></span>
                </i>
                Suporte Técnico
            </a>
            <a href="https://wa.me/5575987092386" target="_blank" class="btn btn-success btn-lg">
                <i class="ki-outline ki-whatsapp fs-2 me-2"></i> WhatsApp
            </a>
            <a href="https://www.speedtest.net/" target="_blank" class="btn btn-primary btn-lg">
                <i class="ki-duotone ki-courier-express fs-2">
                    <span class="path1"></span>
                    <span class="path2"></span>
                    <span class="path3"></span>
                    <span class="path4"></span>
                    <span class="path5"></span>
                    <span class="path6"></span>
                    <span class="path7"></span>
                </i>
                Testar Velocidade
            </a>
        </div>
    </div>
</div>