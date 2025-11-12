<?php $this->layout("_theme"); ?>

<div class="container py-10">
    <div class="text-center mb-10">
        <h1 class="fw-bold text-primary mb-3">
            <?= $title ?? "Canais de Atendimento Skywave Fibra"; ?>
        </h1>
        <p class="fs-5 text-muted mx-auto" style="max-width: 700px;">
            Entre em contato com nossa equipe para solicitar um plano personalizado, tirar dúvidas ou receber suporte.
        </p>
    </div>

    <div class="row g-6 justify-content-center">
        <!-- WhatsApp -->
        <div class="col-md-4 col-sm-6">
            <a href="https://wa.me/5575987092386" target="_blank" class="text-decoration-none">
                <div class="card border-0 shadow-sm hover-elevate-up transition bg-success-subtle text-center py-6">
                    <div class="fs-1 text-success mb-3">
                        <i class="ki-outline ki-whatsapp"></i>
                    </div>
                    <h4 class="fw-bold text-success">WhatsApp</h4>
                    <p class="text-muted fs-6 mb-0">(75) 8709-2386</p>
                </div>
            </a>
        </div>

        <!-- Instagram -->
        <div class="col-md-4 col-sm-6">
            <a href="https://instagram.com/skywave_fibra" target="_blank" class="text-decoration-none">
                <div class="card border-0 shadow-sm hover-elevate-up transition bg-light-danger text-center py-6">
                    <div class="fs-1 text-danger mb-3">
                        <i class="ki-outline ki-instagram"></i>
                    </div>
                    <h4 class="fw-bold text-danger">Instagram</h4>
                    <p class="text-muted fs-6 mb-0">@skywave_fibra</p>
                </div>
            </a>
        </div>

        <!-- Facebook -->
        <div class="col-md-4 col-sm-6">
            <a href="https://facebook.com/SkywaveFibra" target="_blank" class="text-decoration-none">
                <div class="card border-0 shadow-sm hover-elevate-up transition bg-light-primary text-center py-6">
                    <div class="fs-1 text-primary mb-3">
                        <i class="ki-outline ki-facebook"></i>
                    </div>
                    <h4 class="fw-bold text-primary">Facebook</h4>
                    <p class="text-muted fs-6 mb-0">Skywave Fibra</p>
                </div>
            </a>
        </div>
    </div>

    <div class="text-center mt-10">
        <a href="<?= url("app") ?>" class="btn btn-outline-secondary px-6 py-3">
            <i class="ki-outline ki-arrow-left fs-3 me-2"></i> Voltar ao Dashboard
        </a>
    </div>
</div>

<style>
    .hover-elevate-up {
        transition: all 0.3s ease;
        border-radius: 16px;
    }

    .hover-elevate-up:hover {
        transform: translateY(-6px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
    }

    .bg-success-subtle {
        background-color: #e6f8ec !important;
    }
</style>