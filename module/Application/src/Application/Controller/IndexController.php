<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class IndexController extends AbstractActionController
{
    public function indexAction()
    {
    	/*
    	$client = new \Elasticsearch\Client(array('hosts' => array('ycapyceq.api.qbox.io:80')));
    	
    	$params['index'] = 'subtitles';
    	$params['type']  = 'movie';
    	$params['body']['query']['match']['content'] = 'janet';
    	$params['body']['highlight']['fields']['content'] = array();
    	
    	$results = $client->search($params);
    	
    	var_dump($results);*/
    	
    	$elasticaResultSet = null;
    	if(isset($_GET['query'])){
	    	$elasticaClient = new \Elastica\Client(array(
	    			'host' => 'ycapyceq.api.qbox.io',
	    			'port' => 80
	    	));
	    	
	    	$elasticaIndex = $elasticaClient->getIndex('subtitles');
	    	
	    	// Define a Query. We want a string query.
	    	$elasticaQueryString  = new \Elastica\Query\QueryString();
	    	
	    	//'And' or 'Or' default : 'Or'
	    	$elasticaQueryString->setDefaultOperator('AND');
	    	$elasticaQueryString->setQuery($_GET['query']);
	    	
	    	// Create the actual search object with some data.
	    	$elasticaQuery        = new \Elastica\Query();
	    	$elasticaQuery->setHighlight(array(
	    			'pre_tags' => array('<em class="highlight">'),
	    			'post_tags' => array('</em>'),
	    			'fields' => array(
	    					'content' => array(
	    							'fragment_size' => 200,
	    							'number_of_fragments' => 100,
	    					),
	    			),
	    	));
	    	$elasticaQuery->setQuery($elasticaQueryString);
	    	
	    	//Search on the index.
	    	$elasticaResultSet    = $elasticaIndex->search($elasticaQuery);
    	}
    	
    	//var_dump($elasticaResultSet->getResults());
    	
        return new ViewModel(array(
        	'results' => $elasticaResultSet->getResults()
        ));
    }
}
