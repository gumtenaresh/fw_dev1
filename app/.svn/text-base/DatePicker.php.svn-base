<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of DatePicker
 *
 * @author naresh
 */
class DatePicker {

    //put your code here
    var $days = 31;
    var $months = array();
    var $years = array();

    /**
     *
     */
    public function getDays($year = null, $month = null) {
        if (!empty($year) && !empty($month)) {
            $this->days = cal_days_in_month(CAL_GREGORIAN, $month, $year);
        }
        $dayArr = array();
        for ($i = 1; $i <= $this->days; $i++) {
            if ($i < 10)
                $s = '0' . $i;
            else
                $s = $i;
            $dayArr[$s] = $s;
        }
        return $dayArr;
    }

    /**
     *
     */
    public function getMonths() {

        return $this->daysArray = array('01' => "Jan", '02' => "Feb", '03' => "Mar",
            '04' => "Apr", '05' => "May", '06' => "June", '07' => "Jul",
            '08' => "Aug", '09' => "Sep",
            10 => "Oct", 11 => "Nov", 12 => "Dec");
    }

    /**
     * making array from last 18th year to  18th - 90 years
     */
    public function getYears() {

        $currentYear = date('Y');
        $endYear = ($currentYear + 1);
        $startYear = $endYear - 87;


        for ($startYear; $startYear < $endYear; $startYear++) {
            $this->years[$startYear] = $startYear;
        }

        arsort($this->years);

        return $this->years;
    }

    public function getTimeStamp($original) {
       /*  $anstime = time() - strtotime($anstime);
        if ($anstime > 3600) {
            $atime = floor(($anstime) / 60 / 60);
            //error_log('hours'.$atime);
            if ($atime > 1) {
                $msg = $atime . ' hours ago';
            } else {
                $msg = $atime . ' hour ago';
            }
            if ($atime > 24) {
                $atime1 = floor($atime / 24);
                //error_log('days'.$atime);
                if ($atime1 > 1) {
                    $atime2 = $atime - ($atime1 * 24);
                    if ($atime2 > 1) {
                        $smsg = 'hours';
                    } else {
                        $smsg = 'hour';
                    }
                    $msg = $atime1 . ' days ' . $atime2 . ' ' . $smsg . ' ago';
                } else {
                    $atime2 = $atime - 24;
                    if ($atime2 > 1) {
                        $smsg = 'hours';
                    } else {
                        $smsg = 'hour';
                    }
                    $msg = $atime1 . ' day ' . $atime2 . ' ' . $smsg . ' ago';
                }
            }
        } else {
            if ($anstime > 60) {
                $minu = floor($anstime / 60);
                $sec = $anstime - ($minu * 60);
                if ($minu > 1) {
                    if ($sec == 1) {
                        $msg = $minu . ' mins ' . $sec . ' sec ago';
                    } else {
                        $msg = $minu . ' mins ' . $sec . ' sec\'s ago';
                    }
                } else {
                    if ($sec == 1) {
                        $msg = $minu . ' min ' . $sec . ' sec ago';
                    } else {
                        $msg = $minu . ' min ' . $sec . ' sec\'s ago';
                    }
                }
            } else {
                if ($anstime == 1) {
                    $msg = $anstime . ' sec ago';
                } else {
                    $msg = $anstime . ' sec\'s ago';
                }
            }
        }

        return $msg;  */
 
         $original = strtotime($original);
          $chunks = array(
          array(60 * 60 * 24 * 365, 'year'),
          array(60 * 60 * 24 * 30, 'month'),
          array(60 * 60 * 24 * 7, 'week'),
          array(60 * 60 * 24, 'day'),
          array(60 * 60, 'hour'),
          array(60, 'min'),
          array(1, 'sec'),
          );
          $today = time();
          $since = $today - $original;

          // $j saves performing the count function each time around the loop
          for ($i = 0, $j = count($chunks); $i < $j; $i++) {

          $seconds = $chunks[$i][0];
          $name = $chunks[$i][1];

          // finding the biggest chunk (if the chunk fits, break)
          if (($count = floor($since / $seconds)) != 0) {
          break;
          }
          }
          //echo $count.'<br/>';
          if($name=='month'){
              if($count>=2){
               $print = 'A long time';
              }
              else{
              $print = ($count == 1) ? '1 ' . $name : "$count {$name}s";
              }
          }else{
            $print = ($count == 1) ? '1 ' . $name : "$count {$name}s";
          }

          if ($i + 1 < $j) {
          // now getting the second item
          $seconds2 = $chunks[$i + 1][0];
          $name2 = $chunks[$i + 1][1];

//          // add second item if its greater than 0
//          if (($count2 = floor(($since - ($seconds * $count)) / $seconds2)) != 0) {
//          $print .= ($count2 == 1) ? ', 1 ' . $name2 : " $count2 {$name2}s";
//          }
          }
          
          if($name == 'week' || $name == 'month' || $name == 'year')
              return  date('m/d/y', $original);
          else
              return $print . ' ago'; 
    }
    

}

