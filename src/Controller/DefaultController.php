<?php

namespace Controller;

class DefaultController extends BaseController {


    /**
     * @param $request
     * @param $response
     * @param $args
     * @return mixed
     */
    public function homeAction($request, $response, $args) {

        $manager = $this->getManager();
//        $stm = $manager->prepare('select * from test');
        $stm = $manager->query('select * from test');
        $stm->execute();
        $results = $stm->fetchAll();


        return $this->render($response, 'index.html.twig', [
            'results' => $results
        ]);
    }
}