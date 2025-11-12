<?php $this->layout("_theme"); ?>

<div class="card shadow-sm border-0">
    <div class="card-header bg-primary text-white py-6">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="fw-bold mb-1"><?= $plan->title ?? "Plano do Cliente"; ?></h2>
                <span class="fs-6 opacity-75"><?= $customer->status === "active" ? "Plano ativo" : "Plano inativo"; ?></span>
            </div>
            <div>
                <?php if ($customer->status === "active"): ?>
                    <button id="cancelPlanBtn" class="btn btn-light-danger btn-sm">
                        <i class="ki-outline ki-cross fs-4 me-1"></i> Cancelar Plano
                    </button>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="card-body py-6 px-8">
        <!-- Dados principais -->
        <h4 class="fw-semibold text-gray-800 mb-4">📄 Detalhes do Plano</h4>
        <div class="row gy-4">
            <div class="col-md-4">
                <div class="border rounded p-4 bg-light">
                    <div class="fw-bold text-gray-700">Velocidade</div>
                    <div class="fs-3 fw-bolder text-primary"><?= $plan->speed ?? "—"; ?></div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="border rounded p-4 bg-light">
                    <div class="fw-bold text-gray-700">Valor Mensal</div>
                    <div class="fs-3 fw-bolder text-success">R$ <?= number_format($plan->price ?? 0, 2, ",", "."); ?></div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="border rounded p-4 bg-light">
                    <div class="fw-bold text-gray-700">Vencimento</div>
                    <div class="fs-3 fw-bolder"><?= date("d/m", strtotime($contract->due_date ?? "now")); ?></div>
                </div>
            </div>
        </div>

        <hr class="my-6">

        <!-- Contrato -->
        <h4 class="fw-semibold text-gray-800 mb-4">📜 Informações do Contrato</h4>
        <div class="row gy-3">
            <div class="col-md-6">
                <div class="border rounded p-4 bg-light">
                    <strong>Início:</strong> <?= date("d/m/Y", strtotime($contract->start_date ?? "now")); ?><br>
                    <strong>Status:</strong> <span class="badge bg-<?= $contract->status === "active" ? "success" : "secondary"; ?>">
                        <?= ucfirst($contract->status ?? "desconhecido"); ?>
                    </span>
                </div>
            </div>
            <div class="col-md-6">
                <div class="border rounded p-4 bg-light">
                    <strong>Código do Contrato:</strong> <?= $contract->id ?? "—"; ?><br>
                    <strong>Forma de Pagamento:</strong> <?= $contract->payment_method ?? "—"; ?>
                </div>
            </div>
        </div>

        <hr class="my-6">

        <!-- Equipamento -->
        <h4 class="fw-semibold text-gray-800 mb-4">🖥️ Equipamento</h4>
        <?php if (!empty($equipment)): ?>
            <div class="border rounded p-4 bg-light">
                <strong>Tipo:</strong> <?= $equipment->type; ?><br>
                <strong>Modelo:</strong> <?= $equipment->model ?? "—"; ?><br>
                <strong>Endereço IP:</strong> <?= $equipment->ip_address ?? "—"; ?><br>
                <strong>Status:</strong>
                <span class="badge bg-<?= $equipment->status === "online" ? "success" : "danger"; ?>">
                    <?= ucfirst($equipment->status); ?>
                </span>
            </div>
        <?php else: ?>
            <div class="alert alert-warning">Nenhum equipamento associado a este cliente.</div>
        <?php endif; ?>

        <hr class="my-6">

        <!-- Ações -->
        <div class="d-flex flex-wrap gap-4 justify-content-center mt-6">
            <a href="https://wa.me/5575987092386" target="_blank" class="btn btn-success btn-lg">
                <i class="ki-outline ki-whatsapp fs-2 me-2"></i> Falar com Suporte
            </a>
            <a href="https://www.speedtest.net/" target="_blank" class="btn btn-primary btn-lg">
                <i class="ki-outline ki-activity fs-2 me-2"></i> Fazer Speed Test
            </a>
            <a href="/app/dicas-de-conexao" class="btn btn-warning btn-lg text-white">
                <i class="ki-outline ki-bulb fs-2 me-2"></i> Dicas de Desempenho
            </a>
        </div>
    </div>
</div>

<script>
    $(document).on('click', '#cancelPlanBtn', function() {
        Swal.fire({
            title: 'Cancelar Plano?',
            text: 'Esta ação encerrará o plano atual. Deseja continuar?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Sim, cancelar',
            cancelButtonText: 'Voltar',
            confirmButtonColor: '#d33',
        }).then((result) => {
            if (result.isConfirmed) {
                $.post('/app/plan/cancel', {
                    id: <?= $contract->id ?? 0; ?>
                }, function(res) {
                    Swal.fire('Plano cancelado!', res.message, 'success').then(() => {
                        location.reload();
                    });
                });
            }
        });
    });
</script>