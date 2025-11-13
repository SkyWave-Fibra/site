<?php $this->layout("_theme"); ?>

<div class="card shadow-sm mb-5">
    <div class="card-header d-flex justify-content-between align-items-center">
        <div>
            <h3 class="fw-bold mb-0"><?= $isEdit ? 'Editar Equipamento' : 'Novo Equipamento'; ?></h3>
            <span class="text-muted"><?= $isEdit ? 'Atualize as informações do equipamento' : 'Cadastre um novo equipamento'; ?></span>
        </div>

        <div class="d-flex align-items-center gap-3">

            <?php if ($isEdit): ?>
                <a href="javascript:void(0);"
                    id="deleteEquipmentBtn"
                    data-posturl="<?= url("/app/equipment/delete") ?>"
                    data-id="<?= $equipment->id; ?>"
                    data-action="delete"
                    data-confirm="Tem certeza que deseja deletar este equipamento?"
                    class="btn btn-light-danger rounded-pill px-5 fw-semibold shadow-sm hover-scale"
                    style="display:flex; align-items:center; gap:6px;">
                    <i class="ki-outline ki-trash fs-3"></i>
                    Excluir
                </a>
            <?php endif; ?>

            <button type="submit" form="equipmentForm" class="btn btn-primary">
                <i class="ki-outline ki-save fs-4 me-2"></i> Salvar
            </button>

        </div>
    </div>


    <form id="equipmentForm" method="post" action="<?= url('/app/equipment/save'); ?>" class="form">
        <div class="card-body p-9">
            <?php if (!empty($allocatedCustomer)): ?>

                <?php
                $person = $allocatedCustomer->person();
                $plan = $allocatedCustomer->plan();
                ?>

                <div class="alert alert-warning d-flex align-items-center p-5 mb-7">
                    <i class="ki-outline ki-information fs-2x me-4"></i>

                    <div class="d-flex flex-column">
                        <h5 class="mb-1 fw-bold">Equipamento alocado</h5>

                        <span class="text-gray-800">
                            Este equipamento está atualmente alocado para:
                        </span>

                        <div class="mt-2">
                            <strong><?= $person->full_name ?></strong>
                            <br>
                            <span class="text-muted">CPF/CNPJ: <?= $person->document ?></span>
                            <br>
                            <span class="text-muted">Plano: <?= $plan ? $plan->name : '-' ?></span>
                        </div>

                        <div class="mt-4">
                            <a href="<?= url('/app/cliente/' . $allocatedCustomer->person_id) ?>"
                                class="btn btn-light-primary btn-sm rounded-pill">
                                Ver Cliente
                            </a>
                        </div>

                    </div>
                </div>

            <?php endif; ?>

            <input type="hidden" name="id" value="<?= $equipment->id ?? ''; ?>">

            <div class="row mb-6">
                <label class="col-lg-3 col-form-label fw-semibold">Tipo</label>
                <div class="col-lg-9">
                    <select name="type" class="form-select form-select-solid" required>
                        <?php
                        $types = ["onu" => "ONU", "router" => "Roteador", "radio" => "Rádio", "switch" => "Switch", "modem" => "Modem"];
                        foreach ($types as $key => $label): ?>
                            <option value="<?= $key; ?>" <?= ($equipment->type ?? '') === $key ? 'selected' : ''; ?>>
                                <?= $label; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="row mb-6">
                <label class="col-lg-3 col-form-label fw-semibold">Fabricante</label>
                <div class="col-lg-9">
                    <input type="text" name="manufacturer" class="form-control form-control-lg form-control-solid"
                        value="<?= $equipment->manufacturer ?? ''; ?>">
                </div>
            </div>

            <div class="row mb-6">
                <label class="col-lg-3 col-form-label fw-semibold">Modelo</label>
                <div class="col-lg-9">
                    <input type="text" name="model" class="form-control form-control-lg form-control-solid"
                        value="<?= $equipment->model ?? ''; ?>">
                </div>
            </div>

            <div class="row mb-6">
                <label class="col-lg-3 col-form-label fw-semibold">Número de Série</label>
                <div class="col-lg-9">
                    <input type="text" name="serial_number" class="form-control form-control-lg form-control-solid"
                        value="<?= $equipment->serial_number ?? ''; ?>">
                </div>
            </div>

            <div class="row mb-6">
                <label class="col-lg-3 col-form-label fw-semibold">Status</label>
                <div class="col-lg-9">
                    <select name="status" class="form-select form-select-solid">
                        <?php
                        $statuses = [
                            "available"   => "Disponível",
                            "allocated"   => "Alocado",
                            "maintenance" => "Em manutenção",
                            "discarded"   => "Descartado"
                        ];
                        foreach ($statuses as $key => $label): ?>
                            <option value="<?= $key; ?>" <?= ($equipment->status ?? 'available') === $key ? 'selected' : ''; ?>>
                                <?= $label; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
        </div>
    </form>
</div>

<?php $this->start("scripts"); ?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(document).on("click", "#deleteEquipmentBtn", function(e) {
        e.preventDefault();

        const postUrl = $(this).data("posturl");
        const equipmentId = $(this).data("id");
        const confirmMessage = $(this).data("confirm") || "Tem certeza que deseja excluir este equipamento?";

        Swal.fire({
            title: "Excluir Equipamento?",
            text: confirmMessage,
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Sim, excluir",
            cancelButtonText: "Cancelar",
            reverseButtons: true,
            buttonsStyling: false,
            customClass: {
                confirmButton: "btn btn-danger rounded-pill px-5 me-3",
                cancelButton: "btn btn-light-primary rounded-pill px-5"
            }
        }).then((result) => {
            if (result.isConfirmed) {

                $.ajax({
                    url: postUrl,
                    method: "POST",
                    data: {
                        id: equipmentId
                    },
                    dataType: "json",
                    success: function(response) {

                        //redirect
                        if (response.redirect) {
                            window.location.href = response.redirect;
                            return;
                        }

                        //reload
                        if (response.reload) {
                            window.location.reload();
                            return;
                        }

                        //message
                        if (response.message) {
                            (response.message.type === "toast" ?
                                alertRender("toast", response.message.class, response.message.text, response.message.title) :
                                alertRender("fixed", response.message.class, response.message.text)
                            )
                        }
                    },
                    error: function() {
                        Swal.fire({
                            title: "Erro!",
                            text: "Erro ao excluir equipamento.",
                            icon: "error",
                            buttonsStyling: false,
                            confirmButtonText: "OK",
                            customClass: {
                                confirmButton: "btn btn-danger rounded-pill px-5"
                            }
                        });
                    }
                });
            }
        });
    });
</script>

<?php $this->end() ?>