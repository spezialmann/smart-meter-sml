<?php
class SML_PARSER {
    public $files;
    public $data;
    public $total_power_consumption_value;
    public $current_power_value;
    public $last_update;

    /**
     * Parse sml file 
     */
    public function parse_sml_file($filename) {
        $hexdata = $this->hexToStr($filename);
        $this->parse_sml_hexdata($hexdata);
    }

    /**
     * Preparing HEX DATA
     */
    private function hexToStr($hexfile) {
        $string = '';

        if (file_exists($hexfile)) {
            $this->last_update = date ("d.m.Y H:i:s", filemtime($hexfile));
        }

        $fn = fopen($hexfile,"r");
        while(! feof($fn))  {
            $line = trim(fgets($fn));
            //echo "Line origin--->>>" . $line . "--";
            $line = strtoupper(substr($line, 8));
            $line = str_replace(" ", "", $line);
            //echo "Line--->>>" . $line . "---LAENGE--->>>" . strlen($line) . "---\n\r";
            $string .= $line;
            
        }
        fclose($fn);
        
        return $string;
    }
    
    /**
     * sml 2 hexdata
     */
    private function parse_sml_hexdata($hexdata) {
        //Muster fuer Start einer Nachricht
        $sml_header='1B1B1B1B01010101';
        //Muster fuer Ende einer Nachricht
        $sml_footer='1B1B1B1B1A';
        //Muster fuer Zaehlerstand 
        $id_consumption =   "77070100010800FF";
        //Muster fuer aktuelle Leistung
        $id_current_power = "77070100100700FF";
        //Muster nach der aktuellen Leistung
        $after_current_power = "01010163";

        //Start der Nachricht vorhanden?
        $start = strpos($hexdata,$sml_header);
        if($start===false) {
            print_r("No start seq");
            return;
        }
        if($start) {
            //Nachricht extrahieren
            $arr = explode($sml_header, $hexdata);
            $hexdata = $arr[1];
        }

        $arr = explode($sml_footer, $hexdata);
        $this->data = $arr[0]; // eine Nachricht

        //Zaehlerstand und aktuelle Leistung aus der Nachricht extrahieren
        $arr = explode($id_consumption , $this->data);
        $consumption_string = $arr[1];

        //Zaehlerstand
        $arr = explode($id_current_power , $consumption_string);
        $consumption_string = $arr[0];
        $consumtion_hex = substr($consumption_string, -10, 8);
        $this->total_power_consumption_value = (hexdec($consumtion_hex))/10000;

        //Aktuelle Leistung
        $power_string_arr = explode($after_current_power, $arr[1]);
        $current_power_hex = substr($power_string_arr[0], -6);
        $this->current_power_value = (hexdec($current_power_hex))/1000;
    }

}
