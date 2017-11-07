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
        $billboards = array();

        foreach($arrBillboards as $row) {
            $arrRow['position'] = (object)["lat"=>(float)$row['latitude'], "lng"=>(float)$row['longitude']];
            $billboards[] = array_merge($row,$arrRow);
        }

        return $this->response->withJson($billboards);
    });

    $this->get('/{id}',function(Request $request, Response $response, array $args) {
        return $this->response->withJson($this->helpers->getOne('billboards', 'id', $args['id']));
    });

    $this->get('/{id}/pdf',function(Request $request, Response $response, array $args) {

        $billboard = $this->helpers->getOne('billboards', 'id', $args['id']);

        $strTrafficDirection['first'] = 'north-bound';
        $strTrafficDirection['second'] = 'south-bound';

        $datetime = new DateTime();
        $date = $datetime->format("m-d-Y");

        #$file = new SplFileObject(__DIR__ . '/billboard-'.$args['id'].'-'.$date.'.pdf','w');
        $strFilename = 'billboard-'.$billboard->billboard_id.'.pdf';

        $file = fopen(__DIR__ . '/'.$strFilename,'w+'); //will create if doesn't exist

        //if(filesize($file)==0) {    //if file size is 0, generate. Otherwise, just return it.
            $dompdf = $this->get('dompdf');
            #$dompdf->setBasePath('');
            $dompdf->loadHtml('
                <!DOCTYPE html>
                <html>
                <head>
                <meta http-equiv="Content-Type" content="text/html;" charset=\'utf-8\'>
                <style>
                    body{color: black;font-family: Helvetica; margin: 0; padding: 0; }
                    div{display:block;position:relative;}
                    h1 {margin:0;padding:0;line-height:3.2rem;font-size:3rem;}
                    h3 {font-size:24px;}
                    .header, .main, .footer, .images, .statistics{clear:both;}
                    .image {width:50%; float:left; text-align:center;display:inline-block;}
                    .image > img{width:300px;border:1px solid #000000;padding:0;}
                    .text-center{text-align:center;}
                    .inside{display:inline-block;}
                    .title{float:left;}
                    .logo{float:right;}
                    .spacer{height:3em;}
                    .capitalize{text-transform:capitalize;}
                    table{width:100%;margin:30px 0px 30px 0px;}
                    td, th { border:1px solid #000000; padding:4px;}
                    table { border-collapse: collapse; border:1px solid #000000;}
                    th { background-color:#7f8fa9; color:#ffffff; font-size:0.6em;}
                    td { background-color:#d8dbe2; font-size:0.6em;}
                </style>
                <title>Billboard Spec Sheet</title>
                </head>
                <body>
                <div class="header">
                    <div class="inside">
                        <div class="title">
                            <h2>Billboard '.$billboard->billboard_id.'</h2>  
                        </div>
                        <div class="logo">
                            <img class="text-center" src="images/logo.png" height="50" />   
                        </div>
                    </div>
                </div> 
                <div class="main">
                    <div class="inside">
                    <h3 class="text-center">Located '.$billboard->location.'</h3>
                    </div>                
                </div>
                <div class="images">
                    <div class="inside">
                        <div class="image">
                            <img src="images/'.$billboard->billboard_id.'/1.jpg" />
                            <div>Viewable by '.$strTrafficDirection['first'].' traffic</div>
                        </div>
                        <div class="image">
                            <img src="images/'.$billboard->billboard_id.'/2.jpg" />
                            <div>Viewable by '.$strTrafficDirection['second'].' traffic</div>
                        </div>
                    </div>
                </div>  
                <div class="statistics">
                    <div class="inside">
                    <table class="text-center capitalize">
                        <thead>  
                        <tr>
                            <th>City</th>
                            <th>State</th>
                            <th>County</th>
                            <th>Highway</th>
                            <th>Size</th>
                            <th>Faces</th>
                            <th>Illumination</th>
                            <th>Structure Type</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>'.$billboard->city.'</td>
                            <td>'.$billboard->state.'</td>
                            <td>'.$billboard->county.'</td>
                            <td>'.$billboard->highway.'</td>
                            <td>'.$billboard->size.'</td>
                            <td>'.$billboard->faces.'</td>
                            <td>'.$billboard->illumination.'</td>
                            <td>'.$billboard->structure_type.'</td>
                        </tr>
                        </tbody>
                    </table> 
                    <table class="text-center">
                        <thead>  
                        <tr>
                            <th>Latitude</th>
                            <th>Longitude</th>
                            <th>DEC Traffic Counts</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>'.$billboard->latitude.'</td>
                            <td>'.$billboard->longitude.'</td>
                            <td>'.$billboard->traffic_counts.'</td>
                        </tr>
                        </tbody>
                    </table>
                    </div>
                </div>                   
                </body>
                </html>
            ');
            $dompdf->render();
            fwrite($file,$dompdf->output());
        //}

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
            ->withHeader('Content-Length', filesize($file))
            ->withBody($stream); // all stream contents will be sent to the response
    });
});

