<?php
	require_once("./CONFIG/config.php");
	$lcsid = $_GET['lcsid'];
	// $courseid = $_GET['courseid'];
	// $seasonid = $_GET['seasonid'];

	$query = "SELECT xtr_istrainer.istrainerid, xtr_users.userid, CONCAT(lastname, ', ', firstname) AS name FROM xtr_istrainer, xtr_users, xtr_person WHERE  xtr_istrainer.userid = xtr_users.userid AND xtr_users.personid = xtr_person.personid AND xtr_istrainer.lcsid = $lcsid ORDER BY name";
	echo $query."<br />";
	$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : SELECT FAILED (trainer, user, person) !<br />$query<br />$result<br />".mysql_error(), E_USER_ERROR);
	$line = mysql_fetch_array($result)
?>
<p><hr></p>
<p>
	<table width="100%">
		<tr>
			<td height="15" width="37%">Entraineur(s)</td>
			<td><?php echo "<a href='trainer_del.php?itid=".$line['istrainerid']."' title=\"Supprimer l'entrainer\" class='noprint'><img src='./design/images/icons/16_delete.png' height='10' width='10' /></a></td><td><a href='user_detail.php?uid=".$line['userid']."'>".$line['name']."</a></td></tr>"; ?></td>
		</tr>
		<?php
			while($line = mysql_fetch_array($result))
				echo "<tr><td>&nbsp;</td><td><a href='trainer_del.php?itid=".$line['istrainerid']."' title=\"Supprimer l'entrainer\" class='noprint'><img src='./design/images/icons/16_delete.png' height='10' width='10' /></a></td><td><a href='user_detail.php?uid=".$line['userid']."'>".$line['name']."</a></td></tr>";
		?>
	</table>
</p>
<p align="right" class="noprint">
	<a href="trainer_add.php?id=<?php echo $courseid; ?>"><img src="./design/images/icons/16_user_add.png" alt="Ajouter un Entrainer" /></a>
</p>
<p><hr></p>
<p>
	<?php
		$query = "SELECT xtr_isaffiliate.personid, CONCAT(lastname, ', ', firstname) AS name, phone, gsm FROM xtr_isaffiliate, xtr_person WHERE xtr_isaffiliate.personid = xtr_person.personid AND xtr_isaffiliate.lcsid = $lcsid ORDER BY name";
		echo $query."<br />";
		$result = mysql_query($query,$connect) or trigger_error("SQL ERROR : SELECT FAILED (trainer, user, person) !<br />".$result."<br />".mysql_error(), E_USER_ERROR);
		$line = mysql_fetch_array($result)
	?>
	<table width="100%">
		<tr>
			<td height="15" width="37%">Elève(s)</td>
			<td width="20">
				<?php
					echo "<a href='isaffiliate_del.php?personid=".$line['personid']."&courseid=$courseid&season=$seasonid' title=\"Supprimer l'élève\" class='noprint'><img src='./design/images/icons/16_delete.png' height='10' width='10' /></a></td><td><a href='affiliate_details.php?id=".$line['personid']."'>".$line['name']."</a></td><td>";
					if($line['gsm'] != "")
						echo substr($line['gsm'], 0, 4)."/".substr($line['gsm'], 4, 2).".".substr($line['gsm'], 6, 2).".".substr($line['gsm'], 8, 2)."</td>";
					else
						if($line['phone'] != "") {
							if($line['phone'][1] == 2)
								echo substr($line['phone'], 0, 2)."/".substr($line['phone'], 2, 3);
							else
								echo substr($line['phone'], 0, 3)."/".substr($line['phone'], 3, 2);
							echo ".".substr($line['phone'], 5, 2).".".substr($line['phone'], 7, 2)."</td>";
						} else
							echo "<td>&nbsp;</td>";
				?>
			</td>
		</tr>
		<?php
			while($line = mysql_fetch_array($result)) {
				echo "<tr><td>&nbsp;</td><td><a href='isaffiliate_del.php?personid=".$line['personid']."&courseid=$courseid&season=$seasonid' title=\"Supprimer l'élève\" class='noprint'><img src='./design/images/icons/16_delete.png' height='10' width='10' /></a></td><td><a href='affiliate_details.php?id=".$line['personid']."'>".$line['name']."</a></td><td>";
					if($line['gsm'] != "")
						echo substr($line['gsm'], 0, 4)."/".substr($line['gsm'], 4, 2).".".substr($line['gsm'], 6, 2).".".substr($line['gsm'], 8, 2)."</td>";
					elseif($line['phone'] != "") {
						if($line['phone'][1] == 2)
							echo substr($line['phone'], 0, 2)."/".substr($line['phone'], 2, 3);
						else
							echo substr($line['phone'], 0, 3)."/".substr($line['phone'], 3, 2);
						echo ".".substr($line['phone'], 5, 2).".".substr($line['phone'], 7, 2)."</td></tr>";
					} else
						echo "<td>&nbsp;</td>";
			}
		?>
	</table>
</p>
<p align="right" class="noprint">
	<a href="isaffiliate_add.php?courseid=<?php echo $courseid; ?>"><img src="./design/images/icons/16_gymnast_add.png" alt="Ajouter un Gymnaste" /></a>
</p>