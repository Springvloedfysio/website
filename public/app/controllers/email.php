<?php

use Symfony\Component\HttpFoundation\Request;

$email = $app['controllers_factory'];

$email->post('/send', function (Request $req) use ($app) {

    require_once __DIR__ . '/../helpers/email.php';

    $success = false;
    $errmsg  = '';

    try {
        $from    = get_email_sender_from_req($req);
        $message = get_email_message_from_req($req);

        if (mollom_init($app)) {
            if (! mollom_check_content($from, $message)) {
                $success = false;
                $errmsg  = 'bad_mole';
            } else {
                $success = send_the_email($app, $from, $message);
            }
        }
    } catch (Exception $e) {
        $errmsg = $app['debug'] ? $e->getMessage() : 'Er ging iets mis.';
    }

    return $app->json(gen_email_action_response($success, $errmsg));

})->before($requireRef)->before($requireAjax)->bind('post_email');


$email->post('/captcha', function (Request $req) use ($app) {

    require_once __DIR__ . '/../helpers/email.php';

    $success = false;
    $errmsg  = '';

    try {
        $from    = get_email_sender_from_req($req);
        $message = get_email_message_from_req($req);

        if (mollom_init($app)) {
            if (mollom_check_captcha($solution)) {
                $success = send_the_email($app, $from, $message);
            } else {
                // ?
            }
        }

    } catch (Exception $e) {
        $errmsg = $e->getMessage();
    }

    return $app->json(gen_email_action_response($success, $errmsg));

})->before($requireRef)->before($requireAjax)->bind('post_email_captcha');


return $email;