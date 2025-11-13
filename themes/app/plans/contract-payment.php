<?php 
$this->layout("_theme"); 
$newPlan = $newPlan ?? null;
if (!$newPlan) { return; } 

// Dados fictícios de pagamento
$fakePixKey = "(99) 99999-9999"; 
$fakeBoletoNumber = "34191.09008 10000.000000 00000.123456 9 644500000" . ($newPlan->price * 100);
?>

<div class="card shadow-sm mb-10">
    <div class="card-header border-0 pt-8 pb-4">
        <h3 class="card-title fw-bolder fs-1">Finalizar Contratação</h3>
        <p class="text-muted mt-2">Revise os detalhes e conclua o pagamento fictício.</p>
    </div>

    <div class="card-body pt-0">

        <!-- ALERTA FICTÍCIO -->
        <div class="alert alert-info d-flex align-items-center p-6 rounded-3 mb-10 shadow-sm">
            <i class="ki-outline ki-information-4 fs-3hx text-info me-5"></i>
            <div>
                <h4 class="fw-bold mb-1 text-info">Simulação Acadêmica</h4>
                <p class="mb-0">Esta é uma simulação de pagamento. Nenhuma transação real será efetuada.</p>
            </div>
        </div>

        <div class="row g-10">

            <!-- COLUNA ESQUERDA — DETALHES DO PLANO -->
            <div class="col-lg-5">

                <div class="card shadow-sm border-0 rounded-3">
                    <div class="card-body p-5">

                        <div class="d-flex align-items-center mb-4">
                            <i class="ki-outline ki-chart-simple fs-2hx text-primary me-3"></i>
                            <h4 class="fw-bolder m-0">Resumo do Plano</h4>
                        </div>

                        <div class="mb-4">
                            <span class="text-muted">Plano selecionado</span>
                            <p class="fs-3 fw-bold text-primary mb-1"><?= $newPlan->name; ?></p>
                        </div>

                        <div class="separator separator-dashed my-5"></div>

                        <div class="d-flex justify-content-between fs-5 fw-semibold mb-3">
                            <span>Próxima Mensalidade:</span>
                            <span class="text-gray-800"><?= $newPlan->priceFormatted(); ?></span>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mt-5">
                            <span class="fs-3 fw-bolder text-dark">Total Agora:</span>
                            <span class="fs-2x fw-extrabold text-success"><?= $newPlan->priceFormatted(); ?></span>
                        </div>

                    </div>
                </div>

            </div>

            <!-- COLUNA DIREITA — MÉTODOS DE PAGAMENTO -->
            <div class="col-lg-7">

                <h3 class="fw-bolder mb-6">Formas de Pagamento (Simulação)</h3>

                <form action="<?= url("app/contract/process") ?>" method="post">
                    <input type="hidden" name="planId" value="<?= $newPlan->id; ?>">

                    <!-- PIX -->
                    <div class="card shadow-sm border-0 rounded-3 mb-8">
                        <div class="card-body p-5">
                            <div class="d-flex align-items-center mb-3">
                                <div class="symbol symbol-40px me-3">
                                    <span class="symbol-label bg-light-success">
                                        <i class="ki-outline ki-wallet fs-2 text-success"></i>
                                    </span>
                                </div>
                                <h5 class="fw-bold m-0 text-success">Pagamento Via PIX</h5>
                            </div>

                            <p class="fw-semibold text-muted mb-3">
                                Chave (Telefone):
                                <span class="text-success fw-bold"><?= $fakePixKey; ?></span>
                            </p>

                            <div class="d-flex justify-content-center my-4">
                                <img 
                                    src="<?= url("shared/assets/images/qr.png") ?>" 
                                    class="rounded-3 shadow-sm border p-2"
                                    width="180"
                                    height="180"
                                    alt="QR Code Pix"
                                >
                            </div>

                            <p class="text-center fs-6 text-muted">Valor: 
                                <strong><?= $newPlan->priceFormatted(); ?></strong>
                            </p>
                        </div>
                    </div>

                    <!-- BOLETO -->
                    <div class="card shadow-sm border-0 rounded-3 mb-10">
                        <div class="card-body p-5">
                            <div class="d-flex align-items-center mb-3">
                                <div class="symbol symbol-40px me-3">
                                    <span class="symbol-label bg-light-warning">
                                        <i class="ki-outline ki-files fs-2 text-warning"></i>
                                    </span>
                                </div>
                                <h5 class="fw-bold m-0 text-warning">Boleto Bancário (Fictício)</h5>
                            </div>

                            <p class="fw-semibold text-muted mb-2">Linha Digitável:</p>

                            <code class="d-block bg-light-warning bg-opacity-25 p-3 rounded fs-7 fw-bold mb-3 border text-warning">
                                <?= $fakeBoletoNumber; ?>
                            </code>

                            <p class="text-center fs-6 text-muted">Valor:
                                <strong><?= $newPlan->priceFormatted(); ?></strong>
                            </p>
                        </div>
                    </div>

                    <!-- BOTÃO FINAL -->
                    <div class="d-grid">
                        <button type="submit" class="btn btn-lg btn-primary py-4 fw-bold fs-4">
                            <i class="ki-outline ki-check fs-3 me-2"></i>
                            Ativar Plano (Simulação)
                        </button>
                    </div>

                </form>

            </div>
        </div>
    </div>
</div>
