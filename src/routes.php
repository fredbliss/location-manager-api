<?php


// Routes

$app->group('/locations', function() {

    $this->get('/',function($request, $response, $args) {
        return $this->response->withJson($this->helpers->getAll('billboards'));
    });

    $this->get('/{id}',function($request, $response, $args) {
        return $this->response->withJson($this->helpers->getOne('billboards', 'id', $args['id']));
    });
});

