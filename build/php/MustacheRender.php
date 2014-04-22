<?php
/**
 * MustacheRender class file.
 *
 * @author Qi Changhai <qi.changhai@sh.adways.net>
 * @package phing.filters
 */
/**
 * MustacheRender render a file using mustache engine.
 *
 * @author Qi Changhai <qi.changhai@sh.adways.net>
 * @package phing.filters
 */
include_once 'phing/filters/BaseParamFilterReader.php';
include_once 'phing/filters/ChainableReader.php';

AutoLoader::register(array('build/php/mustache'));

class MustacheRender extends BaseParamFilterReader implements ChainableReader
{
    protected $params;

    /**
     * Set params data
     * @param array params used to render mustache file.
     */
    public function setParams($value)
    {
        $this->params = $value;
    }

    /**
     * Returns params used to render mustache file.
     * @return array params used to render mustache file.
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * Returns stream after rendering with mustache
     *
     * @return the resulting stream, or -1
     *         if the end of the resulting stream has been reached
     *
     * @exception IOException if the underlying stream throws an IOException
     *            during reading
     */
    public function read($len = null)
    {
        if ( !$this->getInitialized() ) {
            $this->_initialize();
            $this->setInitialized(true);
        }

        $buffer = $this->in->read($len);

        if($buffer === -1) {
            return -1;
        }

        $m = new Mustache_Engine;
        $buffer = $m->render($buffer, $this->params);

        return $buffer;
    }



    /**
     * Creates a new MustacheRender using the passed in
     * Reader for instantiation.
     *
     * @param Reader $reader A Reader object providing the underlying stream.
     *               Must not be <code>null</code>.
     *
     * @return Reader A new filter based on this configuration, but filtering
     *         the specified reader
     */
    function chain(Reader $reader) {
        $newFilter = new MustacheRender($reader);
        $newFilter->setParams($this->getParams());
        $newFilter->setInitialized(true);
        $newFilter->setProject($this->getProject());
        return $newFilter;
    }

    /**
     * Parses the parameters
     */
    private function _initialize()
    {
        if($this->getParameters()){
            $params = $this->getParameters();
        }else{
            $project = $this->getProject();
            $params = $project->getProperties();
        }

        $this->params = $this->_expandParams($params);
    }

    /**
     * Expand the properties of phing to multi-dimensinal array.
     * @return array
     */
    public function _expandParams($params)
    {
        $result = array();
        foreach($params as $key => $value){
            $ref = &$result;
            $subKeys = explode('.', $key);
            for($i = 0; $i < count($subKeys) - 1; $i++){
                $subKey = $subKeys[$i];
                if(!isset($ref[$subKey])){
                    $ref[$subKey] = array();
                }else{
                    /*
                     * If properties are specified like:
                     *
                     *  foo.bar = 1
                     *  foo.bar.1 = 1
                     *
                     * then the later will be ignored as it can't be expanded as array.
                     */
                    if(is_scalar($ref[$subKey])){
                        continue 2;
                    }
                }
                $ref = &$ref[$subKey];
            }
            $ref[$subKeys[$i]] = $value;
        }
        return $result;
    }
}

final class AutoLoader
{
    private $baseDirs;
    private function __construct($baseDirs = array()){
        if (count($baseDirs)==0) {
            $baseDirs[] = getcwd().'/..';
        } else {
            foreach($baseDirs as &$dir){
                $dir = rtrim($dir, '/');
            }
            $this->baseDirs = $baseDirs;
        }
    }

    public static function register($baseDirs = array()){
        $loader = new self($baseDirs);
        spl_autoload_register(array($loader, 'autoload'));
    }

    public function autoload($class){
        if ($class[0] === '\\') {
            $class = substr($class, 1);
        }
        foreach ($this->baseDirs as $dir) {
            $file = sprintf('%s/%s.php', $dir, $class);
            if (file_exists($file)) {
                require $file;
                return;
            }
        }
    }
}
