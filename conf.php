<?php
 $id = (int) $_GET['id'];
 $token = (String) htmlentities($_GET['token']);
 if(!isset($id)){
	$valid = false;
	$err_mess = "Le lien est erroné";

}elseif(!isset($token)){
	$valid = false;
	$err_mess = "Le lien est erroné";
}

if($valid){
	$req = $DB->query("SELECT id 
		FROM utilisateurs 
		WHERE id = ? AND token = ?",
		array($id, $token));
	$req = $req->fetch();

	if(!isset($req['id'])){
		$valid = false;
		$err_mess = "Le lien est erroné";
	}else{
		$DB->insert("UPDATE utilisateurs SET token = NULL, confirmation_token = ? WHERE id = ?",
	  	array(date('Y-m-d H:i:s'), $req['id']));

	  $info_mess = "Votre compte a bien été validé";
	}
}
?>