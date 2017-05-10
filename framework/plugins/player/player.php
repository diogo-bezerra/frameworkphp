<%@LANGUAGE="VBSCRIPT" CODEPAGE="1252"%>

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>player</title>
<script type="text/javascript">
function MM_CheckFlashVersion(reqVerStr,msg){
  with(navigator){
    var isIE  = (appVersion.indexOf("MSIE") != -1 && userAgent.indexOf("Opera") == -1);
    var isWin = (appVersion.toLowerCase().indexOf("win") != -1);
    if (!isIE || !isWin){  
      var flashVer = -1;
      if (plugins && plugins.length > 0){
        var desc = plugins["Shockwave Flash"] ? plugins["Shockwave Flash"].description : "";
        desc = plugins["Shockwave Flash 2.0"] ? plugins["Shockwave Flash 2.0"].description : desc;
        if (desc == "") flashVer = -1;
        else{
          var descArr = desc.split(" ");
          var tempArrMajor = descArr[2].split(".");
          var verMajor = tempArrMajor[0];
          var tempArrMinor = (descArr[3] != "") ? descArr[3].split("r") : descArr[4].split("r");
          var verMinor = (tempArrMinor[1] > 0) ? tempArrMinor[1] : 0;
          flashVer =  parseFloat(verMajor + "." + verMinor);
        }
      }
      // WebTV has Flash Player 4 or lower -- too low for video
      else if (userAgent.toLowerCase().indexOf("webtv") != -1) flashVer = 4.0;

      var verArr = reqVerStr.split(",");
      var reqVer = parseFloat(verArr[0] + "." + verArr[2]);
  
      if (flashVer < reqVer){
        if (confirm(msg))
          window.location = "http://www.macromedia.com/shockwave/download/download.cgi?P1_Prod_Version=ShockwaveFlash";
      }
    }
  } 
}
</script>
<link href="../../styles/global_style.css" rel="stylesheet" type="text/css" />
<script type="text/javascript"
	src="http://ajax.googleapis.com/ajax/libs/swfobject/2.2/swfobject.js">
</script>
</head>

<%
video = request.QueryString("video")
nome = request.QueryString("nome")
%>
<body>
<%
if video <> "" then
	video = video&".flv"
%>
	<span id="loading"><img src="../../images/loading.gif" /> <font class="text12_cinza"><b>Carregando...</b></font></span><br />
	<script type="text/javascript">
		setTimeout("document.getElementById('loading').innerHTML='<font class=\"text12_cinza\"><b><%=nome%></b></font>'",8000);
	</script>
<%
end if
%>
<table bgcolor="#999999" border="0px">
<tr>
<td>

<script type="text/javascript">
var flashvars = { file:'<%=video%>',autostart:'true' };
var params = { allowfullscreen:'true', allowscriptaccess:'always' };
var attributes = { id:'player1', name:'player1' };
swfobject.embedSWF('player.swf','container1','500','370','9.0.115','false',
flashvars, params, attributes);
</script>
<p id="container1" style="border:2px solid #000000">Por favor instale o Flash Plugin</p>
<!-- <object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,0,0" width="400" height="300" id="FLVPlayer">
  <param name="movie" value="FLVPlayer_Progressive.swf" />
  <param name="salign" value="lt" />
  <param name="quality" value="high" />
  <param name="scale" value="noscale" />
  <param name="FlashVars" value="&MM_ComponentVersion=1&skinName=Corona_Skin_3&streamName=<%'=video%>&autoPlay=true&autoRewind=false" />
  <embed src="FLVPlayer_Progressive.swf" flashvars="&MM_ComponentVersion=1&skinName=Corona_Skin_3&streamName=<%'=video%>&autoPlay=true&autoRewind=false" quality="high" scale="noscale" width="400" height="300" name="FLVPlayer" salign="LT" type="application/x-shockwave-flash" />
</object> -->


</td>
</tr>
</table>
</body>
</html>
