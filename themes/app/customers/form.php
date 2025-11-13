<?php $this->layout("_theme"); ?>

<div class="card">
    <div class="card-header border-0 pt-6">
        <div class="card-title flex-column">
            <h3 class="fw-bold mb-1"><?= $title ?? 'Associar Cliente'; ?></h3>
            <span class="text-muted fs-7"><?= $subtitle ?? ''; ?></span>
        </div>
    </div>

    <div class="card-body py-4">

        <?php if (!empty($person)): ?>
            <!-- 🔹 Modo edição -->
            <div class="border rounded p-4 bg-light mb-5">
                <div class="card bg-light mb-5">
                    <div class="card-body py-5">

                        <h5 class="fw-bold text-primary mb-4">
                            <i class="ki-outline ki-user fs-2 me-2"></i>
                            Cliente encontrado
                        </h5>

                        <div class="separator separator-dashed my-4"></div>

                        <div class="mb-4">
                            <span class="fw-semibold text-gray-600 d-block">Nome</span>
                            <span class="fw-bold"><?= $person->full_name ?></span>
                        </div>

                        <div class="mb-4">
                            <span class="fw-semibold text-gray-600 d-block">E-mail</span>
                            <span class="fw-bold"><?= $account->email ?></span>
                        </div>

                        <div class="mb-4">
                            <span class="fw-semibold text-gray-600 d-block">Documento</span>
                            <span class="fw-bold"><?= $person->document ?></span>
                        </div>

                        <div class="mb-4">
                            <span class="fw-semibold text-gray-600 d-block">Plano Atual</span>
                            <span class="badge badge-light-primary fw-bold">
                                <?= $activePlan ? $activePlan->name : '-' ?>
                            </span>
                        </div>

                        <div class="">
                            <span class="fw-semibold text-gray-600 d-block">Equipamento Atual</span>
                            <span class="fw-bold">
                                <?= $activeEquipment
                                    ? "{$activeEquipment->type} - {$activeEquipment->manufacturer} - {$activeEquipment->model}"
                                    : '-' ?>
                            </span>
                        </div>

                    </div>
                </div>


                <?php if ($activePlan): ?>
                    <div class="text-end mt-3">
                        <button type="button"
                            class="btn btn-light-danger rounded-pill px-5 py-2 fw-semibold shadow-sm hover-scale"
                            id="cancelPlanBtn"
                            data-person-id="<?= $person->id; ?>"
                            style="display:inline-flex;align-items:center;gap:6px;">
                            <i class="ki-outline ki-cross-circle fs-3"></i>
                            Cancelar Plano
                        </button>
                    </div>
                <?php endif; ?>
            </div>

            <!-- 🔹 Formulário de edição -->
            <form id="clientForm" action="<?= url('/app/client/save'); ?>" method="post">
                <input type="hidden" name="person_id" value="<?= $person->id; ?>">

                <div class="row g-6">
                    <!-- Plano -->
                    <div class="col-md-6">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <label class="form-label fw-semibold m-0">Plano</label>
                        </div>

                        <select name="plan_id" id="plan_id" class="form-select form-select-solid" required>
                            <option value="">-- selecione um plano --</option>
                            <?php foreach ($plans ?? [] as $p): ?>
                                <option value="<?= $p->id; ?>"
                                    <?= ($activePlan && $p->id == $activePlan->id) ? 'selected' : ''; ?>>
                                    <?= $p->summary(); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>


                    <!-- Equipamento -->
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Equipamento</label>
                        <select name="equipment_id" id="equipment_id" class="form-select form-select-solid" required>
                            <option value="">-- selecione equipamento --</option>
                            <?php foreach ($equipments ?? [] as $eq): ?>
                                <option value="<?= $eq->id; ?>"
                                    <?= ($activeEquipment && $eq->id == $activeEquipment->id) ? 'selected' : ''; ?>>
                                    <?= "{$eq->type} - {$eq->manufacturer} - {$eq->model}"; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="mt-6 text-end">
                    <a href="<?= url('/app/clientes'); ?>" class="btn btn-light me-3">Voltar</a>
                    <button type="submit" class="btn btn-success">
                        <i class="ki-outline ki-save fs-3"></i> Salvar Alterações
                    </button>
                </div>
            </form>

        <?php else: ?>
            <!-- 🔹 Modo cadastro -->
            <form id="searchForm" action="<?= url('/app/clientes/buscar'); ?>" method="post" class="ajax-off">

                <div class="card shadow-sm p-6 mb-7">
                    <h4 class="fw-bold mb-5 text-primary">
                        Buscar Cliente
                    </h4>

                    <div class="d-flex flex-column flex-md-row align-items-stretch gap-4">

                        <!-- Campo de busca -->
                        <div class="position-relative flex-grow-1">
                            <i class="ki-duotone ki-search-list fs-2 position-absolute top-50 translate-middle-y ms-4 text-gray-400"></i>
                            <input
                                type="text"
                                id="searchDocument"
                                name="document"
                                class="form-control form-control-lg form-control-solid ps-14 py-5"
                                placeholder="Digite o CPF ou CNPJ do cliente..." />
                        </div>

                        <!-- Botão -->
                        <button type="submit" class="btn btn-primary btn-lg px-8 shadow-sm fw-semibold d-flex align-items-center">
                            <i class="ki-duotone ki-search-check fs-2 me-2"></i>
                            Buscar
                        </button>

                    </div>
                </div>

            </form>


            <div id="clientResult" class="d-none">
                <div class="border rounded p-4 bg-light mb-5">
                
                    <div class="card bg-light mb-5">
                        <div class="card-body py-5">

                            <h5 class="fw-bold text-primary mb-4">
                                <i class="ki-outline ki-user fs-2 me-2"></i>
                                Cliente encontrado
                            </h5>

                            <div class="separator separator-dashed my-4"></div>

                            <div class="mb-4">
                                <span class="fw-semibold text-gray-600 d-block">Nome</span>
                                <span class="fw-bold"><span id="p_name">-</span></span>
                            </div>

                            <div class="mb-4">
                                <span class="fw-semibold text-gray-600 d-block">E-mail</span>
                                <span class="fw-bold"><span id="p_email">-</span></span>
                            </div>

                            <div class="mb-4">
                                <span class="fw-semibold text-gray-600 d-block">Documento</span>
                                <span class="fw-bold"><span id="p_doc">-</span></span>
                            </div>

                            <div class="mb-4">
                                <span class="fw-semibold text-gray-600 d-block">Plano Atual</span>
                                <span class="badge badge-light-primary fw-bold">
                                    <span id="p_plan">-</span>
                                </span>
                            </div>

                            <div class="">
                                <span class="fw-semibold text-gray-600 d-block">Equipamento Atual</span>
                                <span class="fw-bold">
                                    <span id="p_eq">-</span>
                                </span>
                            </div>

                        </div>
                    </div>

                </div>

                <!-- 🔹 Formulário de associação -->
                <form id="clientForm" action="<?= url('/app/client/save'); ?>" method="post">
                    <input type="hidden" name="person_id" id="person_id">

                    <div class="row g-6">
                        <!-- Plano -->
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Plano</label>
                            <div id="planContainer"></div>
                        </div>

                        <!-- Equipamento -->
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Equipamento</label>
                            <select name="equipment_id" id="equipment_id" class="form-select form-select-solid" required>
                                <option value="">-- selecione equipamento --</option>
                                <?php foreach ($equipments ?? [] as $eq): ?>
                                    <option value="<?= $eq->id; ?>">
                                        <?= "{$eq->type} - {$eq->manufacturer} - {$eq->model}"; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="mt-6 text-end">
                        <a href="<?= url('/app/clientes'); ?>" class="btn btn-light me-3">Cancelar</a>
                        <button type="submit" class="btn btn-success">
                            <i class="ki-outline ki-save fs-3"></i> Salvar Associação
                        </button>
                    </div>
                </form>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php $this->start("scripts"); ?>

<script>
    $(function() {

        /** ========================================
         *  CANCELAR PLANO – FUNCIONA EM QUALQUER MODO
         * ========================================= */
        $(document).off('click', '#cancelPlanBtn').on('click', '#cancelPlanBtn', function() {

            const personId = $(this).data('person-id') || $('#person_id').val();

            if (!personId) {
                alertRender("toast", "warning", "Cliente não identificado.");
                return;
            }

            if (!confirm("Deseja realmente cancelar o plano atual deste cliente?")) return;

            $.ajax({
                url: "<?= url('/app/clientes/cancelar-plano'); ?>",
                method: "POST",
                data: {
                    person_id: personId
                },
                dataType: "json",
                success: function(res) {
                    if (res.success) {
                        alertRender("toast", "success", res.message || "Plano cancelado com sucesso!");
                        setTimeout(() => window.location.reload(), 1200);
                    } else {
                        alertRender("toast", "warning", res.message || "Não foi possível cancelar o plano.");
                    }
                },
                error: () => alertRender("toast", "danger", "Erro ao cancelar o plano.")
            });
        });


        <?php if (empty($person)): ?>
            /** ========================================
             *  PARTE ESPECÍFICA DO MODO BUSCA
             * ========================================= */
            const planContainer = $('#planContainer');

            $('#searchForm').on('submit', function(e) {
                e.preventDefault();
                const data = new FormData(this);

                $.ajax({
                    url: this.action,
                    data,
                    type: "POST",
                    dataType: "json",
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function(res) {

                        if (!res.found) {
                            alertRender("toast", "warning", "Cliente não encontrado");
                            return;
                        }

                        $('#clientResult').removeClass('d-none');
                        $('#person_id').val(res.person.id);
                        $('#p_name').text(res.person.full_name);
                        $('#p_email').text(res.account?.email ?? '-');
                        $('#p_doc').text(res.person.document);
                        $('#p_plan').text(res.customer?.plan ?? '-');

                        // Botão cancelar (se houver plano)
                        let cancelBtn = res.customer?.plan_id ?
                            `` :
                            '';

                        let planOptions = `
                        <option value="">-- selecione um plano --</option>
                        <?php foreach ($plans ?? [] as $p): ?>
                            <option value="<?= $p->id; ?>"><?= $p->summary(); ?></option>
                        <?php endforeach; ?>
                    `;

                        planContainer.html(`
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            ${cancelBtn}
                        </div>
                        <select name="plan_id" id="plan_id" class="form-select form-select-solid" required>
                            ${planOptions}
                        </select>
                    `);

                        if (res.customer?.plan_id) {
                            $('#plan_id').val(res.customer.plan_id);
                        }

                        if (res.active_equipment) {
                            $('#equipment_id').val(res.active_equipment.equipment_id);
                            setTimeout(() => {
                                let selectedText = $('#equipment_id option:selected').text();
                                if (!selectedText.trim()) selectedText = res.active_equipment.equipment_name;
                                $('#p_eq').text(selectedText);
                            }, 100);
                        }

                        $('#searchForm').addClass('d-none');
                    },
                    error: () => alertRender("toast", "danger", "Erro na requisição")
                });
            });
        <?php endif; ?>

    });
</script>

<?php $this->end(); ?>