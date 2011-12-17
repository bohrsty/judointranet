<?php
/*
 * Created on 14.12.2011
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */



require_once(dirname(__FILE__).'/../html2pdf.class.php');


$fh = fopen(dirname('__FILE__').'/res/_bfv.html','r');

$content = fread($fh,filesize(dirname('__FILE__').'/res/_bfv.html'));

try {
        // init HTML2PDF
        $html2pdf = new HTML2PDF('P', 'A4', 'de', true, 'UTF-8', array(0, 0, 0, 0));

        // display the full page
//        $html2pdf->pdf->SetDisplayMode('fullpage');

        // convert
        $html2pdf->writeHTML($content, isset($_GET['vuehtml']));

        // send the PDF
        $html2pdf->Output('bfv.pdf');
}
catch(HTML2PDF_exception $e) {
        echo $e;
        exit;
}




?>
