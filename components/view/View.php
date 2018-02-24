<?php

namespace components\view;


class View
{
    public function generate($contentVar){

        \Twig_Autoloader::register();

        try {
            $loader = new \Twig_Loader_Filesystem('../templates/');
            $twig = new \Twig_Environment($loader);
            $template = $twig->loadTemplate('bases.tmpl');
            $page = $twig->loadTemplate($contentVar['page']);

            echo $template->render([
                    'images' => $contentVar['images'],
                    'page'    => $page,
                ]
            );
        } catch (Exception $e) {
            echo '<b>Мы не нашли шаблоны, но вот вам котики';

        }
    }
}