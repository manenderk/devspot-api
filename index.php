<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use MatthiasMullie\Minify;
require 'vendor/autoload.php';

$app = new \Slim\App;

//Minify CSS
$app->post('/minify/css', function (Request $request, Response $response) {
	
	$responseData=[
    'status'            => 'error',
    'minified_resource' => '',
    'message'           => '',
    'original_size'     => 0,
    'minified_size'     => 0
  ];

	try{
		  $data = $request->getParsedBody();
    	$style = $data['unminified_resource'];
    	$minifier = new Minify\CSS($style);
    	$minified = $minifier->minify();
    	$responseData['status'] = 'success';
    	$responseData['minified_resource'] = $minified;  
      $responseData['original_size']= mb_strlen($style, '8bit');
      $responseData['minified_size']= mb_strlen($responseData['minified_resource'], '8bit');	
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
		'status'            => 'error',
		'minified_resource' => '',
		'message'           => '',
    'original_size'     => 0,
    'minified_size'     => 0
	];

	try{
		  $data = $request->getParsedBody();
    	$script = $data['unminified_resource'];
    	$minifier = new Minify\JS($script);
    	$minified = $minifier->minify();
    	$responseData['status'] = 'success';
    	$responseData['minified_resource'] = $minified; 	
      $responseData['original_size']= mb_strlen($script, '8bit');
      $responseData['minified_size']= mb_strlen($responseData['minified_resource'], '8bit');
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