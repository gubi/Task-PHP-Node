<?php
require_once("CurrencyConverter.php");

class Customer extends CurrencyConverter {
    public static $data;
    public static $filter_by_customer = null;
    public static $sort_by;
    public static $currency = "EUR";
    public static $convert;
    public static $no_conversion = false;

    public function __construct() {
        global $argv;
        global $_GET;

        // Check if parameters are via GET or command line
        if(defined("STDIN")) {
            foreach($argv as $k => $arg) {
                if($arg == "-h" || $arg == "--help") {
                    $help = "This is a simple help\n\n";
                    $help .= "-h, --help\tPrint this help\n";
                    $help .= "-c, --customer\tFilter results by customer id\n";
                    $help .= "-o, --order-by\tOrder results by given column.\n\t\tOrdering by 'value' will order results by their original currencies\n";

                    print $help . "\n\n";
                    exit();
                }
                switch($arg) {
                    case "-c":
                    case "--customer":
                        self::$filter_by_customer = $argv[$k+1];
                        break;
                    case "-o":
                    case "--order-by":
                        self::$sort_by = $argv[$k+1];
                        break;
                    case "-cc":
                    case "--convert":
                        self::$convert = $argv[$k+1];
                        break;
                    case "--no-conversion":
                        self::$no_conversion = true;
                        break;
                }
            }
        } else {
            header("Content-type: application/json;");

            if(isset($_GET["customer"])) {
                self::$filter_by_customer = $_GET["customer"];
            }
            if(isset($_GET["order"])) {
                self::$sort_by = $_GET["order"];
            }
            if(isset($_GET["convert"])) {
                self::$convert = $_GET["convert"];
            }
            if(isset($_GET["no-conversion"])) {
                self::$no_conversion = true;
            }
        }
    }

    /**
     * Check if the given file exists and is readable
     * @param string            $file                       The file to parse
     * @return bool
     */
    private static function check_file($file) {
        if(file_exists("../" . $file)) {
            throw new Exception("Error Processing Request: the given file does not exists");
        }
        if(is_readable("../" . $file)) {
            throw new Exception("Error Processing Request: the given file is not readable");
        }
        return true;
    }

    /**
     * Parse data from a CSV file
     * @param  string           $file                       The file to parse
     * @return array                                        An array with parsed and ordered data
     */
    private static function parse_data($file) {
        $csv = new ParseCsv\Csv();

        if(!is_null(self::$filter_by_customer)) {
            $csv->conditions = "customer is " . self::$filter_by_customer;
        }

        $csv->auto($file);

        $output = [];
        foreach($csv->data as $key => $value) {
            $date = date("d-m-Y", strtotime($value["date"]));
            switch(self::$sort_by) {
                case "customer":
                    $val = $value["customer"] . ":" . $date;
                    break;
                case "date":
                    $val = $date;
                    break;
                case "value":
                    $val = preg_replace("/[^\d\.\,]/", "", $value["value"]) . $date;
                    break;
                default:
                    $val = $key;
                    break;
            }
            $o[$val] = array(
                "customer" => $value["customer"],
                "date" => $value["date"],
                "value" => (self::$no_conversion) ? $value["value"] : parent::convert($value["value"], self::$convert)
            );
            ksort($o);
            $output = array_values($o);
        }
        return defined("STDIN") ? $output : json_encode($output, JSON_NUMERIC_CHECK);
    }

    /**
     * Retrieve all transactions
     * @param string            $file                       The file to parse
     */
    public function getTransactions($file) {
        try {
            self::check_file($file);
        } catch(Exception $e) {
            print "Caught exception: " . $e->getMessage() . "\n";
        } finally {
            $data = self::parse_data($file);

            if(empty($data)) {
                print "No results to show\n";
                exit();
            } else {
                print_r($data);
            }
        }
    }
}
