<?php
require_once("vendor/autoload.php");
require_once("classes/Customer.php");

$customer = new Customer;
$customer->getTransactions("../data.csv");
