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

            $billboard_data[$row['billboard_id']]['directions'][$arrDir[0]] = array('id'=>$row['id'],'facing'=>$this->helpers->getDirectionLabel($arrDir[0]));
        }

        foreach($billboard_data as $billboard) {

            $billboard_final = $billboard;
            unset($billboard_final['directions']);
            foreach($billboard['directions'] as $direction) {
                $billboard_final['directions'][] = $direction;
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

    $this->get('/{id}/pdf',function(Request $request, Response $response, array $args) {

        $billboard = $this->helpers->getOne('billboards', 'id', $args['id']);

        $direction = explode("-",$billboard->panel);

        $directionLabel = $this->helpers->getDirectionLabel($direction[0]);

        $datetime = new DateTime();
        $date = $datetime->format("m-d-Y");

        $strFilename = 'billboard-'.$args['id'].'-'.$date.'.pdf';
        $strFilePath = __DIR__ . '/'.$strFilename;
        $file = fopen($strFilePath,'w+'); //will create if doesn't exist

        $pdf = $this->helpers->generatePdf($this->helpers->generatePdfTemplate($billboard,$directionLabel));

        fwrite($file,$pdf->output());

        $stream = new \Slim\Http\Stream($file); // create a stream instance for the response body

        return $response->withHeader('Content-Type', 'application/pdf')
            ->withHeader('Access-Control-Allow-Origin', '*')
            ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
            ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS')
            ->withHeader('Content-Description', 'File Transfer')
            ->withHeader('Content-Disposition', 'attachment; filename="' .$strFilename . '"')
            #->withHeader('Content-Transfer-Encoding', 'binary')
            ->withHeader('Expires', '0')
            ->withHeader('Cache-Control', 'must-revalidate')
            ->withHeader('Pragma', 'public')
            ->withHeader('Content-Length', filesize($strFilePath))
            ->withBody($stream); // all stream contents will be sent to the response
    });

});

