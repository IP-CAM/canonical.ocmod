<?php

class Modelmodulecanonical extends Model
{
    public function addRule($request) {

        $data = $this->formCorrectData($request);

        if ($data['url'] != '') {
            $this->db->query("INSERT INTO `canonical_pages` (`canonical_url`,`url`)"
                . "VALUES ('" . $this->db->escape($data['canonical_url']) . "','" . $data['url'] . "')");
        } else {
            $this->db->query("INSERT INTO `canonical_pages` (`canonical_url`)"
                . "VALUES ('" . $data['canonical_url'] . "')");
        }
//        if ($data['url'] != '') {
//            $sql = "INSERT INTO `canonical_pages` (`canonical_url`,`url`)"
//                . "VALUES (:canonical_url', :url)";
//        } else {
//            $sql = "INSERT INTO `canonical_pages` (`canonical_url`)"
//                . "VALUES (:canonical_url)";
//        }
//        $result = $this->db->prepare($sql);

//        $result->bindParam(':canonical_url', $data['canonical_url'], PDO::PARAM_STR);
//        $result->bindParam(':url', $data['url'], PDO::PARAM_STR);

//        $result->execute();

        $id = $this->db->getLastId();
        $paramsArray = [];
//
        foreach ($data['params'] as $key => $value) {
//            $sql_params = "INSERT INTO `canonical_pages_params` (`id`,`param`,`value`) "
//                . "VALUES (:id,:key,:value)";
//            $result = $this->db->prepare($sql_params);
//            $result->bindParam(':id', $id, PDO::PARAM_INT);
//            $result->bindParam(':key', $key, PDO::PARAM_STR);
//            $result->bindParam(':value', $value, PDO::PARAM_STR);
//            $result->execute();
            $paramsArray[] = "(" . $id . ",'" . $key . "','" . $value . "')";
        }


        if (!empty($paramsArray)) {
            $paramsForQuery = implode(',', $paramsArray);

            $this->db->query("INSERT INTO `canonical_pages_params` (`id`,`param`,`value`) "
                . "VALUES " . $paramsForQuery);
        }

        return true;
    }

    public function deleteRule($id) {
        $this->db->query("DELETE FROM `canonical_pages` WHERE `id` =" . $id);
        //return $result;
    }

    public function getRules() {

        $rules = [];
        $rulesRows = $this->db->query("SELECT `cp`.`id` AS id, `canonical_url`, `url`, `param`, `value` "
                                    . "FROM `canonical_pages` AS `cp` "
                                    . "LEFT JOIN `canonical_pages_params` AS `cpp` "
                                    . "ON `cp`.`id` = `cpp`.`id` ");

        foreach ($rulesRows->rows as $row) {
            if (isset($rules[$row['id']])) {
                $rules[$row['id']]['params'][$row['param']] = $row['value'];
            } else {
                if ($row['param'] != '') {
                    $rules[$row['id']] = ['canonical_url' => $row['canonical_url'],'url' => $row['url'],
                        'params' => [$row['param'] => $row['value']]];
                } else {
                    $rules[$row['id']] = ['canonical_url' => $row['canonical_url'],'url' => $row['url']];
                }
            }
        }

        return $rules;
    }

    /**
     *  example of data before:
     *  $data = ['url' => 'url', 'options' =>'index,follow',
     *          'params' => [['key' => 'id', 'value' => 10], ['key' =>'model', 'value' => 5]];
     *
     *  example of data after:
     *  $data = ['url' => 'url', 'index' => 0, 'follow' => 0,
     *          'params' => ['id'=> 10, 'model' => 5] ];
     *
     * @param array $data
     * @return array
     */
    private function formCorrectData($data) {
        $params = [];
        if (isset($data['params'])) {
            foreach ($data['params'] as $param) {
                $params[$param['key']] = $param['value'];
            }
        }
        $data['params'] = $params;

        return $data;
    }
}