<?php $this->layout("_theme", ["title" => "Confirme e ative sua conta no " . CONF_SITE_NAME]); ?>

<tr>
    <td style="padding: 48px 24px 0; color: #161c2d; font-size: 18px; font-weight: 600;">
        Olá <?= $full_name; ?>, seja bem vindo ao <?= CONF_SITE_NAME ?>!
    </td>
</tr>
<tr>
    <td style="padding: 15px 24px 15px; color: #8492a6;">
        Após a confirmação do seu e-mail você já poderá interagir no sistema.
    </td>
</tr>

<tr>
    <td style="padding: 15px 24px;text-align:center;">
        Este é seu código de ativação
        <br>
        <span class="btn-primary" style="outline:none;text-decoration:none;font-size:26px;letter-spacing:2.5px;font-weight:600;border-radius:10px;font-family:Helvetica,Arial,sans-serif;line-height:30px;display:inline-block;white-space:nowrap;background-color:#007bff;color:#ffffff;padding:15px 25px;border:1px solid #007bff"><?= $code ?></span>
    </td>
</tr>

<tr>
    <td style="padding: 15px 24px 0; color: #8492a6;">
        Caso a confirmação não seja efetuada em até 24h a conta será excluída.
        <br>
        Caso você tenha fechado a tela de confirmação <a href="<?= $confirm_link ?>">clique aqui</a> e te
        redirecionaremos.
    </td>
</tr>

<tr>
    <td style="padding: 15px 24px 15px; color: #8492a6;">
        É muito bom tê-lo conosco.
        <br>
        <strong><?= CONF_SITE_NAME ?></strong>
    </td>
</tr>

<tr>
    <td style="padding: 16px 8px; color: #8492a6; background-color: #f8f9fc; text-align: center;">
        <?= date("Y") . " | " . CONF_SITE_NAME ?>
    </td>
</tr>