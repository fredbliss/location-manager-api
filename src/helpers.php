<?php
use Aura\Sql\ExtendedPdo;

class Helpers {

    protected $container;

    public function __construct($c) {
        $this->container = $c;
    }

    public function generatePdfTemplate($billboard,$directionLabel) {
        $strTemplate = '<!DOCTYPE html>
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
                    table{width:100%;margin:30px 0px 30px 0px;}
                    .text-center{text-align:center;}
                    .inside{display:inline-block;}
                    .title{float:left;}
                    .logo{float:right;}
                    .map-container {
                        display: block;
                        background-image: url("https://maps.googleapis.com/maps/api/staticmap?center='.$billboard->latitude.','.$billboard->longitude.'&markers=color:blue%7C'.$billboard->latitude.','.$billboard->longitude.'&size=540x540&zoom=10&maptype=hybrid&sensor=false&key=AIzaSyCnCmpnxnMX-HLfiR4U2FvinL7dFmNVUTI");
                        background-repeat: no-repeat;
                        background-position: 50% 50%;
                        line-height: 0;
                    }                    
                    .map-container img
                    {
                        max-width: 100%;
                        opacity: 1;
                    }
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
                            <img src="../../small/'.$billboard->billboard_id.' '.$billboard->panel.'.jpg" />
                            <div>Viewable by '.$directionLabel.' traffic</div>
                        </div>
                        <div class="image map-container">
                            <img class="img-fluid" src="https://maps.googleapis.com/maps/api/staticmap?center='.$billboard->latitude.','.$billboard->longitude.'&markers=color:blue%7C'.$billboard->latitude.','.$billboard->longitude.'&size=540x540&zoom=10&maptype=hybrid&sensor=false&key=AIzaSyCnCmpnxnMX-HLfiR4U2FvinL7dFmNVUTI">
                        </div>
                    </div>
                </div>  
                <div class="statistics">
                    <div class="inside">
                    <table class="text-center">
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
                    </div>
                </div>                   
                </body>
                </html>';

        return $strTemplate;
    }

    public function generateTemplate($billboard,$directionLabel) {
        $strTemplate = '<!DOCTYPE html>
                <html>
                <head>
                <meta http-equiv="Content-Type" content="text/html;" charset=\'utf-8\'>
                <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.3/css/bootstrap.min.css" integrity="sha384-Zug+QiDoJOrZ5t4lssLdxGhVrurbmBWopoEl+M6BdEfwnCJZtKxi1KgxUyJq13dy" crossorigin="anonymous">
                <style>
                    @page{ size: A4; }
                    @media print {
                        .container { width: auto; }
                        .no-print { display:none; }
                    }
                    body{color: black;font-family: Helvetica; margin: 0; padding: 0; }
                    .header{margin-bottom:30px;}
                    h1 {margin:0;padding:0;line-height:3.2rem;font-size:3rem;}
                    .title h2{line-height:50px; color:#4a66ac; -webkit-print-color-adjust: exact; font-weight:bold;}
                    h3 {font-size:24px;}
                    .text-center{text-align:center;}
                    .logo{float:right;}
                    .spacer{height:3em;}
                    .capitalize{text-transform:capitalize;}
                    .imagery img, .imagery iframe {border:none;}
                    .map-container {
                        display: block;
                        background-image: url("//maps.googleapis.com/maps/api/staticmap?center='.$billboard->latitude.','.$billboard->longitude.'&markers=color:blue%7C'.$billboard->latitude.','.$billboard->longitude.'&size=540x540&zoom=10&maptype=hybrid&sensor=false&key=AIzaSyCnCmpnxnMX-HLfiR4U2FvinL7dFmNVUTI");
                        background-repeat: no-repeat;
                        background-position: 50% 50%;
                        line-height: 0;
                    }
                    .map-container img
                    {
                        max-width: 100%;
                        opacity: 1;
                    }
                    .icon {
                        height:24px;
                        width:24px;
                        margin-bottom:6px;
                    }
                    iframe{width:100%;height:100%;position:relative;}
                    table{width:100%;margin:30px 0px 30px 0px;}
                    td, th { border:1px solid #000000; padding:4px;}
                    table { border-collapse: collapse; border:1px solid #000000;}
                    th { background-color:#7f8fa9; -webkit-print-color-adjust: exact; color:#ffffff; font-size:0.6em;}
                    td { background-color:#d8dbe2; -webkit-print-color-adjust: exact; font-size:0.6em;}
                </style>
                <title>Billboard Spec Sheet</title>
                </head>
                <body>
                <div class="container">
                    <div class="row justify-content-md-center header">
                        <div class="col">
                            <div class="row">
                                <div class="col-8 title">
                                    <h2>Billboard '.$billboard->billboard_id.' <img src="../node_modules/open-iconic/svg/print.svg" alt="print" class="icon no-print" onclick="window.print()"></h2>  
                                </div>
                                <div class="col-4 text-right">
                                    <img class="text-center" src="../images/logo.png" height="50" /> 
                                </div>
                            </div>
                        </div>
                    </div> 
                    <div class="row justify-content-md-center main">
                        <div class="col">
                        <h3 class="text-center">'.$billboard->details.'</h3>
                        </div>                
                    </div>                
                ';
            #if($this->urlExists('http://vo.intelligentspark.com/assets/location-manager/assets/images/'.$billboard->billboard_id)) {
                $strTemplate .= '
                <div class="row justify-content-md-center imagery">
                    <div class="col">
                        <img src="../../small/'.$billboard->billboard_id.' '.$billboard->panel.'.jpg" class="img-fluid" />
                    </div>
                    <div class="col map-container">
                        <img class="img-fluid" src="//maps.googleapis.com/maps/api/staticmap?center='.$billboard->latitude.','.$billboard->longitude.'&markers=color:blue%7C'.$billboard->latitude.','.$billboard->longitude.'&size=540x540&zoom=10&maptype=hybrid&sensor=false&key=AIzaSyCnCmpnxnMX-HLfiR4U2FvinL7dFmNVUTI">
                    </div>
                </div>
                <div class="row justify-content-md-center">                
                        <div class="col text-center">Viewable by '.$directionLabel.' traffic</div>
                        <div class="col">&nbsp;</div>
                </div>';
            #}

            $strTemplate .= '<div class="row statistics">
                    <div class="col-xs col-md col-offset-2">
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
                    </div>
                    <div class="col-xs col-md col-offset-2">                        
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
                </div><!--end container -->
                <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
                <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
                <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.3/js/bootstrap.min.js" integrity="sha384-a5N7Y/aK3qNeh15eJKGWxsqtnX/wWdSZSKp+81YjTmS15nvnvxKHuzaWwXHDli+4" crossorigin="anonymous"></script>                 
                </body>
                </html>
        ';

        return $strTemplate;
    }

    protected function urlExists($url) {
        $headers=get_headers($url);
        return stripos($headers[0],"200 OK")?true:false;
    }

    public function getAll($table, $where = '')
    {
        $pdo = $this->container['db'];
        $stm = "SELECT * FROM " . $table . ' ' . $where;
        $sth = $pdo->prepare($stm);

        $sth->execute();

        return ($sth ? $sth->fetchAll(PDO::FETCH_ASSOC) : false);
    }

    public function getOne($table, $wherekey, $value, $fields = '*') {
        $pdo = $this->container['db'];
        $sth = $pdo->prepare("SELECT ".$fields." FROM ".$table." WHERE ".$wherekey." =:".$wherekey);
        $sth->bindParam("id", $value);
        $sth->execute();


        return ($sth ? $sth->fetchObject() : false);
    }

    public function generatePdf($strContent) {
        $pdf = $this->container['dompdf'];

        $pdf->loadHtml($strContent);

        $pdf->render();

        return $pdf;
    }

    public function getDirectionLabel($strDirection) {
        switch($strDirection) {
            case 'NB':
                $directionLabel = 'northbound';
                break;
            case 'SB':
                $directionLabel = 'southbound';
                break;
            case 'EB':
                $directionLabel = 'eastbound';
                break;
            case 'WB':
                $directionLabel = 'westbound';
                break;
        }

        return $directionLabel;
    }
}