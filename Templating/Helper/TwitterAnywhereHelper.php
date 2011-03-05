<?php

namespace Kris\TwitterBundle\Templating\Helper;

use Symfony\Component\Templating\Helper\Helper;
use Symfony\Component\Templating\EngineInterface;

class TwitterAnywhereHelper extends Helper
{
    protected $templating;
    protected $apiKey;
    protected $version;
    protected $callbackURL;

    protected $config = array();
    protected $scripts = array();

    public function __construct(EngineInterface $templating, $apiKey, $version = 1)
    {
        $this->templating = $templating;
        $this->apiKey = $apiKey;
        $this->version = $version;
    }

    /*
     * 
     */
    public function setup($parameters = array(), $name = null)
    {
        $name = $name ?: 'KrisTwitterBundle::setup.html.php';
        return $this->templating->render($name, $parameters + array(
            'apiKey'      => $this->apiKey,
            'version'     => $this->version,
        ));
    }

    /*
     * 
     */
    public function initialize($parameters = array(), $name = null)
    {
        //convert config array to map
        $configMap = null;
        foreach($this->config as $key => $value){
            $configMap .= $key.": ".$value;
        }

        //convert scripts into lines
        $lines = '';
        foreach ($this->scripts as $script) {
            $lines .= rtrim($script, ';').";\n";
        }        

        $name = $name ?: 'KrisTwitterBundle::initialize.html.php';
        return $this->templating->render($name, $parameters + array(
            'configMap'     => $configMap,
            'scripts'       => $lines,
        ));
    }

    /*
     *
     */
    public function queue($script)
    {
        $this->scripts[] = $script;
    }

    /*
     * 
     */
    public function setConfig($key, $value)
    {
        $this->config[$key] = '"'.$value.'"';
    }

    /**
     * @codeCoverageIgnore
     */
    public function getName()
    {
        return 'twitter_anywhere';
    }
}
