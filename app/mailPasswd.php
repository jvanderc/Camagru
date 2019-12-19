<?php

$headers  = 'MIME-Version: 1.0' . '\n';
$headers .= 'Content-type: text/html; charset=UTF-8' . '\n';
$headers .= 'From: <jvanderc@student.s19.be>' . '\n';
$body = '
<html>
    <body>
        <p>Bonjour ' . $this->login . ',<p>
        Pour modifier votre mot de passe veuillez cliquer sur le lien suivant :
        <a href=http://' . $url . '>Suivez-moi</a>
        A bientot.
    </body>
</html>';

if (mail($email, 'Camagru', $body, $headers))
    return $this->message = 'Mail envoyé';
else
    return $this->message = 'Mail pas envoyé';