<!doctype html>
<html lang="pt-br">

<head>
    <meta name="viewport" content="width=device-width" />
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title><?= $title; ?></title>
    <!-- Font -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:400,600,700&display=swap" rel="stylesheet">

</head>

<body style="font-family: Nunito, sans-serif; font-size: 15px; font-weight: 400;">

    <table style="margin-left: auto; margin-right: auto; box-sizing: border-box; width: 95%; border-radius: 6px; overflow: hidden; background-color: #fff; box-shadow: 0 0 3px rgba(60, 72, 88, 0.15);">
        <thead>
            <tr style="background-color: #202942; padding: 3px 0; line-height: 28px; text-align: center; color: #fff; font-size: 24px; font-weight: 700; letter-spacing: 1px;">
                <th scope="col">
                    <a href="<?= CONF_URL_BASE; ?>"><img src="<?= CONF_URL_BASE . "/shared/assets/images/front/header.png"; ?>" height="80" alt="" style="padding: 15px;"></a>
                </th>
            </tr>
        </thead>

        <tbody>

            <?= $this->section("content"); ?>

        </tbody>
    </table>

</body>

</html>