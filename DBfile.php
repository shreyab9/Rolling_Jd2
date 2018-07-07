<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class RollingBD extends mysqli {

    // single instance of self shared among all instances
    private static $instance = null;
    // db connection config vars
    private $user = "root";
    private $pass = "";
    private $dbName = "moira";
    private $dbHost = "localhost";

    public static function getInstance() {
        if (!self::$instance instanceof self) {
            self::$instance = new self;
        }
        return self::$instance;
    }

    // The clone and wakeup methods prevents external instantiation of copies of the Singleton class,
    // thus eliminating the possibility of duplicate objects.
    public function __clone() {
        trigger_error("Clone is not allowed.", E_USER_ERROR);
    }

    public function __wakeup() {
        trigger_error("Deserializing is not allowed.", E_USER_ERROR);
    }

    // private constructor
    public function __construct() {
        parent::__construct($this->dbHost, $this->user, $this->pass, $this->dbName);
        if (mysqli_connect_error()) {
            exit('Connect Error (' . mysqli_connect_errno() . ') '
                    . mysqli_connect_error());
        }
        parent::set_charset('utf-8');
    }

    public function get_size_id($sizeid) {
        $sizeid = $this->real_escape_string($sizeid);

        $size1 = $this->query("SELECT size_name FROM jd2_rolling_size WHERE size_id = " . $sizeid);
        //echo $size[0];

        if ($size1->num_rows > 0) {
            $row = $size1->fetch_row();
            // print_r($row );die;
            return $row[0];
        } else{
            return 0;
        //return $this->query("SELECT id FROM size WHERE sizename = " . $m1size);
    }
    }
     
    

 public function get_department_id($department) {
        $department = $this->real_escape_string($department);

           
        $size = $this->query("SELECT dept_name FROM jd2_rolling_department WHERE dept_id = " . $department);
        //echo $size[0];

        if ($size->num_rows > 0) {
            $row = $size->fetch_row();
            // print_r($row );die;
            return $row[0];
        } else{
            return 0;
        //return $this->query("SELECT id FROM size WHERE sizename = " . $m1size);
    }
 }
    public function get_person_id($responsible_person) {
        $responsible_person = $this->real_escape_string($responsible_person);

        $size = $this->query("SELECT resp_per_name FROM jd2_rolling_responsible_person WHERE resp_per_id = " . $responsible_person);
        //echo $size[0];

        if ($size->num_rows > 0) {
            $row = $size->fetch_row();
            // print_r($row );die;
            return $row[0];
        } else{
            return 0;
        //return $this->query("SELECT id FROM size WHERE sizename = " . $m1size);
    }
    }
     public function get_location_id($location_code) {
        $location_code = $this->real_escape_string($location_code);

        $size = $this->query("SELECT location_code FROM jd2_rolling_location WHERE location_id = " . $location_code);
        //echo $size[0];

        if ($size->num_rows > 0) {
            $row = $size->fetch_row();
            // print_r($row );die;
            return $row[0];
        } else{
            return 0;
        
    }
     }
    
    public function get_reason_id($reasonid) {
        $reasonid = $this->real_escape_string($reasonid);

        $size = $this->query("SELECT `reason_code` FROM `jd2_rolling_reason` WHERE `reason_id` = " . $reasonid);
        //echo $size[0];

        if ($size->num_rows > 0) {
            $row = $size->fetch_row();
            // print_r($row );die;
            return $row[0];
        } else{
            return 0;
        
    }
    }
    
    public function get_total_billets_bypass_production($bddate,$heat_number,$deptid) {
        $bd_date = $this->real_escape_string($bddate);
         $show_date = DateTime::createFromFormat('d/m/Y', $bd_date)->format('Y-m-d');
        $heat_no = $this->real_escape_string($heat_number);
        $dept_id=$this->real_escape_string($deptid);

        $size = $this->query("SELECT sum(total_billets_bypass_prod) FROM `jd2_rolling_breakdown`
                where `bd_date` = '" . $show_date . "'and `heat_number` = '" . $heat_no . "' AND dept_id ='".$dept_id."'");
    
        if ($size->num_rows > 0) {
            $row = $size->fetch_row();
            // print_r($row );die;
            return $row[0];
        } else{
            return 0;
       
    }
    }

    
    
    
    
    
    public function get_roughing_mr_prod_mill1($kpidate,$heatnumber,$m1s) {
        $kpi_date = $this->real_escape_string($kpidate);
         $heatnumber = $this->real_escape_string($heatnumber);
        $show_date = DateTime::createFromFormat('d/m/Y', $kpi_date)->format('Y-m-d');
        echo $show_date;
      
       $m1s= $this->real_escape_string($m1s);
        echo "<br>";
        $s1 = $this->query("SELECT sum(missroll_prod) FROM  jd2_rolling_breakdown  WHERE 
                bd_date ='".$show_date."'and mill_1_size='".$m1s."' and location_id in(7,8,12,16)and heat_number='".$heatnumber."' ");
        if ( $s1->num_rows > 0) {
            $r1 = $s1->fetch_row();
            return $r1[0];
        } else {
            return 0;
        }
    }
    
      public function get_roughing_mr_prod_mill2($kpidate,$heatnumber,$m2s) {
        $kpi_date = $this->real_escape_string($kpidate);
        $heatnumber = $this->real_escape_string($heatnumber);
        $show_date = DateTime::createFromFormat('d/m/Y', $kpi_date)->format('Y-m-d');
        echo $show_date;
      
       $m2s= $this->real_escape_string($m2s);
        echo "<br>";
        $s1 = $this->query("SELECT sum(missroll_prod) FROM  jd2_rolling_breakdown  WHERE 
               bd_date ='".$show_date."' and  mill_2_size='".$m2s."' and  location_id in(9,10,13,17) and heat_number='".$heatnumber."' ");
        if ( $s1->num_rows > 0) {
            $r1 = $s1->fetch_row();
            return $r1[0];
        } else {
            return 0;
        }
    }
    
    public function get_cutting_prod_mill1($kpidate,$heatnumber,$m1s) {
        $kpi_date = $this->real_escape_string($kpidate);
        $show_date = DateTime::createFromFormat('d/m/Y', $kpi_date)->format('Y-m-d');
          $heatnumber = $this->real_escape_string($heatnumber);
     
       $m1s= $this->real_escape_string($m1s);
        echo "<br>";
        $s1 = $this->query("SELECT sum(total_cutting_wt) FROM jd2_rolling_breakdown   WHERE 
          bd_date ='".$show_date."' and mill_1_size= '".$m1s."' and heat_number='".$heatnumber."' and location_id in(7,8,12,16) ");
        if ( $s1->num_rows > 0) {
            $r1 = $s1->fetch_row();
            return $r1[0];
        } else {
            return 0;
        }
    }
    public function get_cutting_prod_mill2($kpidate,$heatnumber,$m2s) {
        $kpi_date = $this->real_escape_string($kpidate);
        $show_date = DateTime::createFromFormat('d/m/Y', $kpi_date)->format('Y-m-d');
          $heatnumber = $this->real_escape_string($heatnumber);
        //echo $show_date;
       $m2s= $this->real_escape_string($m2s);
        echo "<br>";
        $s1 = $this->query("SELECT  sum(total_cutting_wt) FROM jd2_rolling_breakdown where
                 bd_date ='".$show_date."' and mill_2_size='".$m2s."' and heat_number='".$heatnumber."' and  location_id in(9,10,13,17) ");
        if ( $s1->num_rows > 0) {
            $r1 = $s1->fetch_row();
            return $r1[0];
        } else {
            return 0;
        }
    }
    
    
    public function get_heat_count($kpidate) {
        $kpi_date = $this->real_escape_string($kpidate);
        $show_date = DateTime::createFromFormat('d/m/Y', $kpi_date)->format('Y-m-d');
      
        echo "<br>";
        $s1 = $this->query("SELECT count(`heat_number`) FROM jd2_rolling_per_heat_prod WHERE per_heat_date = '" . $show_date . "'");
        if ($s1->num_rows > 0) {

            $r1 = $s1->fetch_row();
            return $r1[0];
        } else {
            return 0;
        }
    }
     public function get_rolling_prod($kpidate) {
        $kpi_date = $this->real_escape_string($kpidate);
        $show_date = DateTime::createFromFormat('d/m/Y', $kpi_date)->format('Y-m-d');
       // echo $kpi_date;
        //echo $show_date;
        echo "<br>";
        $s1 = $this->query("SELECT sum(rollingprod) FROM jd2_rolling_per_heat_prod WHERE per_heat_date  = '" . $show_date . "'");
        if ( $s1->num_rows > 0) {
           
            $r1 = $s1->fetch_row();
            
            return $r1[0];
           
        } else{
            return 0;
        }
    }
    
       public function get_bd_down_time($kpidate,$heatno) {
        $kpi_date = $this->real_escape_string($kpidate);
        $show_date = DateTime::createFromFormat('d/m/Y', $kpi_date)->format('Y-m-d');
        $heat_no = $this->real_escape_string($heatno);
        $s1 = $this->query("select SEC_TO_TIME(SUM(TIME_TO_SEC(`bd_total_time`))) from jd2_rolling_breakdown WHERE bd_date= '" . $show_date . "' and `heat_number`='" . $heat_no . "'");
        if ( $s1->num_rows > 0) {
            $r1 = $s1->fetch_row();
       
            return  $r1[0];
           
        } else {
            return 0;
        }   
    
}

    public function get_ccm_prod($kpidate) {
        $kpi_date = $this->real_escape_string($kpidate);
        $show_date = DateTime::createFromFormat('d/m/Y', $kpi_date)->format('Y-m-d');
       // echo $kpi_date;
        //echo $show_date;
        echo "<br>";
        $s1 = $this->query("SELECT sum(ccmprod) FROM jd2_rolling_per_heat_prod WHERE per_heat_date = '" . $show_date . "'");
        if ( $s1->num_rows > 0) {
            $r1 = $s1->fetch_row();   
            return $r1[0];
           
        } else{
            return 0;
        }
            }

            
           public function get_heat_running_time($kpidate) {
        $kpi_date = $this->real_escape_string($kpidate);
        $show_date = DateTime::createFromFormat('d/m/Y', $kpi_date)->format('Y-m-d');
    
        echo "<br>";
        $s1 = $this->query("select SEC_TO_TIME(SUM(TIME_TO_SEC(`total_heat_time`))) FROM jd2_rolling_per_heat_prod WHERE per_heat_date  = '" . $show_date . "'");
        if ( $s1->num_rows > 0) {
            $r1 =$s1->fetch_row();   
            return $r1[0];
           
        } else{
            return 0;
        }
            
           } 
            
         public function get_billets_bypass($kpidate, $department) {
        $kpi_date = $this->real_escape_string($kpidate);
        $show_date = DateTime::createFromFormat('d/m/Y', $kpi_date)->format('Y-m-d');
        $dep_name = $this->real_escape_string($department);
       
        echo "<br>";
        $s1 = $this->query("select sum(total_billets_bypass)from jd2_rolling_breakdown WHERE bd_date= '" . $show_date . "' and dept_id='" . $dep_name . "'");
        if ( $s1->num_rows > 0) {
            $r1 = $s1->fetch_row();
            return $r1[0];
        } else {
            return 0;
        }
    }
    
      public function get_3rdstand_bypass_3mtr($kpidate) {
        $kpi_date = $this->real_escape_string($kpidate);
        $show_date = DateTime::createFromFormat('d/m/Y', $kpi_date)->format('Y-m-d');
   
        echo "<br>";
        $s1 = $this->query("select sum(3stand_3mtr_billetsbypass)FROM jd2_rolling_per_heat_prod WHERE per_heat_date = '" . $show_date . "'");
        if ( $s1->num_rows > 0) {
            $r1 = $s1->fetch_row();
         
            return $r1[0];
            
        } else {
            return 0;
        }
    } 
       
    public function get_3rdstand_bypass_6mtr($kpidate) {
        $kpi_date = $this->real_escape_string($kpidate);
        $show_date = DateTime::createFromFormat('d/m/Y', $kpi_date)->format('Y-m-d');
   
        echo "<br>";
        $s1 = $this->query("select sum(3stand_6mtr_billetsbypass) FROM jd2_rolling_per_heat_prod WHERE per_heat_date = '" . $show_date . "'");
        if ( $s1->num_rows > 0) {
            $r1 = $s1->fetch_row();
            echo $r1[0];
            return $r1[0];
            
        } else {
            return 0;
        }
    } 
      public function get_billets_bypass_3mtr_ccm($kpidate) {
        $kpi_date = $this->real_escape_string($kpidate);
        $show_date = DateTime::createFromFormat('d/m/Y', $kpi_date)->format('Y-m-d');
   
        echo "<br>";
        $s1 = $this->query("select sum(ccm_3mtr_billetsbypass) FROM jd2_rolling_per_heat_prod WHERE per_heat_date = '" . $show_date . "'");
        if ( $s1->num_rows > 0) {
            $r1 = $s1->fetch_row();
            echo $r1[0];
            return $r1[0];
            
        } else {
            return 0;
        }
    } 
     public function get_billets_bypass_6mtr_ccm($kpidate) {
        $kpi_date = $this->real_escape_string($kpidate);
        $show_date = DateTime::createFromFormat('d/m/Y', $kpi_date)->format('Y-m-d');
   
        echo "<br>";
        $s1 = $this->query("select sum(ccm_6mtr_billetsbypass)FROM jd2_rolling_per_heat_prod WHERE per_heat_date = '" . $show_date . "'");
        if ( $s1->num_rows > 0) {
            $r1 = $s1->fetch_row();
            echo $r1[0];
            return $r1[0];
            
        } else {
            return 0;
        }
    } 
    
     public function billets_bypass_prod_3rdstand ($kpidate) {
        $kpi_date = $this->real_escape_string($kpidate);
        $show_date = DateTime::createFromFormat('d/m/Y', $kpi_date)->format('Y-m-d');
   
        echo "<br>";
        $s1 = $this->query("select sum(billets_bypass_prod_3stand) FROM jd2_rolling_per_heat_prod WHERE per_heat_date = '" . $show_date . "'");
        if ( $s1->num_rows > 0) {
            $r1 = $s1->fetch_row();
            echo $r1[0];
            return $r1[0];
            
        } else {
            return 0;
        }
    } 
    
    
    
    
    
    public function get_bl_8mm($kpidate) {
        $kpi_date = $this->real_escape_string($kpidate);
        $show_date = DateTime::createFromFormat('d/m/Y', $kpi_date)->format('Y-m-d');
   
        echo "<br>";
        $s1 = $this->query("select sum(8mm_prod) FROM jd2_rolling_per_heat_prod WHERE per_heat_date= '" . $show_date . "'");
        if ( $s1->num_rows > 0) {
            $r1 = $s1->fetch_row();
            echo $r1[0];
            return $r1[0];
            
        } else {
            return 0;
        }
    } 
    public function get_bl_10mm($kpidate) {
        $kpi_date = $this->real_escape_string($kpidate);
        $show_date = DateTime::createFromFormat('d/m/Y', $kpi_date)->format('Y-m-d');
   
        echo "<br>";
        $s1 = $this->query("select sum(10mm_prod) FROM jd2_rolling_per_heat_prod WHERE per_heat_date= '" . $show_date . "'");
        if ( $s1->num_rows > 0) {
            $r1 = $s1->fetch_row();
            echo $r1[0];
            return $r1[0];
            
        } else {
            return 0;
        }
    } 
       public function get_bl_12mm($kpidate) {
        $kpi_date = $this->real_escape_string($kpidate);
        $show_date = DateTime::createFromFormat('d/m/Y', $kpi_date)->format('Y-m-d');
   
        echo "<br>";
        $s1 = $this->query("select sum(12mm_prod) FROM jd2_rolling_per_heat_prod WHERE per_heat_date= '" . $show_date . "'");
        if ( $s1->num_rows > 0) {
            $r1 = $s1->fetch_row();
            echo $r1[0];
            return $r1[0];
            
        } else {
            return 0;
        }
    } 
    public function get_bl_16mm($kpidate) {
        $kpi_date = $this->real_escape_string($kpidate);
        $show_date = DateTime::createFromFormat('d/m/Y', $kpi_date)->format('Y-m-d');
   
        echo "<br>";
        $s1 = $this->query("select sum(16mm_prod) FROM jd2_rolling_per_heat_prod WHERE per_heat_date= '" . $show_date . "'");
        if ( $s1->num_rows > 0) {
            $r1 = $s1->fetch_row();
            echo $r1[0];
            return $r1[0];
            
        } else {
            return 0;
        }
    }    
    
    public function get_bl_20mm($kpidate) {
        $kpi_date = $this->real_escape_string($kpidate);
        $show_date = DateTime::createFromFormat('d/m/Y', $kpi_date)->format('Y-m-d');
   
        echo "<br>";
        $s1 = $this->query("select sum(20mm_prod) FROM jd2_rolling_per_heat_prod WHERE per_heat_date= '" . $show_date . "'");
        if ( $s1->num_rows > 0) {
            $r1 = $s1->fetch_row();
            echo $r1[0];
            return $r1[0];
            
        } else {
            return 0;
        }
    } 
    public function get_bl_25mm($kpidate) {
        $kpi_date = $this->real_escape_string($kpidate);
        $show_date = DateTime::createFromFormat('d/m/Y', $kpi_date)->format('Y-m-d');
   
        echo "<br>";
        $s1 = $this->query("select sum(25mm_prod) FROM jd2_rolling_per_heat_prod WHERE per_heat_date= '" . $show_date . "'");
        if ( $s1->num_rows > 0) {
            $r1 = $s1->fetch_row();
            echo $r1[0];
            return $r1[0];
            
        } else {
            return 0;
        }
    } 
    public function get_bl_28mm($kpidate) {
        $kpi_date = $this->real_escape_string($kpidate);
        $show_date = DateTime::createFromFormat('d/m/Y', $kpi_date)->format('Y-m-d');
   
        echo "<br>";
        $s1 = $this->query("select sum(28mm_prod) FROM jd2_rolling_per_heat_prod WHERE per_heat_date= '" . $show_date . "'");
        if ( $s1->num_rows > 0) {
            $r1 = $s1->fetch_row();
            echo $r1[0];
            return $r1[0];
            
        } else {
            return 0;
        }
    } 
    public function get_bl_32mm($kpidate) {
        $kpi_date = $this->real_escape_string($kpidate);
        $show_date = DateTime::createFromFormat('d/m/Y', $kpi_date)->format('Y-m-d');
   
        echo "<br>";
        $s1 = $this->query("select sum(32mm_prod) FROM jd2_rolling_per_heat_prod WHERE per_heat_date= '" . $show_date . "'");
        if ( $s1->num_rows > 0) {
            $r1 = $s1->fetch_row();
            echo $r1[0];
            return $r1[0];
            
        } else {
            return 0;
        }
    } 
    
     public function get_mr_prod($kpidate) {
        $kpi_date = $this->real_escape_string($kpidate);
        $show_date = DateTime::createFromFormat('d/m/Y', $kpi_date)->format('Y-m-d');
   
        echo "<br>";
        $s1 = $this->query("select sum(missroll_prod) from jd2_rolling_breakdown WHERE bd_date= '" . $show_date . "'");
        if ( $s1->num_rows > 0) {
            $r1 = $s1->fetch_row();
            echo $r1[0];
            return $r1[0];
            
        } else {
            return 0;
        }
    } 
    
    
    public function get_mr_prod_mill($kpidate) {
        $kpi_date = $this->real_escape_string($kpidate);
        $show_date = DateTime::createFromFormat('d/m/Y', $kpi_date)->format('Y-m-d');
   
        echo "<br>";
        $s1 = $this->query("select sum(missroll_prod) from jd2_rolling_breakdown WHERE bd_date= '" . $show_date . "' and dept_id in(3,4,5)");
        if ( $s1->num_rows > 0) {
            $r1 = $s1->fetch_row();
            echo $r1[0];
            return $r1[0];
            
        } else {
            return 0;
        }
    } 
   public function get_total_mr_ina_day($kpidate) {
        $kpi_date = $this->real_escape_string($kpidate);
        $show_date = DateTime::createFromFormat('d/m/Y', $kpi_date)->format('Y-m-d');
   
        echo "<br>";
        $s1 = $this->query("select sum(total_missroll)  from jd2_rolling_breakdown WHERE bd_date= '" . $show_date . "'");
        if ( $s1->num_rows > 0) {
            $r1 = $s1->fetch_row();
            echo $r1[0];
            return $r1[0];
            
        } else {
            return 0;
        }   
    
}


        public function get_total_mr($kpidate,$department) {
        $kpi_date = $this->real_escape_string($kpidate);
        $show_date = DateTime::createFromFormat('d/m/Y', $kpi_date)->format('Y-m-d');
         $dep_name = $this->real_escape_string($department);
       
        echo "<br>";
        $s1 = $this->query("select sum(total_missroll)from jd2_rolling_breakdown WHERE bd_date= '" . $show_date . "' and dept_id='" . $dep_name . "'");
      
        if ( $s1->num_rows > 0) {
            $r1 = $s1->fetch_row();
            echo $r1[0];
            return $r1[0];
            
        } else {
            return 0;
        }   
    
}



  public function get_depen_mr($kpidate) {
        $kpi_date = $this->real_escape_string($kpidate);
        $show_date = DateTime::createFromFormat('d/m/Y', $kpi_date)->format('Y-m-d');
   
        echo "<br>";
        $s1 = $this->query("select sum(dep_missroll) from jd2_rolling_breakdown WHERE bd_date= '" . $show_date . "'");
        if ( $s1->num_rows > 0) {
            $r1 = $s1->fetch_row();
            echo $r1[0];
            return $r1[0];
            
        } else {
            return 0;
        }   
    
}

public function get_indepen_mr($kpidate) {
        $kpi_date = $this->real_escape_string($kpidate);
        $show_date = DateTime::createFromFormat('d/m/Y', $kpi_date)->format('Y-m-d');
   
        echo "<br>";
        $s1 = $this->query("select sum(indep_missroll) from jd2_rolling_breakdown WHERE bd_date= '" . $show_date . "'");
        if ( $s1->num_rows > 0) {
            $r1 = $s1->fetch_row();
            echo $r1[0];
            return $r1[0];
            
        } else {
            return 0;
        }   
    
}

public function get_total_cutting($kpidate) {
        $kpi_date = $this->real_escape_string($kpidate);
        $show_date = DateTime::createFromFormat('d/m/Y', $kpi_date)->format('Y-m-d');
   
        echo "<br>";
        $s1 = $this->query("select sum(total_cutting)from jd2_rolling_breakdown WHERE bd_date= '" . $show_date . "'");
        if ( $s1->num_rows > 0) {
            $r1 = $s1->fetch_row();
            echo $r1[0];
            return $r1[0];
            
        } else {
            return 0;
        }   
    
}

public function get_total_cutting_mill($kpidate) {
        $kpi_date = $this->real_escape_string($kpidate);
        $show_date = DateTime::createFromFormat('d/m/Y', $kpi_date)->format('Y-m-d');
   
        echo "<br>";
        $s1 = $this->query("select sum(total_cutting)from jd2_rolling_breakdown WHERE bd_date= '" . $show_date . "' and dept_id in(3,4,5)");
        if ( $s1->num_rows > 0) {
            $r1 =$s1->fetch_row();
            echo $r1[0];
            return $r1[0];
            
        } else {
            return 0;
        }   
    
}

public function get_total_cutting_ccm($kpidate) {
        $kpi_date = $this->real_escape_string($kpidate);
        $show_date = DateTime::createFromFormat('d/m/Y', $kpi_date)->format('Y-m-d');
   
        echo "<br>";
        $s1 = $this->query("select sum(total_cutting) from jd2_rolling_breakdown WHERE bd_date= '" . $show_date . "' and  dept_id=2");
        if ( $s1->num_rows > 0) {
            $r1 = $s1->fetch_row();
            echo $r1[0];
            return $r1[0];
            
        } else {
            return 0;
        }   
    
}

public function get_total_cutting_fnce($kpidate) {
        $kpi_date = $this->real_escape_string($kpidate);
        $show_date = DateTime::createFromFormat('d/m/Y', $kpi_date)->format('Y-m-d');
        echo "<br>";
        $s1 = $this->query("select sum(total_cutting) from jd2_rolling_breakdown WHERE bd_date= '" . $show_date . "' and  dept_id=1");
        if ( $s1->num_rows > 0) {
            $r1 = $s1->fetch_row();
            echo $r1[0];
            return $r1[0];
            
        } else {
            return 0;
        }   
    
}


public function get_total_cutting_mpeb($kpidate) {
        $kpi_date = $this->real_escape_string($kpidate);
        $show_date = DateTime::createFromFormat('d/m/Y', $kpi_date)->format('Y-m-d');
   
        echo "<br>";
        $s1 = $this->query("select sum(total_cutting) from jd2_rolling_breakdown WHERE bd_date= '" . $show_date . "' and  dept_id=7");
        if ( $s1->num_rows > 0) {
            $r1 = $s1->fetch_row();
            echo $r1[0];
            return $r1[0];
            
        } else {
            return 0;
        }   
    
}


/**public function get_prod_down_time($kpidate,$department) {
        $kpi_date = $this->real_escape_string($kpidate);
        $show_date = DateTime::createFromFormat('d/m/Y', $kpi_date)->format('Y-m-d');
        $dep_name = $this->real_escape_string($department);
        echo "<br>";
        $s1 = $this->query("select TIME_FORMAT((SUM(`bd_total_time`)),'%H:%i') from breakdown b WHERE date= '" . $show_date . "' and department='" . $dep_name . "'");
        if ( $s1->num_rows > 0) {
            $r1 = $s1->fetch_row();
            echo $r1[0];
            $test=$r1[0];
          $test1= date('H', strtotime($test))*60 + date('i', strtotime($test));
            return $test1;
        
            
        } else {
            return 0;
        }   
    
}**/

/**public function get_prod_down_time_dept_min($kpidate, $department) {
        $kpi_date = $this->real_escape_string($kpidate);
        $show_date = DateTime::createFromFormat('d/m/Y', $kpi_date)->format('Y-m-d');
        $dep_name = $this->real_escape_string($department);
        echo "<br>";
        $s1 = $this->query("select  TIME_FORMAT(SUM(`bd_total_time`),'%H:%i')  from `jd2_rolling_breakdown` WHERE `bd_date`= '" . $show_date . "' and dept_id='" . $dep_name . "'");
        if ($s1->num_rows > 0) {
            $r1 = $s1->fetch_row();
            echo $r1[0];
            $totaltime=$r1[0];
            $hour = date('H', strtotime($totaltime)); // we get the firs two values from the hh:mm:ss string 
            //echo"step1before conversion";
            //ECHO $hour;
            //echo"<br>";
           // echo $hour;
           // echo"<br>";
            $hour = (int) $hour * 60;
           // echo"<br>";
           // echo "step2";
           // echo $hour;
            $minute = date('i', strtotime($totaltime));
            $minute = (int) $minute;
            $totaltimeinminutes = $hour + $minute;
            //echo"<br>";
           // echo "minute";
           // echo $totaltimeinminutes;
            return $totaltimeinminutes;
            
        }
        
  else {
            return 0;
        }
    }**/


public function get_prod_down_time_dept_min($kpidate,$department) {
        $kpi_date = $this->real_escape_string($kpidate);
        $show_date = DateTime::createFromFormat('d/m/Y', $kpi_date)->format('Y-m-d');
        $dep_name = $this->real_escape_string($department);
        echo "<br>";
        $s1 = $this->query("select  sum(bd_total_time_minutes)  from jd2_rolling_breakdown WHERE bd_date= '" . $show_date . "' and dept_id='" . $dep_name . "'");
        if ( $s1->num_rows > 0) {
            $r1 = $s1->fetch_row();
            $test2= $r1[0];
   
            return $test2;
        } else {
            return 0;
        }   
    
}
public function get_prod_down_time_reason_min($kpidate,$reason) {
        $kpi_date = $this->real_escape_string($kpidate);
        $show_date = DateTime::createFromFormat('d/m/Y', $kpi_date)->format('Y-m-d');
        $reason_code = $this->real_escape_string($reason);
        
        echo "<br>";
        $s1 = $this->query("select sum(bd_total_time_minutes) from jd2_rolling_breakdown WHERE bd_date = '" . $show_date . "' and reason_id='" . $reason_code . "'");
        if ($s1->num_rows > 0) {
            $r1 =$s1->fetch_row();
              $test=$r1[0];
         
            return $test;
        }
        else {
            return 0;
        }   
}
    public function get_prod_down_time_dept_hr($kpidate,$department) {
        $kpi_date = $this->real_escape_string($kpidate);
        $show_date = DateTime::createFromFormat('d/m/Y', $kpi_date)->format('Y-m-d');
        $dep_name = $this->real_escape_string($department);
        echo "<br>";
        $s1 = $this->query("select  SEC_TO_TIME(SUM(TIME_TO_SEC(`bd_total_time`)))  from jd2_rolling_breakdown WHERE bd_date= '" . $show_date . "' and dept_id='" . $dep_name . "'");
        if ( $s1->num_rows > 0) {
            $r1 = $s1->fetch_row();
            $test2= $r1[0];
   
            return $test2;
        } else {
            return 0;
        }   
    
}

public function get_prod_down_time_reason_hr($kpidate,$reason) {
        $kpi_date = $this->real_escape_string($kpidate);
        $show_date = DateTime::createFromFormat('d/m/Y', $kpi_date)->format('Y-m-d');
        $reason_code = $this->real_escape_string($reason);
        echo $show_date; echo"<br>"; echo $reason_code;
        
        echo "<br>";
        $s1 = $this->query("select SEC_TO_TIME(SUM(TIME_TO_SEC(`bd_total_time`)))  from `jd2_rolling_breakdown` WHERE `bd_date`= '" . $show_date . "' and `reason_id`='" . $reason_code . "'");
        if ($s1->num_rows > 0) {
            $r1 = $s1->fetch_row();
            return $r1[0];
            
        } else {
            return 0;
        }   
    
}




public function get_total_heat_gap($kpidate) {
        $kpi_date = $this->real_escape_string($kpidate);
        $show_date = DateTime::createFromFormat('d/m/Y', $kpi_date)->format('Y-m-d');
        
        
        echo "<br>";
        $s1 = $this->query("select SEC_TO_TIME(SUM(TIME_TO_SEC(`heat_gap`))) from jd2_rolling_per_heat_prod WHERE per_heat_date = '" . $show_date . "' ");
        if ($s1->num_rows > 0) {
            $r1 =$s1->fetch_row();
              $test=$r1[0];
      
            return $test;
        }
        else {
            return 0;
        }   
}
public function get_bypass_prod($kpidate,$department) {
        $kpi_date = $this->real_escape_string($kpidate);
        $show_date = DateTime::createFromFormat('d/m/Y', $kpi_date)->format('Y-m-d');
          $dep_name = $this->real_escape_string($department);
        echo "<br>";
        $s1 = $this->query("select sum(total_billets_bypass_prod)from jd2_rolling_breakdown WHERE bd_date= '" . $show_date . "' and dept_id='" . $dep_name . "'");
        if ( $s1->num_rows > 0) {
            $r1 = $s1->fetch_row();
            echo $r1[0];
            return $r1[0];
            
        } else {
            return 0;
        }   
    
}
public function billets_by_pass_prod_due_ccm($kpidate) {
        $kpi_date = $this->real_escape_string($kpidate);
        $show_date = DateTime::createFromFormat('d/m/Y', $kpi_date)->format('Y-m-d');
         
        echo "<br>";
        $s1 = $this->query("select sum(billets_bypass_prod_ccm) FROM jd2_rolling_per_heat_prod WHERE per_heat_date= '" . $show_date . "'");
        if ( $s1->num_rows > 0) {
            $r1 = $s1->fetch_row();
            echo $r1[0];
            return $r1[0];
            
        } else {
            return 0;
        }   
    
}

public function get_bd_time_of_a_day($kpidate) {
        $kpi_date = $this->real_escape_string($kpidate);
        $show_date = DateTime::createFromFormat('d/m/Y', $kpi_date)->format('Y-m-d');
         
        echo "<br>";
        $s1 = $this->query("select SEC_TO_TIME(SUM(TIME_TO_SEC(`bd_total_time`))) FROM `jd2_rolling_breakdown` WHERE `bd_date`= '" . $show_date . "'");
        if ( $s1->num_rows > 0) {
            $r1 = $s1->fetch_row();
            echo $r1[0];
            return $r1[0];
            
        } else {
            return 0;
        }   
    
}







public function get_bypass_prod_reason($kpidate,$reason) {
        $kpi_date = $this->real_escape_string($kpidate);
        $show_date = DateTime::createFromFormat('d/m/Y', $kpi_date)->format('Y-m-d');
         $reason_code = $this->real_escape_string($reason);
        echo "<br>";
        $s1 = $this->query("select sum(	total_billets_bypass_prod)from jd2_rolling_breakdown WHERE bd_date= '" . $show_date . "' and reason_id='" . $reason_code . "'");
        if ( $s1->num_rows > 0) {
            $r1 = $s1->fetch_row();
            echo $r1[0];
            return $r1[0];
            
        } else {
            return 0;
        }   
    
}



  public function get_billets_bypass_reason($kpidate, $reason) {
        $kpi_date = $this->real_escape_string($kpidate);
        $show_date = DateTime::createFromFormat('d/m/Y', $kpi_date)->format('Y-m-d');
        $reason_code = $this->real_escape_string($reason);
       
        echo "<br>";
        $s1 = $this->query("select sum(total_billets_bypass)from jd2_rolling_breakdown WHERE bd_date= '" . $show_date . "' and reason_id='" . $reason_code . "'");
        if ( $s1->num_rows > 0) {
            $r1 = $s1->fetch_row();
            return $r1[0];
        } else {
            return 0;
        }
    }   

    public function get_8_rfmr($kpidate) {
        $kpi_date = $this->real_escape_string($kpidate);
        $show_date = DateTime::createFromFormat('d/m/Y', $kpi_date)->format('Y-m-d');
       
        echo "<br>";
        $s1 = $this->query("select sum(8rf_missroll_prod)FROM jd2_rolling_per_heat_prod WHERE per_heat_date= '" . $show_date ."'");
        if ($s1->num_rows > 0) {
            $r1 =$s1->fetch_row();
            return $r1[0];
        } else {
            return 0;
        }
    }  
    
    public function get_10_rfmr($kpidate) {
        $kpi_date = $this->real_escape_string($kpidate);
        $show_date = DateTime::createFromFormat('d/m/Y', $kpi_date)->format('Y-m-d');
       
        echo "<br>";
        $s1 = $this->query("select sum(10rf_missroll_prod)FROM jd2_rolling_per_heat_prod WHERE per_heat_date= '" . $show_date ."'");
        if ($s1->num_rows > 0) {
            $r1 = $s1->fetch_row();
            return $r1[0];
        } else {
            return 0;
        }
    }  
    
    public function get_12_rfmr($kpidate) {
        $kpi_date = $this->real_escape_string($kpidate);
        $show_date = DateTime::createFromFormat('d/m/Y', $kpi_date)->format('Y-m-d');
       
        echo "<br>";
        $s1 = $this->query("select sum(12rf_missroll_prod)FROM jd2_rolling_per_heat_prod WHERE per_heat_date= '" . $show_date ."'");
        if ($s1->num_rows > 0) {
            $r1 = $s1->fetch_row();
            return $r1[0];
        } else {
            return 0;
        }
    }  
    
    public function get_16_rfmr($kpidate) {
        $kpi_date = $this->real_escape_string($kpidate);
        $show_date = DateTime::createFromFormat('d/m/Y', $kpi_date)->format('Y-m-d');
       
        echo "<br>";
        $s1 = $this->query("select sum(16rf_missroll_prod)FROM jd2_rolling_per_heat_prod WHERE per_heat_date= '" . $show_date ."'");
        if ($s1->num_rows > 0) {
            $r1 = $s1->fetch_row();
            return $r1[0];
        } else {
            return 0;
        }
    }  
    public function get_20_rfmr($kpidate) {
        $kpi_date = $this->real_escape_string($kpidate);
        $show_date = DateTime::createFromFormat('d/m/Y', $kpi_date)->format('Y-m-d');
       
        echo "<br>";
        $s1 = $this->query("select sum(20rf_missroll_prod)FROM jd2_rolling_per_heat_prod WHERE per_heat_date= '" . $show_date ."'");
        if ($s1->num_rows > 0) {
            $r1 = $s1->fetch_row();
            return $r1[0];
        } else {
            return 0;
        }
    }  
    public function get_25_rfmr($kpidate) {
        $kpi_date = $this->real_escape_string($kpidate);
        $show_date = DateTime::createFromFormat('d/m/Y', $kpi_date)->format('Y-m-d');
       
        echo "<br>";
        $s1 = $this->query("select sum(25rf_missroll_prod)FROM jd2_rolling_per_heat_prod WHERE per_heat_date= '" . $show_date ."'");
        if ($s1->num_rows > 0) {
            $r1 = $s1->fetch_row();
            return $r1[0];
        } else {
            return 0;
        }
    }  
    public function get_28_rfmr($kpidate) {
        $kpi_date = $this->real_escape_string($kpidate);
        $show_date = DateTime::createFromFormat('d/m/Y', $kpi_date)->format('Y-m-d');
       
        echo "<br>";
        $s1 = $this->query("select sum(28rf_missroll_prod)FROM jd2_rolling_per_heat_prod WHERE per_heat_date= '" . $show_date ."'");
        if ($s1->num_rows > 0) {
            $r1 = $s1->fetch_row();
            return $r1[0];
        } else {
            return 0;
        }
    }  
    public function get_32_rfmr($kpidate) {
        $kpi_date = $this->real_escape_string($kpidate);
        $show_date = DateTime::createFromFormat('d/m/Y', $kpi_date)->format('Y-m-d');
       
        echo "<br>";
        $s1 = $this->query("select sum(32rf_missroll_prod)FROM jd2_rolling_per_heat_prod WHERE per_heat_date= '" . $show_date ."'");
        if ($s1->num_rows > 0) {
            $r1 = $s1->fetch_row();
            return $r1[0];
        } else {
            return 0;
        }
    }  
   public function get_8_cut($kpidate) {
        $kpi_date = $this->real_escape_string($kpidate);
        $show_date = DateTime::createFromFormat('d/m/Y', $kpi_date)->format('Y-m-d');
       
        echo "<br>";
        $s1 = $this->query("select sum(8cut_prod)FROM jd2_rolling_per_heat_prod WHERE per_heat_date= '" . $show_date ."'");
        if ($s1->num_rows > 0) {
            $r1 = $s1->fetch_row();
            return $r1[0];
        } else {
            return 0;
        }
    }  
      public function get_10_cut($kpidate) {
        $kpi_date = $this->real_escape_string($kpidate);
        $show_date = DateTime::createFromFormat('d/m/Y', $kpi_date)->format('Y-m-d');
       
        echo "<br>";
        $s1 = $this->query("select sum(10cut_prod)FROM jd2_rolling_per_heat_prod WHERE per_heat_date= '" . $show_date ."'");
        if ($s1->num_rows > 0) {
            $r1 = $s1->fetch_row();
            return $r1[0];
        } else {
            return 0;
        }
    }  
      public function get_12_cut($kpidate) {
        $kpi_date = $this->real_escape_string($kpidate);
        $show_date = DateTime::createFromFormat('d/m/Y', $kpi_date)->format('Y-m-d');
       
        echo "<br>";
        $s1 = $this->query("select sum(12cut_prod)FROM jd2_rolling_per_heat_prod WHERE per_heat_date= '" . $show_date ."'");
        if ($s1->num_rows > 0) {
            $r1 = $s1->fetch_row();
            return $r1[0];
        } else {
            return 0;
        }
    }  
      public function get_16_cut($kpidate) {
        $kpi_date = $this->real_escape_string($kpidate);
        $show_date = DateTime::createFromFormat('d/m/Y', $kpi_date)->format('Y-m-d');
       
        echo "<br>";
        $s1 = $this->query("select sum(16cut_prod)FROM jd2_rolling_per_heat_prod WHERE per_heat_date= '" . $show_date ."'");
        if ($s1->num_rows > 0) {
            $r1 = $s1->fetch_row();
            return $r1[0];
        } else {
            return 0;
        }
    }  
      public function get_20_cut($kpidate) {
        $kpi_date = $this->real_escape_string($kpidate);
        $show_date = DateTime::createFromFormat('d/m/Y', $kpi_date)->format('Y-m-d');
       
        echo "<br>";
        $s1 = $this->query("select sum(20cut_prod)FROM jd2_rolling_per_heat_prod WHERE per_heat_date= '" . $show_date ."'");
        if ($s1->num_rows > 0) {
            $r1 = $s1->fetch_row();
            return $r1[0];
        } else {
            return 0;
        }
    }  
      public function get_25_cut($kpidate) {
        $kpi_date = $this->real_escape_string($kpidate);
        $show_date = DateTime::createFromFormat('d/m/Y', $kpi_date)->format('Y-m-d');
       
        echo "<br>";
        $s1 = $this->query("select sum(25cut_prod)FROM jd2_rolling_per_heat_prod WHERE per_heat_date= '" . $show_date ."'");
        if ($s1->num_rows > 0) {
            $r1 = $s1->fetch_row();
            return $r1[0];
        } else {
            return 0;
        }
    }  
      public function get_28_cut($kpidate) {
        $kpi_date = $this->real_escape_string($kpidate);
        $show_date = DateTime::createFromFormat('d/m/Y', $kpi_date)->format('Y-m-d');
       
        echo "<br>";
        $s1 = $this->query("select sum(28cut_prod)FROM jd2_rolling_per_heat_prod WHERE per_heat_date= '" . $show_date ."'");
        if ( $s1->num_rows > 0) {
            $r1 = $s1->fetch_row();
            return $r1[0];
        } else {
            return 0;
        }
    }  
      public function get_32_cut($kpidate) {
        $kpi_date = $this->real_escape_string($kpidate);
        $show_date = DateTime::createFromFormat('d/m/Y', $kpi_date)->format('Y-m-d');
       
        echo "<br>";
        $s1 = $this->query("select sum(32cut_prod)FROM jd2_rolling_per_heat_prod WHERE per_heat_date= '" . $show_date ."'");
        if ($s1->num_rows > 0) {
            $r1 = $s1->fetch_row();
            return $r1[0];
        } else {
            return 0;
        }
    }  
    
    
     public function get_total_rolled_pcs($kpidate) {
        $kpi_date = $this->real_escape_string($kpidate);
        $show_date = DateTime::createFromFormat('d/m/Y', $kpi_date)->format('Y-m-d');
       
     
        $s1 = $this->query("select sum(total_rolled_pcs)FROM jd2_rolling_per_heat_prod WHERE per_heat_date= '" . $show_date ."'");
        if ($s1->num_rows > 0) {
            $r1 = $s1->fetch_row();
            echo "rolledpcs";
            return $r1[0];
        } else {
            return 0;
        }
    }  
     public function get_total_billets_bypass($kpidate) {
        $kpi_date = $this->real_escape_string($kpidate);
        $show_date = DateTime::createFromFormat('d/m/Y', $kpi_date)->format('Y-m-d');
    
        $s1 = $this->query("select sum(total_billets_bypass) from jd2_rolling_breakdown WHERE bd_date= '" . $show_date ."'");
        if ($s1->num_rows > 0) {
            $r1 = $s1->fetch_row();
            echo "billetsbypass";
            return $r1[0];
        } else {
            return 0;
        }
    }  
    
    
    public function get_total_hotrolling_of_shift($kpidate,$shift) {
        $kpi_date = $this->real_escape_string($kpidate);
        $shift = $this->real_escape_string($shift);
        $show_date = DateTime::createFromFormat('d/m/Y', $kpi_date)->format('Y-m-d');
  
        echo "<br>";
        $s1 = $this->query("select (sum(`rollingprod`)/sum(`ccmprod`))*100 FROM jd2_rolling_per_heat_prod WHERE per_heat_date= '" . $show_date ."' and shift='".$shift."'");
        if ($s1->num_rows > 0) {
            $r1 = $s1->fetch_row();
            
            return $r1[0];
        } else {
            return 0;
        }
    }  
    
    
    
public function get_total_missroll_of_shift($kpidate,$shift) {
        $kpi_date = $this->real_escape_string($kpidate);
        $F_shift = $this->real_escape_string($shift);
        $show_date = DateTime::createFromFormat('d/m/Y', $kpi_date)->format('Y-m-d');

        echo "<br>";
        $s1 = $this->query("select sum(`total_missroll`) from jd2_rolling_breakdown WHERE bd_date= '" . $show_date ."' and shift='".$F_shift."'");
        if ($s1->num_rows > 0) {
            $r1 = $s1->fetch_row();
            
            return $r1[0];
        } else {
            return 0;
        }
    }  
    
    
public function get_missroll_sum($kpidate,$heat_number) {
        $heat_no = $this->real_escape_string($heat_number); 
        $kpi_date = $this->real_escape_string($kpidate);
        $show_date = DateTime::createFromFormat('d/m/Y', $kpi_date)->format('Y-m-d');
        $s1 = $this->query("select sum(`total_missroll`) from jd2_rolling_breakdown WHERE bd_date= '" . $show_date ."' and `heat_number`='".$heat_no."'");
        if ($s1->num_rows > 0) {
            $r1 = $s1->fetch_row();
            
            return $r1[0];
        } else {
            return 0;
        }
    } 
    
  public function get_total_missroll_of_dept_in_shift($kpidate,$F_shift,$F_department) {
        $kpi_date = $this->real_escape_string($kpidate);
        $shift = $this->real_escape_string($F_shift);
        $department = $this->real_escape_string($F_department);
        $show_date = DateTime::createFromFormat('d/m/Y', $kpi_date)->format('Y-m-d');
    
        $s1 = $this->query("select sum(`total_missroll`) from jd2_rolling_breakdown WHERE bd_date= '" . $show_date ."' and shift='".$shift."' and dept_id='".$department."'");
        if ($s1->num_rows > 0) {
            $r1 = $s1->fetch_row();
            
            return $r1[0];
        } else {
            return 0;
        }
    }    
    
    
    
    public function get_First_Heat_Start_Time($kpidate) {
        $kpi_date = $this->real_escape_string($kpidate);
        $show_date = DateTime::createFromFormat('d/m/Y', $kpi_date)->format('Y-m-d');
    
        $s1 = $this->query("select `heat_start_time` from jd2_rolling_per_heat_prod "
                . "where per_heat_date='".$show_date."'"
                . " and per_heat_id in (select min(per_heat_id) "
                . "from jd2_rolling_per_heat_prod where per_heat_date='".$show_date."') ");
        if ( $s1->num_rows > 0) {
            $r1 = $s1->fetch_row();

            return $r1[0];
        } else {
            return 0;
        }
    }  
    public function get_Last_Heat_End_Time($kpidate) {
        $kpi_date = $this->real_escape_string($kpidate);
        $show_date = DateTime::createFromFormat('d/m/Y', $kpi_date)->format('Y-m-d');
    
        $s1 = $this->query("select `heat_end_time` from jd2_rolling_per_heat_prod  "
                . "WHERE per_heat_date='".$show_date."' and "
                . " `heat_number` in (select max(`heat_number`) "
                . "from jd2_rolling_per_heat_prod where per_heat_date='".$show_date."')");
        if ( $s1->num_rows > 0) {
            $r1 = $s1->fetch_row();
            
            return $r1[0];
        } else {
            return 0;
        }
    }  
    
    
    public function get_mr_roughing_side($kpidate,$heat_number) {
        $kpi_date = $this->real_escape_string($kpidate);
        $heatnumber = $this->real_escape_string($heat_number);
        $show_date = DateTime::createFromFormat('d/m/Y', $kpi_date)->format('Y-m-d');
      
      
       //$m2s= $this->real_escape_string($m2_s);
        echo "<br>";
        $s1 = $this->query("SELECT sum(missroll_prod) FROM  jd2_rolling_breakdown  WHERE 
               bd_date ='".$show_date."' and  location_id in(1,2,3,4,5,6) and heat_number='".$heatnumber."' ");
        if ( $s1->num_rows > 0) {
            $r1 = $s1->fetch_row();
            return $r1[0];
        } else {
            return 0;
        }
    }
    
     public function get_rfmr_production_mill_side($perheatdate,$heat_number) {
        $final_date = $this->real_escape_string($perheatdate);
        $heatnumber = $this->real_escape_string($heat_number);

        $show_date = DateTime::createFromFormat('d/m/Y', $final_date)->format('Y-m-d');
      
      
   
        $s1 = $this->query("SELECT sum(missroll_prod) FROM  jd2_rolling_breakdown  WHERE 
               bd_date ='".$show_date."' and heat_number='".$heatnumber."'and location_id in(7,8,9,10,12,13,16,17) ");
        if ( $s1->num_rows > 0) {
            $r1 = $s1->fetch_row();
            return $r1[0];
        } else {
            return 0;
        }
    }
     public function get_8mm_rfside($kpidate) {
        $kpi_date = $this->real_escape_string($kpidate);
        $show_date = DateTime::createFromFormat('d/m/Y', $kpi_date)->format('Y-m-d');
       
        echo "<br>";
        $s1 = $this->query("select sum(	rf_side_8mm_prod)FROM jd2_rolling_per_heat_prod WHERE per_heat_date= '" . $show_date ."'");
        if ($s1->num_rows > 0) {
            $r1 = $s1->fetch_row();
            return $r1[0];
        } else {
            return 0;
        }
    } 
      public function get_10mm_rfside($kpidate) {
        $kpi_date = $this->real_escape_string($kpidate);
        $show_date = DateTime::createFromFormat('d/m/Y', $kpi_date)->format('Y-m-d');
       
        echo "<br>";
        $s1 = $this->query("select sum(	rf_side_10mm_prod)FROM jd2_rolling_per_heat_prod WHERE per_heat_date= '" . $show_date ."'");
        if ($s1->num_rows > 0) {
            $r1 = $s1->fetch_row();
            return $r1[0];
        } else {
            return 0;
        }
    } 
      public function get_12mm_rfside($kpidate) {
        $kpi_date = $this->real_escape_string($kpidate);
        $show_date = DateTime::createFromFormat('d/m/Y', $kpi_date)->format('Y-m-d');
       
        echo "<br>";
        $s1 = $this->query("select sum(	rf_side_12mm_prod)FROM jd2_rolling_per_heat_prod WHERE per_heat_date= '" . $show_date ."'");
        if ($s1->num_rows > 0) {
            $r1 = $s1->fetch_row();
            return $r1[0];
        } else {
            return 0;
        }
    } 
       public function get_16mm_rfside($kpidate) {
        $kpi_date = $this->real_escape_string($kpidate);
        $show_date = DateTime::createFromFormat('d/m/Y', $kpi_date)->format('Y-m-d');
       
        echo "<br>";
        $s1 = $this->query("select sum(	rf_side_16mm_prod)FROM jd2_rolling_per_heat_prod WHERE per_heat_date= '" . $show_date ."'");
        if ($s1->num_rows > 0) {
            $r1 = $s1->fetch_row();
            return $r1[0];
        } else {
            return 0;
        }
    } 
      public function get_20mm_rfside($kpidate) {
        $kpi_date = $this->real_escape_string($kpidate);
        $show_date = DateTime::createFromFormat('d/m/Y', $kpi_date)->format('Y-m-d');
       
        echo "<br>";
        $s1 = $this->query("select sum(	rf_side_20mm_prod)FROM jd2_rolling_per_heat_prod WHERE per_heat_date= '" . $show_date ."'");
        if ($s1->num_rows > 0) {
            $r1 = $s1->fetch_row();
            return $r1[0];
        } else {
            return 0;
        }
    } 
     public function get_25mm_rfside($kpidate) {
        $kpi_date = $this->real_escape_string($kpidate);
        $show_date = DateTime::createFromFormat('d/m/Y', $kpi_date)->format('Y-m-d');
       
        echo "<br>";
        $s1 = $this->query("select sum(	rf_side_25mm_prod)FROM jd2_rolling_per_heat_prod WHERE per_heat_date= '" . $show_date ."'");
        if ($s1->num_rows > 0) {
            $r1 = $s1->fetch_row();
            return $r1[0];
        } else {
            return 0;
        }
    } 
     public function get_28mm_rfside($kpidate) {
        $kpi_date = $this->real_escape_string($kpidate);
        $show_date = DateTime::createFromFormat('d/m/Y', $kpi_date)->format('Y-m-d');
       
        echo "<br>";
        $s1 = $this->query("select sum(	rf_side_28mm_prod)FROM jd2_rolling_per_heat_prod WHERE per_heat_date= '" . $show_date ."'");
        if ($s1->num_rows > 0) {
            $r1 = $s1->fetch_row();
            return $r1[0];
        } else {
            return 0;
        }
    } 
    
     public function get_32mm_rfside($kpidate) {
        $kpi_date = $this->real_escape_string($kpidate);
        $show_date = DateTime::createFromFormat('d/m/Y', $kpi_date)->format('Y-m-d');
       
        echo "<br>";
        $s1 = $this->query("select sum(	rf_side_32mm_prod)FROM jd2_rolling_per_heat_prod WHERE per_heat_date= '" . $show_date ."'");
        if ($s1->num_rows > 0) {
            $r1 = $s1->fetch_row();
            return $r1[0];
        } else {
            return 0;
        }
    } 
    
        }
        
   
 