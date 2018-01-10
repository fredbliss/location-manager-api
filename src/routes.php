<?php

use Slim\Http\Request;
use Slim\Http\Response;

// Routes
$app->options('/{locations:.+}', function ($request, $response, $args) {
    return $response;
});


$app->group('/locations', function() {

    $this->get('/',function(Request $request, Response $response, array $args) {

        $arrBillboards = $this->helpers->getAll('billboards');
        $billboard_data = array();
        $billboards = array();

        foreach($arrBillboards as $row) {
            $arrRow['position'] = (object)["lat"=>(float)$row['latitude'], "lng"=>(float)$row['longitude']];
            $arrDir = explode('-',$row['panel']);

            //set initial row data
            if(!is_array($billboard_data[$row['billboard_id']]))
                $billboard_data[$row['billboard_id']] = array_merge($row,$arrRow);

            $billboard_data[$row['billboard_id']]['directions'][$arrDir[0]] = array('id'=>$row['id'],'facing'=>$arrDir[0]);
        }

        foreach($billboard_data as $billboard) {

            $billboard_final = $billboard;
            unset($billboard_final['directions']);
            foreach($billboard['directions'] as $direction) {
                $billboard_final['directions'][] = $this->helpers->getDirectionLabel($direction);
            }

            $billboards[] = $billboard_final;
        }

        return $this->response->withJson($billboards);
    });

    /*$this->get('/{id}',function(Request $request, Response $response, array $args) {
        return $this->response->withJson($this->helpers->getOne('billboards', 'id', $args['id']));
    });*/

    /*$this->get('/{id}/map',function(Request $request, Response $response, array $args) {
        $newResponse = $response->withHeader('X-Frame-Options','ALLOW-FROM https://maps.googleapis.com/');
        $body = $newResponse->getBody();

        $body->write('<iframe style="width:100%;height:100%" src="//www.google.com/maps/embed/v1/place?q=34.686779,-84.470558&maptype=satellite&zoom=14&key=AIzaSyCnCmpnxnMX-HLfiR4U2FvinL7dFmNVUTI"></iframe>');

    });*/

    $this->get('/{id}',function(Request $request, Response $response, array $args) {

        $billboard = $this->helpers->getOne('billboards', 'id', $args['id']);

        //TODO: set direction by database
        /* $arrTrafficDirection['first'] = 'north-bound';
         $arrTrafficDirection['second'] = 'south-bound';
 */
        $direction = explode("-",$billboard->panel);

        $directionLabel = $this->helpers->getDirectionLabel($direction[0]);

        $datetime = new DateTime();
        $date = $datetime->format("m-d-Y");

        return $response->getBody()->write($this->helpers->generateTemplate($billboard,$directionLabel));

    });

});

