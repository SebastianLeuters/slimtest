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
//        $stm = $manager->prepare('select *
        $selectStatement = $manager->select()
            ->from('test');

        $stmt = $selectStatement->execute();
        $data = $stmt->fetchAll();


        // INSERT INTO users ( id , usr , pwd ) VALUES ( ? , ? , ? )
        $insertStatement = $manager->insert(array('name', 'description'))
            ->into('test')
            ->values(array('Neuer name', 'Neue Beschreibung'));

        $insertId = $insertStatement->execute(false);


//        $stm = $manager->query('select * from test');
//        $stm->execute();
//        $results = $stm->fetchAll();


        return $this->render($response, 'index.html.twig', [
            'results' => $data
        ]);
    }
}