<?php

class Modelmodulecanonical extends Model
{
    public function getCanonicalUrl($uri,$params) {

        $queryWhere = $this->formWhereClause($uri,$params);

        $rule = $this->db->query("SELECT `canonical_url` "
            . "FROM `canonical_pages` AS `cp` "
            . "LEFT JOIN `canonical_pages_params` AS `cpp` "
            . "ON `cp`.`id` = `cpp`.`id` "
            . $queryWhere
            . " ORDER BY `cp`.`id` DESC LIMIT 1");

        if (empty($rule->row)) {
            return null;
        }
        return $rule->row['canonical_url'];
    }

    private function formWhereClause($uri,$params) {

        if (isset($params['_route_'])) {
            if ($uri == $params['_route_']) {
                unset($params['_route_']);
            }
        }

        $queryWhereArray = [];

        if (isset($uri)) {
            $queryWhereArray[] = "(`url` = '" . $uri . "')";
        }
        if (isset($params)) {
            if (empty($params)) {
                $queryWhereArray[] = "(ISNULL(`param`))";
            } else {
                $paramsArray = [];
                foreach ($params as $key => $value) {
                    $paramsArray[] = "(`param` = '" . $key . "' AND `value` = '" . $value . "')";
                }
                if (!empty($paramsArray)) {
                    $queryWhereArray[] = "(" . implode(' OR ', $paramsArray) . ")";
                }
            }
        }

        $queryWhereString = '';

        if (!empty($queryWhereArray)) {
            $queryWhereString = ' WHERE ' . implode(" AND ", $queryWhereArray);
        }

        return $queryWhereString;
    }
}