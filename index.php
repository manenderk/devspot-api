<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use MatthiasMullie\Minify;
require 'vendor/autoload.php';

$app = new \Slim\App;

//Minify CSS
$app->post('/minify/css', function (Request $request, Response $response) {
	
	$responseData=[
		'status'	=>	'error',
		'style'		=> 	'',
		'message'	=> 	''
	];

	try{
		$data = $request->getParsedBody();
    	$style = filter_var($data['style'], FILTER_SANITIZE_STRING);
    	$minifier = new Minify\CSS($style);
    	$minified = $minifier->minify();
    	$responseData['status'] = 'success';
    	$responseData['style'] = $minified;
    	unset($minifier);    	
	}
   	catch(Exception $e){
   		$responseData['status'] = 'error';
   		$responseData['message'] = $e->getMessage(); 
   	}

    
    $response->withHeader('Content-Type', 'application/json');
    $response->write(json_encode($responseData));
    return $response;
});

//Minify JS
$app->post('/minify/js', function (Request $request, Response $response) {
	
	$responseData=[
		'status'	=>	'error',
		'script'	=> 	'',
		'message'	=> 	''
	];

	try{
		$data = $request->getParsedBody();
    	$script = filter_var($data['script'], FILTER_SANITIZE_STRING);
    	$minifier = new Minify\JS($script);
    	$minified = $minifier->minify();
    	$responseData['status'] = 'success';
    	$responseData['script'] = $minified;
    	unset($minifier);    	
	}
   	catch(Exception $e){
   		$responseData['status'] = 'error';
   		$responseData['message'] = $e->getMessage(); 
   	}

    
    $response->withHeader('Content-Type', 'application/json');
    $response->write(json_encode($responseData));
    return $response;
});


$app->run();