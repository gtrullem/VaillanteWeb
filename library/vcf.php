<?php

class VCF {
    var $data;
    var $name;
	
    function VCF($data) {
        $this->name = $data['firstname']." ".$data['surname'];
        //$this->data = "BEGIN:VCARD\nVERSION:3\nREV:".date("Ymd\THis\Z")."\nFN:".$data['firstname']." ".$data['surname']."\nN:".$data['surname'].";".$data['firstname']."\nNICKNAME:".$data['nickname']."\nBDAY:".$data['birthday']."\nORG:".$data['company']."\nTITLE:".$data['jobtitle']."\nADR;WORK;ENCODING=QUOTED-PRINTABLE:;;".$data['workbuilding']."=0A".$data['workstreet'].";".$data['worktown'].";".$data['workcounty'].";".$data['workpostcode'].";".$data['workcountry']."\nADR;HOME;ENCODING=QUOTED-PRINTABLE:;;".$data['homebuilding']."=0A".$data['homestreet'].";".$data['hometown'].";".$data['homecounty'].";".$data['homepostcode'].";".$data['homecountry']."\nTEL;WORK;VOICE:".$data['worktelephone']."\nTEL;HOME;VOICE:".$data['hometelephone']."\nTEL;CELL;VOICE:".$data['mobile']."\nEMAIL;WORK;INTERNET:".$data['workemail']."\nEMAIL;HOME;INTERNET:".$data['homeemail']."\nURL;WORK:".$data['workurl']."\nURL;HOME:".$data['homeurl']."\nNOTE;ENCODING=QUOTED-PRINTABLE:".preg_replace('/[\n\r]/','=0A',$data['notes'])."\nEND:VCARD";
                $this->data = "BEGIN:VCARD\nVERSION:3\nREV:".date("Ymd\THis\Z")."\nFN:".$data['firstname']." ".$data['surname']."\nN:".$data['surname'].";".$data['firstname']."\nNICKNAME:".$data['nickname']."\nBDAY:".$data['birthday']."\nORG:".$data['company']."\nTITLE:".$data['jobtitle']."\nADR;WORK;ENCODING=QUOTED-PRINTABLE:;;".$data['workbuilding']."=0A".$data['workstreet'].";".$data['worktown'].";".$data['workcounty'].";".$data['workpostcode'].";".$data['workcountry']."\nADR;HOME;ENCODING=QUOTED-PRINTABLE:;;".$data['homebuilding']." ".$data['homestreet'].";".$data['hometown'].";".$data['homecounty'].";".$data['homepostcode'].";".$data['homecountry']."\nTEL;WORK;VOICE:".$data['worktelephone']."\nTEL;HOME;VOICE:".$data['hometelephone']."\nTEL;CELL;VOICE:".$data['mobile']."\nEMAIL;WORK;INTERNET:".$data['workemail']."\nEMAIL;HOME;INTERNET:".$data['homeemail']."\nURL;WORK:".$data['workurl']."\nURL;HOME:".$data['homeurl']."\nNOTE;ENCODING=QUOTED-PRINTABLE:".preg_replace('/[\n\r]/','=0A',$data['notes'])."\nEND:VCARD";

    }
	
    function save() {
        file_put_contents($this->name.'.vcf',$this->data);
    }
	
    function show() {
        header('Content-type:text/x-vcard');
        header('Content-Disposition: attachment; filename="'.$this->name.'.vcf"');
        Header('Content-Length: '.strlen($this->data));
        Header('Connection: close');
        echo $this->data;
    }
}

?>