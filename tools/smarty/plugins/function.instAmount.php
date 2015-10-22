<?php
function  smarty_function_instAmount ($params) {
  $r = 0.25/365; #daily int
  $X = $params['amount']/100; #total loan
  $aux = 1;  #first inst value
  for ($i=0; $i< $params['installments']-2;$i++) {
    $aux = $aux + pow(1/(1+$r) ,(45+30*$i));
        echo $aux."<br>";
  }
  return ($X/$aux);
}
?>
