<?php

use Silex\Application,
    Symfony\Component\HttpFoundation\Request;

/**
 * [init_mollom description]
 * @param  Application $app [description]
 * @return [type]           [description]
 */
function mollom_init(Application $app)
{
    static $done = false;

    $r = ($app['mollom'] && $app['mollom']['priv_key'] && $app['mollom']['pub_key']);

    if (!$done) {
        require __DIR__ . '/../vendor/mollom/mollom.php';
        $done = true;

        if ($r) {
            Mollom::setPrivateKey($app['mollom']['priv_key']);
            Mollom::setPublicKey($app['mollom']['pub_key']);
            Mollom::setServerList(mollom_get_servers());
        }
    }

    return $r;
}

/**
 * [mollom_get_servers description]
 *
 * @return  array [description]
 */
function mollom_get_servers()
{
    $file = __DIR__ . '/../config/mollom-servers.json';
    $got  = is_file($file);
    $list = $got ? @json_decode(file_get_contents($file)) : false;

    if (!$got || !$list || filemtime($file) < time() - 2592000) {
        $list = Mollom::getServerList();

        @file_put_contents($file, json_encode($list));
    }

    return $list;
}

/**
 * [mollom_check_content description]
 *
 * @param  array  $from     [description]
 * @param  string $message  [description]
 * @return bool             [description]
 */
function mollom_check_content(array $from, $message)
{
    return Mollom::checkContent(null, null, $message, $from['name'], null, $from['email']);
}

/**
 * [send_the_email description]
 *
 * @param  Application $app     [description]
 * @param  array       $from    [description]
 * @param  string      $message [description]
 * @return bool                 [description]
 */
function send_the_email(Application $app, array $from, $message)
{
    $msg = \Swift_Message::newInstance()
        ->setSubject('Bericht via website (springvloedfysio.nl)')
        ->setFrom($app['emails']['site_from'])
        ->setTo($app['emails']['site_to'])
        ->setReplyTo(array($from['email'] => $from['name']))
        ->setBody($message);

    return $app['mailer']->send($msg);
}

function get_email_sender_from_req(Request $req)
{
    if (!$req->request->has('email'))
        throw new Exception('email address not provided');
    if (!$req->request->has('name'))
        throw new Exception('name not provided');

    $from = array(
        'email' => trim($req->request->get('email')),
        'name'  => trim($req->request->get('name')),
    );

    if (!strlen($from['email']))
        throw new Exception('email address is empty');

    if (!strlen($from['name']))
        throw new Exception('name is empty');

    return $from;
}

function get_email_message_from_req(Request $req)
{
    if (!$req->request->has('message'))
        throw new Exception('message not provided');

    $msg = trim($req->request->get('message'));

    if (!strlen($msg))
        throw new Exception('message is empty');

    return $msg;
}

/**
 * [gen_email_action_response description]
 *
 * @param  boolean $success [description]
 * @param  array   $xtra    [description]
 * @return sting            [description]
 */
function gen_email_action_response($success = true, $errmsg = null, array $xtra = array())
{
    $base = array('success' => !!$success);

    if (!$success && $errmsg)
        $base['errmsg'] = $errmsg;

    return array_merge($xtra, $base);
}

