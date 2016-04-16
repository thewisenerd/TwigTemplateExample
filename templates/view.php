<?php
    require $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';
    require $_SERVER['DOCUMENT_ROOT'] . '/config.php';

    $template = '';
    if ($_GET['template'] == "") {
        echo 'specify template';
        exit;
    } else {
        $template = $_GET['template'];
    } // _get['template']

    if ($_GET['auth'] == "") {
        echo 'specify auth token';
        exit;
    } else {
        if ($_GET['auth'] != $_config['templateViewAuthToken']) {
            echo 'auth token wrong!';
            exit;
        }
    } // _get['auth']

    // now would be a nice time to define TEMPLATESDIR
    define('TEMPLATESDIR', $_config['templateDir'] . DIRECTORY_SEPARATOR);

    if (!(
        is_readable(TEMPLATESDIR . $template) &&
        is_dir(TEMPLATESDIR . $template)
    )) {
        echo "template does not exist!";
        exit;
    }

    $viewTemplateDir = TEMPLATESDIR . $template . DIRECTORY_SEPARATOR;

    $json = false;
    try {
        if (
            is_readable($viewTemplateDir . 'data.json') &&
            (!(is_dir($viewTemplateDir . 'data.json')))
        ) {
            $jsonRaw = file_get_contents($viewTemplateDir . 'data.json');
            if ($jsonRaw) {
                $json = json_decode($jsonRaw, true);
            }
        }
    } catch (Exception $e) {
        // TODO: fix this
        echo $e;
    }

    $loader = new Twig_Loader_Filesystem(TEMPLATESDIR . $template);
    // TODO: move out of 'debug' mode
    $twig = new Twig_Environment($loader, array('debug' => true));

    try {
        if ($json) {
            echo $twig->render('index.html', $json);
        } else {
            echo $twig->render('index.html');
        }
    } catch (Exception $e) {
        // TODO: fix this
        echo $e;
    }
