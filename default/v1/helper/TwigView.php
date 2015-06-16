<?php

/**
 * Twig view
 *
 * The Twig view is a custom View class that renders templates using the Twig
 * template language (http://www.twig-project.org/).
 *
 * Two fields that you, the developer, will need to change are:
 * - parserDirectory
 * - parserOptions
 */
class TwigView extends \Slim\View
{
    /**
     * @var string The path to the Twig code directory WITHOUT the trailing slash
     */
    public $parserDirectory = null;
    /**
     *
     * @var array Paths to directories to attempt to load Twig template from
     */
    public $twigTemplateDirs = array();
    /**
     * @var array The options for the Twig environment, see
     * http://www.twig-project.org/book/03-Twig-for-Developers
     */
    public $parserOptions = array();
    /**
     * @var TwigExtension The Twig extensions you want to load
     */
    public $parserExtensions = array();
    
    /**
     * @var TwigEnvironment The Twig environment for rendering templates.
     */
    private $parserInstance = null;
    
    /**
     * Render Twig Template
     *
     * This method will output the rendered template content
     *
     * @param string $template The path to the Twig template, relative to the Twig templates directory.
     * @param null $data
     * @return string
     */
    public function render($template, $data = null)
    {
        $env = $this->getInstance();
        $parser = $env->loadTemplate($template);
        $data = array_merge($this->all(), (array) $data);
        return $parser->render($data);
    }

    /**
     * Creates new TwigEnvironment if it doesn't already exist, and returns it.
     *
     * @return \Twig_Environment
     */
    public function getInstance()
    {
        if (!$this->parserInstance) {
            /**
             * Check if Twig_Autoloader class exists
             * otherwise include it.
             */
            if (!class_exists('\Twig_Autoloader')) {
                require_once $this->parserDirectory . '/Autoloader.php';
            }
            \Twig_Autoloader::register();
            $loader = new \Twig_Loader_Filesystem($this->twigTemplateDirs);
            $this->parserInstance = new \Twig_Environment(
                $loader,
                $this->parserOptions
            );
            foreach ($this->parserExtensions as $ext) {
                $extension = is_object($ext) ? $ext : new $ext;
                $this->parserInstance->addExtension($extension);
            }
        }
        return $this->parserInstance;
    }
}
