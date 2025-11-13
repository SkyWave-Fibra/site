<?php $this->layout("_theme", ["title" => "Recupere sua senha para acessar o " . CONF_SITE_NAME]); ?>

<tr>
    <td style="padding: 48px 24px 0; color: #161c2d; font-size: 18px; font-weight: 600;">
        Perdeu a sua senha, <?= $full_name; ?>?
    </td>
</tr>
<tr>
    <td style="padding: 15px 24px 15px; color: #8492a6;">
        Você está recebendo este e-mail pois foi solicitado a recuperação de senha no <?= CONF_SITE_NAME ?>.
    </td>
</tr>

<tr>
    <td style="padding: 15px 24px;">
        <a title='Recuperar Senha' href="<?= $forget_link; ?>" class="btn-primary" style="outline:none;text-decoration:none;font-size:16px;letter-spacing:0.5px;font-weight:600;border-radius:6px;font-family:Helvetica,Arial,sans-serif;line-height:20px;display:inline-block;white-space:nowrap;background-color:#007bff;color:#ffffff;padding:8px 20px;border:1px solid #007bff">Clique aqui para criar uma nova senha</a>
    </td>
</tr>

<tr>
    <td style="padding: 15px 24px 0; color: #8492a6;">
        <b>IMPORTANTE:</b> Caso você não tenha feito essa solicitação, ignore. Seus dados permanecem seguros.
    </td>
</tr>

<tr>
    <td style="padding: 15px 24px 15px; color: #8492a6;">
        Equipe <strong><?= CONF_SITE_NAME ?></strong>
    </td>
</tr>

<tr>
    <td style="padding: 16px 8px; color: #8492a6; background-color: #f8f9fc; text-align: center;">
        <?= date("Y") . " | " . CONF_SITE_NAME ?>
    </td>
</tr>