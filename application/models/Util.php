<?php

class Util {

    public static function dataMysql($data) {
        $dt = trim($data);

        if (strstr($dt, "/")) {

            $aux2 = explode("/", $dt);

            $datai2 = $aux2[2] . "-" . $aux2[1] . "-" . $aux2[0];

            return $datai2;
        }
    }

}
