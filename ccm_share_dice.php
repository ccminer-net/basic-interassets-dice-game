<?php
function roll () {
    return mt_rand(0,10000);
}
if  (is_user_logged_in()) {
$servername = [YOUR SERVER IP ADDRESS];
$username = [YOUR DATABASE USERNAME];
$password = [YOUR DATABASE PASSWORD];
$dbname = [YOUR DATABSE NAME];
global $current_user;
get_currentuserinfo();
$id = get_current_user_id() ;
$conn = new mysqli($servername, $username, $password, $dbname);
echo ('
<strong><center><h3>TRY TO WIN FREE SHARES WITH YOUR CCM!!!</h3></center></strong>
<strong><center>______</center></strong><br>
<form name="SellCCM" action="https://ccminer.net/win-shares/" method="POST">
<strong><center>YOU MUST PICK A NUMBER BIGGER THAN 5500 TO WIN!<br>YOU HAVE THE 45% OF POSSIBILITY TO WIN 10 SHARES</br>CCM RATE: 1 CCM=20 SATOSHI(0.00000020 BTC)</strong></center>
<strong><center>CCM <input style="height:35px; width:153px" name="BET_CCM" type="number" value="10000" min="10000" step="10000" numberFormat: "1" /></strong></center>
<center><input type="submit" name="bet_submitted" value="PLAY" /></center>
</form>');
if (isset ($_POST['bet_submitted'])){
$sql ="SELECT user_id,meta_key,meta_value FROM wp_usermeta";
$results = $conn->query($sql);
if ($results->num_rows > 0) {
    while($row = $results->fetch_assoc()) {
	if ($row["user_id"]==$id){
    		if ($row["meta_key"]=='mycred_default'){
    		$CCM_value=$row["meta_value"];
    		}
    		if ($row["meta_key"]=='shares'){
    		$SHARES_value=$row["meta_value"];
    		if ($_POST['BET_CCM']>$CCM_value){
    		echo "you don't have enough balance to play this game";
    		}else{		  		
		$result=roll();
		$lost=$CCM_value-$_POST["BET_CCM"];
		$lost_log=-$_POST["BET_CCM"];
		$win_log=$_POST["BET_CCM"]/1000;
		$win=$SHARES_value+$win_log;
		if ($result < 5500){
		echo ('<center><h3>'.$result.'</center></h3><br><center><h3>YOU LOST '.$_POST["BET_CCM"].' CCM</center></h3><br>');
		$sql = "UPDATE wp_usermeta SET meta_value= $lost WHERE user_id=$id AND meta_key='mycred_default'";
    		$conn->query($sql); 
    		$t=time();
    		$sql2 ="INSERT INTO wp_myCRED_log (user_id, creds,ctype,time,entry) VALUES ('$id','$lost_log','mycred_default','$t','LOST A BET')";
		$conn->query($sql2);
		}else{
		echo('<center><h3>'.$result.'</center></h3><br><center><h3>YOU WON '.$win_log.' SHARES</center></h3><br>');
		echo($SHARES_value);
		$sql = "UPDATE wp_usermeta SET meta_value= $lost WHERE user_id=$id AND meta_key='mycred_default'";
    		$conn->query($sql);
		$sql = "UPDATE wp_usermeta SET meta_value= $win WHERE user_id=$id AND meta_key='shares'";
    		$conn->query($sql); 
    		$t=time();
    		$sql2 ="INSERT INTO wp_myCRED_log (user_id, creds,ctype,time,entry) VALUES ('$id','$lost_log','mycred_default','$t','WIN SHARES')";
		$conn->query($sql2);
    		$sql2 ="INSERT INTO wp_myCRED_log (user_id, creds,ctype,time,entry) VALUES ('$id','$win_log','shares','$t','WIN SHARES')";
		$conn->query($sql2);
}
}
}
}
}
}
}
}
