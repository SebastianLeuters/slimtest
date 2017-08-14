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


        $nameKey = $this->csrf->getTokenNameKey();
        $valueKey = $this->csrf->getTokenValueKey();
        $name = $request->getAttribute($nameKey);
        $value = $request->getAttribute($valueKey);

        $manager = $this->getManager();
//        $stm = $manager->prepare('select *
        /** @var \mysqli_result  $data */
        $data = $manager->select('test');

        $manager->insert('test', ['name' => 'neuer test2', 'description' => 'neue Beschreibung']);


        // INSERT INTO users ( id , usr , pwd ) VALUES ( ? , ? , ? )
//        $insertStatement = $manager->insert(array('name', 'description'))
//            ->into('test')
//            ->values(array('Neuer name', 'Neue Beschreibung'));

//        $insertId = $insertStatement->execute(false);


//        $stm = $manager->query('select * from test');
//        $stm->execute();
//        $results = $stm->fetchAll();


        return $this->render($response, 'index.html.twig', [
            'nameKey' => $nameKey,
            'name' => $name,
            'valueKey' => $valueKey,
            'value' => $value,
            'results' => $data
        ]);
    }
}