<?php $this->layout("_theme"); ?>

<div class="card shadow-sm mb-10">
    <div class="card-header d-flex justify-content-between align-items-center py-5">
        <div>
            <h3 class="fw-bold mb-0"><?= $isEdit ? 'Editar Funcionário' : 'Novo Funcionário'; ?></h3>
            <span class="text-muted"><?= $isEdit ? 'Atualize as informações do funcionário' : 'Cadastre um novo funcionário'; ?></span>
        </div>

        <button type="submit" form="employeeForm" class="btn btn-primary">
            <i class="ki-outline ki-save fs-4 me-2"></i> Salvar
        </button>
    </div>

    <div class="card-body p-9">
        <!-- ========================== -->
        <!-- DADOS PESSOAIS (somente leitura) -->
        <!-- ========================== -->
        <div class="d-flex justify-content-between align-items-center mb-7">
            <h4 class="fw-bold text-primary mb-0">Dados Pessoais</h4>

            <!-- Botão para editar a pessoa -->
            <a href="<?= url('/app/usuario/' . ($employee->account()->id ?? '')); ?>"
                class="btn btn-light-primary btn-sm">
                <i class="ki-outline ki-pencil fs-4 me-2"></i>
                Editar dados pessoais
            </a>
        </div>

        <div class="row mb-6">
            <label class="col-lg-3 fw-semibold text-muted">Nome Completo</label>
            <div class="col-lg-9 d-flex align-items-center">
                <span class="fs-5 fw-semibold"><?= $employee->person->full_name ?? '—'; ?></span>
            </div>
        </div>

        <div class="row mb-6">
            <label class="col-lg-3 fw-semibold text-muted">Documento</label>
            <div class="col-lg-9 d-flex align-items-center">
                <span class="fs-5 fw-semibold"><?= $employee->person->document ?? '—'; ?></span>
            </div>
        </div>

        <div class="row mb-6">
            <label class="col-lg-3 fw-semibold text-muted">Data de Nascimento</label>
            <div class="col-lg-9 d-flex align-items-center">
                <span class="fs-5 fw-semibold">
                    <?= !empty($employee->person->birth_date)
                        ? date("d/m/Y", strtotime($employee->person->birth_date))
                        : '—'; ?>
                </span>
            </div>
        </div>

        <hr class="my-10">

        <!-- ========================== -->
        <!-- FORMULÁRIO DE EMPREGO (editável) -->
        <!-- ========================== -->
        <form id="employeeForm" method="post" action="<?= url('/app/funcionarios/salvar'); ?>" class="form">
            <input type="hidden" name="person_id" value="<?= $employee->id ?? ''; ?>">

            <h4 class="fw-bold mb-5 text-primary">Informações do Emprego</h4>

            <!-- Cargo -->
            <div class="row mb-6">
                <label class="col-lg-3 col-form-label fw-semibold">Cargo</label>
                <div class="col-lg-9">
                    <select name="role" class="form-select form-select-solid" required>
                        <?php
                        $roles = [
                            "admin"      => "Administrador",
                            "support"    => "Atendimento",
                            "technician" => "Técnico",
                            "finance"    => "Financeiro"
                        ];
                        foreach ($roles as $key => $label): ?>
                            <option value="<?= $key; ?>" <?= ($employee->role ?? '') === $key ? 'selected' : ''; ?>>
                                <?= $label; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <!-- Função -->
            <div class="row mb-6">
                <label class="col-lg-3 col-form-label fw-semibold">Função</label>
                <div class="col-lg-9">
                    <input type="text"
                        name="role_name"
                        class="form-control form-control-lg form-control-solid"
                        value="<?= $employee->role_name ?? ''; ?>"
                        placeholder="Ex: Técnico de rede, Analista financeiro...">
                </div>
            </div>

            <!-- Data de Admissão -->
            <div class="row mb-6">
                <label class="col-lg-3 col-form-label fw-semibold">Data de Admissão</label>
                <div class="col-lg-9">
                    <input type="date"
                        name="hire_date"
                        class="form-control form-control-lg form-control-solid"
                        value="<?= $employee->hire_date ?? date('Y-m-d'); ?>"
                        required>
                </div>
            </div>

            <!-- Status -->
            <div class="row mb-6">
                <label class="col-lg-3 col-form-label fw-semibold">Status</label>
                <div class="col-lg-9">
                    <select name="status" class="form-select form-select-solid">
                        <option value="active" <?= ($employee->status ?? '') === 'active' ? 'selected' : ''; ?>>Ativo</option>
                        <option value="terminated" <?= ($employee->status ?? '') === 'terminated' ? 'selected' : ''; ?>>Desligado</option>
                    </select>
                </div>
            </div>
        </form>
    </div>
</div>

<?php $this->start("scripts"); ?>
<?php $this->end(); ?>