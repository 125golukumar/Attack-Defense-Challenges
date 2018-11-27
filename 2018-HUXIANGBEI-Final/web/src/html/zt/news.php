﻿<?php
include("../inc/conn.php");
include("top.php");
include("bottom.php");
include("left.php");

$fp="../skin/".$skin."/news.htm";
if (file_exists($fp)==false){
WriteErrMsg($fp.'模板文件不存在');
exit;
}
$f = fopen($fp,'r');
$strout = fread($f,filesize($fp));
fclose($f);

if (isset($_REQUEST['bigclass'])){
$bigclass=$_REQUEST['bigclass'];
}else{
$bigclass="";
}

$pagetitle=$comane."—新闻";
$pagekeywords=$comane."—新闻";
$pagedescription=$comane."—新闻";


if (isset($_REQUEST["page_size"])){
$page_size=$_REQUEST["page_size"];
checkid($page_size);
setcookie("page_size_news",$page_size,time()+3600*24*360);
}else{
	if (isset($_COOKIE["page_size_news"])){
	$page_size=$_COOKIE["page_size_news"];
	}else{
	$page_size=5;
	}
}

if( isset($_GET["page"]) && $_GET["page"]!="") 
{
    $page=$_GET['page'];
}else{
    $page=1;
}
$list=strbetween($strout,"{loop}","{/loop}");

$sql="select * from zzcms_zx where editor='".$editor."'and bigclassname='公司新闻' and passed=1 ";
$rs = query($sql); 
$offset=($page-1)*$page_size;//$page_size在上面被设为COOKIESS
$totlenum= num_rows($rs);  
$totlepage=ceil($totlenum/$page_size);

$sql=$sql." order by id desc limit $offset,$page_size";
$rs = query($sql); 
$row= num_rows($rs);//返回记录数
if(!$row){
$strout=str_replace("{#fenyei}","",$strout) ;
$strout=str_replace("{loop}".$list."{/loop}","暂无信息",$strout) ;
}else{
$list2='';
$i=1;
while ($row= fetch_array($rs)){

if (whtml=="Yes"){
$link="newsshow-".$row['id'].".htm";
}else{
$link="newsshow.php?newsid=".$row['id'] ;
}
$list2 = $list2. str_replace("{#link}" ,$link,$list) ;
$list2 =str_replace("{#title}",cutstr($row["title"],20),$list2) ;
$list2 =str_replace("{#sendtime}",date("Y-m-d",strtotime($row['sendtime'])),$list2) ;				
$i=$i+1;
}
$fenyei='';
if ($page<>1) {
		if (whtml=="Yes") {
			$fenyei=$fenyei. "<a href='/news/news-".$id."-".($page-1).".htm'>上一页</a>";
			}else{
			$fenyei=$fenyei. "<a href='?id=".$id."&page=".($page-1)."'>上一页</a>";
		}
}
	for($a=1; $a<=$totlepage;$a++){
		if (whtml=="Yes") {
			if ($page==$a) {
			$fenyei=$fenyei. "<span>".$a."</span>";
			}else{
			$fenyei=$fenyei. "<a href='/news/news-".$id."-".$a.".htm'>".$a."</a>";
			}
		}else{
			if ($page==$a) {
			$fenyei=$fenyei. "<span>".$a."</span>";
			}else{
			$fenyei=$fenyei. "<a href='?id=".$id."&page=".$a."' >".$a."</a>";
			}
		}
	}
if ($page<>$totlepage) {
			if (whtml=="Yes") {
			$fenyei=$fenyei. "<a href='/news/news-".$id."-".($page+1).".htm'>下一页</a> ";
			}else{
			$fenyei=$fenyei. "<a href='?id=".$id."&page=".($page+1)."'>下一页</a> ";
			}
}
if ($totlepage>1){
$fenyei=$fenyei. "<select name='select' onChange=if(this.options[this.selectedIndex].value!=''){location=this.options[this.selectedIndex].value;}>";
for($a=1; $a<=$totlepage;$a++){
			if (whtml=="Yes") {
				if ($a==$page) {
				$fenyei=$fenyei. "<option value='/news/news-".$id."-".$a.".htm' selected>第".$a."页</option>";
				}else{
				$fenyei=$fenyei. "<option value='/news/news-".$id."-".$a.".htm'>第".$a."页</option>";
				}
			}else{
				if ($a==$page) {
				$fenyei=$fenyei. "<option value='?id=".$id."&page=".$a."' selected>第".$a."页</option>";
				}else{
				$fenyei=$fenyei. "<option value='?id=".$id."&page=".$a."' >第".$a."页</option>";
				}
			}
}
$fenyei=$fenyei. " </select>";
}
$strout=str_replace("{loop}".$list."{/loop}",$list2,$strout) ;
$strout=str_replace("{#fenyei}",$fenyei,$strout) ;
}

$strout=str_replace("{#siteskin}",siteskin,$strout) ;
$strout=str_replace("{#sitename}",sitename,$strout) ;
$strout=str_replace("{#siteurl}",siteurl,$strout);
$strout=str_replace("{#pagetitle}",$pagetitle,$strout);
$strout=str_replace("{#pagekeywords}",$pagekeywords,$strout);
$strout=str_replace("{#pagedescription}",$pagedescription,$strout);
$strout=str_replace("{#ztleft}",$siteleft,$strout);
$strout=str_replace("{#showdaohang}",$showdaohang,$strout);
$strout=str_replace("{#skin}",$skin,$strout);

$strout=str_replace("{#sitebottom}",$sitebottom,$strout);
$strout=str_replace("{#sitetop}",$sitetop,$strout);

echo  $strout;
?>