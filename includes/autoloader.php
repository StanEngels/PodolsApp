<?php
spl_autoload_register(function($className){
        require(dirname(dirname(__FILE__)) . '/classes/' . $className . '.php');
});