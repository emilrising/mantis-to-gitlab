<?php
class MyPDO extends PDO
{
	protected $conf_file;
/*	
	public function set_ini_file($var) {  // You can then perform check on the data etc here
       $this->conf_file = $var;
    }
*/	
    public function __construct($conf_file)
    {
        if (!$settings = parse_ini_file($conf_file, TRUE)) throw new exception('Unable to open ' . $conf_file . '.');
       
        $dns = $settings['database']['driver'] .
        ':host=' . $settings['database']['host'] .
        ';dbname=' . $settings['database']['dbname'] . 
        ';charset=' . $settings['database']['charset'] . 
        ((!empty($settings['database']['port'])) ? (';port=' . $settings['database']['port']) : '');
       
        parent::__construct($dns, $settings['database']['username'], $settings['database']['password']);
    }
}
?>