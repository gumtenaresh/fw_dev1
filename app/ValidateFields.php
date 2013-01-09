<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ValidateFields
 *
 * @author satyam
 */
class ValidateFields {
    //put your code here

    /**
     * @author Satyam
     * @param <type> $inputs
     * @param <type> $labels
     * @param <type> $optional
     * @return string
     */
    public function validate($inputs, $labels, $post = null) {
        //Check for input filter errors here and generate error messages.

        $errorArr = array();

        foreach ($inputs as $key => $value) {


            if (trim($value) == '' && $labels[$key]['required'] === true && empty($post[$key])) {
                $errorArr[] = $labels[$key]['label'] . ' is required ' . "";
            } else if ($value == '') {

                $errorArr[] = $labels[$key]['label'] . ' is invalid ' . "";
            }
        }

        return $errorArr;
    }

}

?>
