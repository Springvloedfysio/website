<!-- PHP easy-form -->
<?

//   Copyright (C) 2004 CentralFloridaVA.com. All rights reserved.

//	 PHPeasy-form version 1.1
//   Released 2004-10-02

//   This file is part of PHPeasy-form.

//   PHPeasy-form is free software; you can redistribute it and/or modify
//   it under the terms of the GNU General Public License as published by
//   the Free Software Foundation; either version 2 of the License, or
//   (at your option) any later version.

//   PHPeasy-form is distributed in the hope that it will be useful,
//   but WITHOUT ANY WARRANTY; without even the implied warranty of
//   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//   GNU General Public License for more details.

//   You should have received a copy of the GNU General Public License
//   along with PHPeasy-form; if not, write to the Free Software
//   Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
	
//	 Contact CentralFloridaVA.com at:
//	 http://www.CentralFloridaVA.com
	
//	++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

$naam = $_POST["name"];
$adres = $_POST["address"];
$plaats = $_POST["city"];
$telefoon= $_POST["phone"];
$email = $_POST["email"];
$commentaar = $_POST["comments"];


$today = date("d M, Y");
$recipient = "info@springvloedfysio.nl";
$subject = "Webformulier";
$forminfo =
"Naam: $naam
Adres: $adres
Plaats: $plaats
Telefoon: $telefoon
Email: $email\n

Commentaar: $commentaar\n

$today \nCopyright Springvloed Fysio";

$formsend = mail("$recipient", "$subject", "$forminfo", "From: $email\r\nReply-to:$email");
?>
<!-- end PHP easy-form -->
<html>

<head>
<title>Springvloed Fysiotherapie</title>
<meta name="generator" content="Namo WebEditor v4.0">
<meta name="author" content="Craftwerk">
<META HTTP-EQUIV="CACHE-CONTROL" CONTENT="NO-CACHE">
<meta http-equiv="pragma" content="no-cache"> 
<meta http-equiv="Refresh" content="URL=index.html">
<link rel="stylesheet" href="defaultcss">
<style>
<!--
@import url(defaultcss.css);
-->
</style>
</head>

<body bgcolor="#2485A8" text="black" link="blue" vlink="purple" alink="red" leftmargin="0" marginwidth="0" topmargin="0" marginheight="0">
<table align="center" border="0" cellpadding="0" cellspacing="0" width="740" style="line-height:140%;">
    <tr>
        <td height="223" align="center" valign="bottom" style="background-image:url('images/koplogo.jpg'); background-repeat:no-repeat; background-position:50% 0%; margin:0; padding:0;">
            <p>&nbsp;<script menumaker src="menubalk.js"></script></p>
        </td>
    </tr>
    <tr>
        <td style="background-image:url('images/nautilus-backdrop.jpg'); background-repeat:no-repeat; background-position:50% 0%; margin-top:0; margin-right:40; margin-bottom:40; margin-left:40; padding-top:0; padding-right:40; padding-bottom:40; padding-left:40;" height="695" valign="top">
            <p style="line-height:120%;">&nbsp;</p>
            <table style="line-height:120%;" border="0" cellpadding="0" cellspacing="30" width="660" align="center">
                <tr>
                    <td width="620" height="252" align="left" valign="top" style="background-image:url('images/koptxtcelhoekjes.gif'); background-repeat:no-repeat; background-attachment:fixed;">            
                        <p><span
style="color:teal;"><font color="#1494C4"><img src="images/form-send_php_smartbutton1.gif" namo_npi=":EmbededNPI1" border="0" width="140" height="39"><br><img src="images/form-send_php_smartbutton2.gif" namo_npi=":EmbededNPI2" border="0" width="251" height="39"></font></span></p>
<table width="100%" cellpadding="0" align="center" style="border-collapse:collapse;" cellspacing="0">
    <tr>
        <td width="794" height="200" align="left" valign="top" style="border-width:0; border-color:black; border-style:none;">
            <p align="left"><font face="Verdana" size="2" color="#333333">Bedankt voor uw bericht.<br>Onderstaande 
                                    gegevens zijn door u verzonden en zullen 
                                    vertrouwelijk worden behandeld.</font></p>
<table width="535" border="0" cellspacing="0" cellpadding="0" style="font-family:Verdana,sans-serif; color:rgb(77,77,77);">
  <tr> 
    <td width="525" height="27"> <p align="left"><font face="Verdana" size="2">&nbsp;</font></p>
	<p><font face="Verdana" size="2"><? echo nl2br($forminfo); ?></font></p>
  </tr>
</table>
</td>
        <td width="86" height="200" align="left" valign="top" style="border-width:0; border-color:black; border-style:none;">
            <p>&nbsp;</p>
</td>
    </tr>
</table>
                    </td>
                </tr>
                <tr>
                    <td width="620" height="81" align="left" valign="top" style="background-image:url('images/koptxtcelhoekjes.gif'); background-repeat:no-repeat; background-attachment:fixed;">            
                                <p>Martin Springvloed<br>'s-Gravendijkwal 66 
                                <br>3014 EG &nbsp;Rotterdam<br>010-2770274<br><a href="mailto:info@springvloedfysio.nl">info@springvloedfysio.nl</a><br><a href="http://www.springvloedfysio.nl">www.springvloedfysio.nl</a><br></p>
                    </td>
                </tr>
            </table>
</td>
    </tr>
    <tr>
        <td align="left" height="25" style="background-image:url('images/cell-backdrop.jpg'); background-repeat:repeat-y; background-position:50% 0%;">
            <hr size="1" width="80%" align="center" color="#BFBFC1">        
            <p align="center"><img src="images/copyright-backdrop.gif" width="568" height="57" border="0"></p>
        </td>
    </tr>
</table>
</body>

<!--Namo WebEditor Data 4.0
:EmbededNPI1
agkAAHhecwny92JkYGY4xsDAoAPEQYwMDMFAGkgx3LhxAyjDDGQdB2I9IPYFCvsCaWwApAME
0tLS4NIzZ87EoXpUmB4h4KyQr5CnUKKQqJAMJBkU0vKLcktzMlOLFMpSi6ry81JS8+jhjAGz
Izi/tCw1L7NIwadEwSmEgcEpPyeFIYUhL4VMF4kA9TFB9YLyRTIw0Qch0fjyxf8GhGxDAxKH
TLcQp63m////HsQpHVU1YkLAsSgzMUfBKScxOZsqfhZAMgWULx4CMTsQswDzhyoOG2D1Rf3R
GUgqFlDFPaOGkBcCaZmpOQxANFJBVGJBmkdpbl5xiUJoDqTKoAgIIdUXAKv8NZs=
-->
<!--Namo WebEditor Data 4.0
:EmbededNPI2
agkAAHhecwny92JkYGY4xsDAoAPEBxkZGIKBNJBiuHHjBlCGGcg6DsR6QJwOFHYG0tgASAcI
pKWlwaVnzpyJQ/WoMD1CwC2/KLc0JzO1SKEstagqPy8lNY+BAZ1PD4cMkB3B+aVlqXmZRQo+
JQpOIQwMTvk5KQwpDHkpZLqHD6iPCaoXlC+SgYk+CInGly/+NyBkGxqQOGS6hThtNf////cg
TumoqhETAo5FmYk5Ck45icnZVPGzAJIpoHzxEIjZgZgFmD9UcdgAqy/qj85AUrGAKu4ZNYS8
EEjLTM1hAKKRCqISC9I8SnPziksUQnMgVQZFQAipvgAAM1E3zw==
-->

































































</html>
