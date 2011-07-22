<?php
require_once "include/common.php";
if(!isset($_SESSION['connected']))
	header('Location: index.php');
$r= false;
if(!empty($_POST['bname']) && !empty($_POST['bmac'])) {
    $r = $ojnAPI->getApiString('accounts/addBunny?login='.urlencode($_SESSION['login']).'&bunnyid='.preg_replace("|:|", "", strtolower($_POST['bmac'])).'&'.$ojnAPI->getToken());
    if(isset($r['ok'])) {
        $_SESSION['message'] = $r['ok'];
        $r = $ojnAPI->getApiString("bunny/".preg_replace("|:|", "", strtolower($_POST['bmac']))."/setBunnyName?name=".urlencode($_POST['bname'])."&".$ojnAPI->getToken());
        $_SESSION['message'] .= '<br />'. (isset($r['ok']) ? $r['ok'] : "Error : ".$r['error']);
    } else
        $_SESSION['message'] = "Error : ".$r['error'];
}

if(!empty($_POST['bmac_rm'])) {
    $r = $ojnAPI->getApiString('accounts/removeBunny?login='.urlencode($_SESSION['login']).'&bunnyid='.preg_replace("|:|", "", strtolower($_POST['bmac_rm'])).'&'.$ojnAPI->getToken());
    $_SESSION['message'] = (isset($r['ok']) ? $r['ok'] : "Error : ".$r['error']);
}

if(!empty($_POST['zid_rm'])) {
    $r = $ojnAPI->getApiString('accounts/removeZtamp?login='.urlencode($_SESSION['login']).'&zid='.$_POST['zid_rm'].'&'.$ojnAPI->getToken());
    $_SESSION['message'] = (isset($r['ok']) ? $r['ok'] : "Error : ".$r['error']);
}

if(!empty($_POST['npwd']) && !empty($_POST['npwd2'])) {
    $r = 1;
    if($_POST['npwd'] == $_POST['npwd2']) {
         $r = $ojnAPI->getApiString('accounts/changePassword?pass='.$_POST['npwd'].'&'.$ojnAPI->getToken());
         if(isset($r['value']))
            $_SESSION['message'] = (strstr($r['value'],'changed') ? '' : 'Error : ').$r['value'];
         else
            $_SESSION['message'] = 'Error : API error.  Should be fixed soon...';
    } else
        $_SESSION['message'] = "Passwords mismatch. Try again ;)";
}
if(!empty($r))
    header('Location: account.php');
?>
<?php
if(isset($_SESSION['message']) && empty($r)) {
	if(strstr($_SESSION['message'],'Error : ')) {?>
	<div class="error_msg">
	<?php } else { ?>
	<div class="ok_msg">
	<?php }
	echo $_SESSION['message']; ?>
	</div>
	<?php unset($_SESSION['message']);
}
?>
<fieldset>
<legend>Add a bunny to your account</legend>
<em>Will only work if the server allows it</em><br />
<form method="post">
<label>Name of your Bunny: <input type="text" name="bname" /></label><br />
<label>MAC Address: <input type="text" name="bmac" /></label><br />
<input type="submit" value="Add" />
</form>
</fieldset>
<fieldset>
<legend>Remove a bunny from your account</legend>
<em>No confirmation, so... be careful!</em><br />
<form method="post">
<select name="bmac_rm">
    <?php
    $bunnies = $ojnAPI->getListOfBunnies(true);
    if(!empty($bunnies))
        foreach($bunnies as $mac => $bunny) { ?>
        <option value="<?php echo $mac; ?>"><?php echo $bunny; ?> (<?php echo $mac; ?>)</option>
    <?php } ?>
</select>
<input type="submit" value="Remove" />
</form>
</fieldset>
<fieldset>
<legend>Remove a ztamp from your account</legend>
<em>No confirmation, so... be careful!</em><br />
<form method="post">
<select name="zid_rm">
    <?php
    $ztamps = $ojnAPI->getListOfZtamps(true);
    if(!empty($ztamps))
        foreach($ztamps as $id => $ztamp) { ?>
        <option value="<?php echo $id; ?>"><?php echo $ztamp; ?> (<?php echo $id; ?>)</option>
    <?php } ?>
</select>
<input type="submit" value="Remove" />
</form>
</fieldset>
<fieldset>
<legend>Change your password</legend>
<form method="post">
<label>New password <input type="password" name="npwd" /></label><br />
<label>Confirm <input type="password" name="npwd2" /></label><br />
<input type="submit" value="Apply" />
</form>
</fieldset>
<?php
require_once "include/append.php";
?>
