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

	/**
	 * @var Doctrine\ORM\EntityManager
	 */
	protected $em;

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

    	$subtitles = $this->getEntityManager()->getRepository('Application\Entity\Subtitles');
    	$movies = $this->getEntityManager()->getRepository('Application\Entity\Movie');

    	$elasticaClient = new \Elastica\Client(array(
    			'host' => 'ycapyceq.api.qbox.io',
    			'port' => 80
    	));

    	// Load index
    	$elasticaIndex = $elasticaClient->getIndex('movies');

    	//Create a type
    	$elasticaType = $elasticaIndex->getType('subtitle');
/*
    	for($i=0;$i<10;$i++){
	    	$list = $subtitles->findBy(
	                   array(),        // $where
	                   array('id' => 'ASC'),    // $orderBy
	                   100,                        // $limit
	                   $i*100                        // $offset
	                 );

	    	$documents = array();
	    	foreach($list as $item){

	    		$movie = $movies->findOneBy(array('freebase_mid' => $item->mid));

	    		$origtitle = null;
	    		if($movie->title!=$movie->original_title){
	    			$origtitle = $movie->original_title;
	    		}
	    		if(!$origtitle){
	    			$origtitle = null;
	    		}
	    		if($movie->script_content){
	    			$item->content = $movie->script_content;
	    		}

	    		// cast
	    		$genres = array();
	    		foreach(unserialize($movie->genre) as $key => $genre){
	    			$genres[] = array(
	    				'genre_id' => $key,
	    				'genre_name' => $genre,
	    			);
	    		}
	    		$directed_by = array();
	    		foreach(unserialize($movie->directed_by) as $key => $cast){
	    			$directed_by[] = array(
	    				'director_id' => $key,
	    				'director_name' => $cast,
	    			);
	    		}
	    		$starring = array();
	    		foreach(unserialize($movie->starring) as $key => $cast){
	    			$starring[] = array(
	    				'actor_id' => $key,
	    				'actor_name' => $cast,
	    			);
	    		}
	    		$story_by = array();
	    		foreach(unserialize($movie->story_by) as $key => $cast){
	    			$story_by[] = array(
	    				'storyby_id' => $key,
	    				'storyby_name' => $cast,
	    			);
	    		}
	    		$written_by = array();
	    		foreach(unserialize($movie->written_by) as $key => $cast){
	    			$written_by[] = array(
	    				'writer_id' => $key,
	    				'writer_name' => $cast,
	    			);
	    		}
	    		$music_by = array();
	    		foreach(unserialize($movie->music_by) as $key => $cast){
	    			$music_by[] = array(
	    				'musician_id' => $key,
	    				'musician_name' => $cast,
	    			);
	    		}

	    		$entry = array(
	    				'title'      => $item->name,
	    				'original_title'      => $origtitle,
						'description' => $movie->description,
						'genres' => $genres,
						'directed_by' => $directed_by,
						'starring' => $starring,
						'story_by' => $story_by,
						'written_by' => $written_by,
						'music_by' => $music_by,
	    				'content'     => $item->content,
	    		);
	    		// First parameter is the id of document.
	    		$document = new \Elastica\Document($item->mid, $entry);
	    		$documents[] = $document;
	    		//var_dump($document);
				//exit();
	    	}
	    	$elasticaType->addDocuments($documents);
	    	$elasticaType->getIndex()->refresh();
    	}*/

    	$elasticaResultSet = null;
    	if(isset($_GET['query'])){

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
	    	$elasticaResultSet    = $elasticaIndex->search($elasticaQuery)->getResults();
    	}

    	//var_dump($elasticaResultSet);
    	//exit();

        return new ViewModel(array(
        	'results' => $elasticaResultSet
        ));
    }

    public function setEntityManager(EntityManager $em)
    {
    	$this->em = $em;
    }
    public function getEntityManager()
    {
    	if (null === $this->em) {
    		$this->em = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
    	}
    	return $this->em;
    }
}
