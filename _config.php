<?php
DEFINE('SSRATING', basename(dirname(__FILE__)));

Object::add_extension('ContentController' , 'SSRatingExtension');
Object::add_extension('DataObject'        , 'SSRatingExtension');

// -- Rule for the Ajax Ranking Action System
Director::addRules(100, array('rating/$Action/$ID' => "SSRatingExtension_Controller"));