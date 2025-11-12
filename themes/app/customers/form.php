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
                <h5 class="fw-semibold mb-3">Cliente encontrado</h5>
                <p class="mb-1"><strong>Nome:</strong> <?= $person->full_name ?? '-'; ?></p>
                <p class="mb-1"><strong>E-mail:</strong> <?= $account->email ?? '-'; ?></p>
                <p class="mb-1"><strong>Documento:</strong> <?= $person->document ?? '-'; ?></p>
                <p class="mb-1"><strong>Plano atual:</strong> <?= $activePlan ? $activePlan->name : '-'; ?></p>
                <p><strong>Equipamento atual:</strong>
                    <?= $activeEquipment
                        ? "{$activeEquipment->type} - {$activeEquipment->manufacturer} - {$activeEquipment->model}"
                        : '-'; ?>
                </p>
            </div>

            <!-- 🔹 Formulário de edição -->
            <form id="clientForm" action="<?= url('/app/client/save'); ?>" method="post">
                <input type="hidden" name="person_id" value="<?= $person->id; ?>">

                <div class="row g-6">
                    <!-- Plano -->
                    <div class="col-md-6">
                        <label class="form-label fw-semibold d-flex align-items-center justify-content-between">
                            <span>Plano</span>
                            <?php if ($activePlan): ?>
                                <button type="button"
                                    class="btn btn-danger btn-sm ms-3"
                                    id="cancelPlanBtn"
                                    data-person-id="<?= $person->id; ?>">
                                    <i class="ki-outline ki-cross fs-4"></i> Cancelar plano
                                </button>
                            <?php endif; ?>
                        </label>

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
                <div class="d-flex align-items-center mb-5">
                    <div class="position-relative me-3">
                        <i class="ki-outline ki-search fs-3 position-absolute ms-4 top-50 translate-middle-y text-gray-400"></i>
                        <input type="text" id="searchDocument" name="document" class="form-control form-control-solid ps-13"
                            placeholder="Digite o CPF ou CNPJ do cliente..." />
                    </div>
                    <button type="submit" class="btn btn-primary">
                        <i class="ki-outline ki-magnifier fs-2"></i> Buscar
                    </button>
                </div>
            </form>

            <div id="clientResult" class="d-none">
                <div class="border rounded p-4 bg-light mb-5">
                    <h5 class="fw-semibold mb-3">Cliente encontrado</h5>
                    <p class="mb-1"><strong>Nome:</strong> <span id="p_name">-</span></p>
                    <p class="mb-1"><strong>E-mail:</strong> <span id="p_email">-</span></p>
                    <p class="mb-1"><strong>Documento:</strong> <span id="p_doc">-</span></p>
                    <p class="mb-1"><strong>Plano atual:</strong> <span id="p_plan">-</span></p>
                    <p><strong>Equipamento atual:</strong> <span id="p_eq">-</span></p>
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
<?php if (empty($person)): ?>
    <script>
        $(function() {
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

                        // 🔹 Exibe o select sempre, mas adiciona o botão de cancelar se tiver plano ativo
                        let cancelBtn = '';
                        if (res.customer?.plan_id) {
                            cancelBtn = `
                                <button type="button"
                                        class="btn btn-danger btn-sm ms-3"
                                        id="cancelPlanBtn"
                                        data-person-id="${res.person.id}">
                                    <i class="ki-outline ki-cross fs-4"></i> Cancelar plano
                                </button>`;
                        }


                        let planOptions = `
                            <option value="">-- selecione um plano --</option>
                            <?php foreach ($plans ?? [] as $p): ?>
                                <option value="<?= $p->id; ?>"><?= $p->summary(); ?></option>
                            <?php endforeach; ?>
                        `;

                        planContainer.html(`
                            <label class="form-label fw-semibold d-flex align-items-center justify-content-between">
                                <span>Plano</span>
                                ${cancelBtn}
                            </label>
                            <select name="plan_id" id="plan_id" class="form-select form-select-solid" required>
                                ${planOptions}
                            </select>
                        `);

                        // Seleciona o plano atual (se existir)
                        if (res.customer?.plan_id) {
                            $('#plan_id').val(res.customer.plan_id);
                        }


                        if (res.active_equipment) {
                            $('#equipment_id').val(res.active_equipment.equipment_id);
                            setTimeout(() => {
                                let selectedText = $('#equipment_id option:selected').text();
                                if (!selectedText || selectedText.trim() === '') {
                                    selectedText = res.active_equipment.equipment_name;
                                }
                                $('#p_eq').text(selectedText);
                            }, 100);
                        } else {
                            $('#equipment_id').val('');
                            $('#p_eq').text('-');
                        }

                        $('#searchForm').addClass('d-none');
                    },
                    error: () => alertRender("toast", "danger", "Erro na requisição")
                });
            });

            // 🔹 Listener universal (funciona mesmo para botões injetados via AJAX)
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
                            setTimeout(() => window.location.reload(), 1500);
                        } else {
                            alertRender("toast", "warning", res.message || "Não foi possível cancelar o plano.");
                        }
                    },
                    error: () => alertRender("toast", "danger", "Erro ao cancelar o plano.")
                });
            });

        });
    </script>
<?php endif; ?>
<?php $this->end(); ?>