<div id="wpadminbar" class="noprint">
	<div class="quicklinks">
		<ul>
		
		<?php
			session_start();
		
			if(!empty($_SESSION['uid'])) {
		?>
			<li id="wp-admin-bar-my-account-with-avatar" class="menupop"><a href="index.php"><span>
				<img src="./design/images/buddyicon_16.jpg" width="16" height="16" alt="Utilisateur" />
				<?php
						echo $_SESSION['name'];
				?>
				</span></a>
				<ul>
					<li><a href="./prestation_add.php"><img src='./design/images/icon_new/16x16/application_view_list.png' />&nbsp;Ajout prestation spécifique</a></li>
					<li><a href="./prestation_multiadd.php"><img src='./design/images/icon_new/16x16/check_box.png' />&nbsp;Ajout prestations multiples</a></li>
					<li><a href="./prestation_listing.php"><img src='./design/images/icon_new/16x16/attributes_display.png' />&nbsp;Liste de mes prestations</a></li>
					<hr>
					<li><a href="./user_detail.php"><img src='./design/images/icon_new/16x16/user.png' />&nbsp;Mon Profil</a></li>
					<li><a href="./user_update.php"><img src='./design/images/icon_new/16x16/vcard_edit.png' />&nbsp;Modifier mon profil</a></li>
					<li><a href="./user_mdpupdt.php"><img src='./design/images/icon_new/16x16/asterisk_orange.png' />&nbsp;Changer mon mot de passe</a></li>
					<hr>
					<li id="wp-admin-bar-se-deconnecter"><a href="dcnx.php"><img src='./design/images/icon_new/16x16/door_in.png' />&nbsp;Se déconnecter</a></li>
				</ul>
			</li>
			<li id="wp-admin-bar-catalogs" class="menupop"><a href="index.php"><span>Accueil</span></a>
				<ul>
					<!-- <li><a href="./todo.php"><img src='./design/images/icon_new/16x16/bell_error.png' />&nbsp;Versions & Mise à jour</a></li> -->
					<?php
						if($_SESSION['status_out'] >= 4) {
					?>
					<li><a href="./news_add.php"><img src='./design/images/icon_new/16x16/comments_add.png' />&nbsp;Ajouter une news</a></li>
					<?php
						}
					?>
					<li><a href="http://www.lavaillantetubize.be/" target="_blank"><img src='./design/images/icon_new/16x16/world_go.png' />&nbsp;Site Internet</a></li>
				</ul>
			</li>
			<li id="wp-admin-bar-catalogs" class="menupop"><a href="#"><span>Gestion</span></a>
				<ul>
					<?php
						if($_SESSION['status_out'] >= 4) {
					?>
					<li><a href="#">Ajout</a>
						<ul>
							<li><a href="./discipline_add.php"><img src='./design/images/icon_new/16x16/application_add.png' />&nbsp;Ajouter une discipline</a></li>
							<li><a href="./subdiscipline_add.php"><img src='./design/images/icon_new/16x16/application_double_add.png' />&nbsp;Ajouter une sous-discipline</a></li>
							<li><a href="./course_add.php"><img src='./design/images/icon_new/16x16/application_cascade_add.png' />&nbsp;Ajouter un cours</a></li>
						</ul>
					</li>
					<?php
						}
					?>
					<li><a href="#">Listing</a>
						<ul>
							<li><a href="./discipline_listing.php"><img src='./design/images/icon_new/16x16/application.png' />&nbsp;Liste des disciplines</a></li>
							<li><a href="./subdiscipline_listing.php"><img src='./design/images/icon_new/16x16/application_double.png' />&nbsp;Liste des sous-disciplines</a></li>
							<li><a href="./course_listing.php"><img src='./design/images/icon_new/16x16/application_cascade.png' />&nbsp;Liste des cours</a></li>
						</ul>
					</li>
					<li><a href="#">Horaires</a>
						<ul>
							<li><a href="./schedule_course.php"><img src='./design/images/icon_new/16x16/columnchart.png' />&nbsp;Horaire des cours</a></li>
							<li><a href="./schedule_trainer.php">Horaire des Entraineurs</a></li>
						</ul>
					</li>
					<?php
						if($_SESSION['status_out'] >= 4) {
					?>
					<li><a href="#">Trésorerie</a>
						<ul>
							<li><a href="./fee.php"><img src='./design/images/icon_new/16x16/calculator_edit.png' />&nbsp;Calcul des Côtisations</a></li>
							<li><a href="./prestation_listing.php"><img src='./design/images/icon_new/16x16/attributes_display.png' />&nbsp;Liste des prestations</a></li>
							<li><a href="./prestation_stat.php"><img src='./design/images/icon_new/16x16/application_view_list.png' />&nbsp;Résumé des prestations</a></li>
						</ul>
					</li>
					<?php
						}
						
						if($_SESSION['status_out'] >= 3)
							echo "<li><a href='preins_listing.php'><img src='./design/images/icon_new/16x16/application_view_detail.png' />&nbsp;Liste des Pré-Inscriptions</a>";
					
						if($_SESSION['status_out'] >= 8) {
					?>
					<hr>
					<li><a href="#">Extranet</a>
						<ul>
							<li><a href="./maintenance.php"><img src='./design/images/icon_new/16x16/database_gear.png' />&nbsp;Maintenance database</a></li>
							<li><hr /></li>
							<li><a href="./configuration.php"><img src='./design/images/icon_new/16x16/cog_edit.png' />&nbsp;Configuration</a></li>
							<li><a href="./holiday_add.php"><img src='./design/images/icon_new/16x16/calendar_add.png' />&nbsp;Ajouter une Indisponibilité</a></li>
							<li><a href="./season_add.php">Ajouter une Saison</a></li>
							<li><hr /></li>
							<li><a href="./right_add.php"><img src='./design/images/icon_new/16x16/group_add.png' />&nbsp;Ajouter un Droit</a></li>
							<li><a href="./right_listing.php"><img src='./design/images/icon_new/16x16/group.png' />&nbsp;Listing des Droits</a></li>
							<li><a href="./right_summary.php"><img src='./design/images/icon_new/16x16/group_gear.png' />&nbsp;Résumé des Droits</a></li>
						</ul>
					</li>
					<?php
						}
					?>
					<hr>
					<li><a href="statistics.php"><img src='./design/images/icon_new/16x16/statistics.png' />&nbsp;Statistiques</a></li><!-- statistics.png -->
				</ul>
            </li>
            <li id="wp-admin-bar-catalogs" class="menupop"><a href="#"><span>Evènements</span></a>
				<ul>
					<li><a href="#">Listing</a>
						<ul>
							<li><a href="./place_listing.php"><img src='./design/images/icon_new/16x16/map_magnify.png' />&nbsp;Liste des lieux</a></li>
							<li><a href="./event_listing.php"><img src='./design/images/icon_new/16x16/date.png' />&nbsp;Liste des évènements</a></li>
							<li><a href="./product_listing.php"><img src='./design/images/icon_new/16x16/folder.png' />&nbsp;Liste du matériel</a></li>
							<li><a href="./holiday_listing.php"><img src='./design/images/icon_new/16x16/calendar.png' />&nbsp;Liste des Indisponibilités</a></li>
						</ul>
					</li>
					<?php
						if($_SESSION['status_out'] >= 4) {
					?>
					<li><a href="#">Ajout</a>
						<ul>
							<li><a href="./place_add.php"><img src='./design/images/icon_new/16x16/map_add.png' />&nbsp;Ajouter un lieu</a></li>
							<li><a href="./product_add.php"><img src='./design/images/icon_new/16x16/folder_add.png' />&nbsp;Ajouter du matériel</a></li>
							<li><a href="./event_add.php"><img src='./design/images/icon_new/16x16/date_add.png' />&nbsp;Ajouter un évènement</a></li>
						</ul>
					</li>
					<?php
						}
					?>
					<li><a href="annual_calendar.php"><img src='./design/images/icon_new/16x16/calendar_view_month.png' />&nbsp;Calendrier Annuel</a></li>
				</ul>
            </li>
			<li id="wp-admin-bar-catalogs" class="menupop"><a href="#"><span>Personnes</span></a>
				<ul>
					<?php
						if($_SESSION['status_out'] >= 3) {
					?>	
					<li><a href="#">Ajout</a>
						<ul>
							<?php
								if($_SESSION['status_out'] == 9) {
							?>
							<li><a href="./user_add.php"><img src='./design/images/icons/16_user_add.png' />&nbsp;Ajouter un Utilisateur</a></li>
							<?php
								}
							?>		
							<li><a href="./person_add.php">Ajouter une Personne</a></li>
							<!-- <li><a href="./inscrit_add.php"><img src='./design/images/icons/16_gymnast_add.png' />&nbsp;Ajouter un Gymnaste</a></li> -->
							<!-- <li><a href="./relationship_add.php">Ajouter une Relation</a></li> -->
						</ul>
					</li>
					<?php
						}
					?>
					<li><a href="#">Listing</a>
						<ul>
							<li><a href="./user_listing.php" title="Liste des utilisateurs"><img src='./design/images/icons/16_search_user.png' />&nbsp;Liste des Utilisateurs</a></li>
							<li><a href="./person_listing.php">Liste des Personnes</a></li>
							<li><a href="./affiliate_listing.php">Liste des Gymnastes</a></li>
							<!-- <li><a href="./relationship_listing.php">Liste des Relations</a></li> -->
						</ul>
					</li>
					<li><a href="./addressbook.php" title="Carnet d'adresse"><img src='./design/images/icon_new/16x16/book_addresses.png' />&nbsp;Carnet d'adresse</a></li>
					<?php
						if($_SESSION['status_in'] >= 1) {
					?>
					<li><hr></li>
					<li><a href="./contact.php" title="Contacter..."><img src='./design/images/icon_new/16x16/email_go.png' />&nbsp;Contacter...</a></li>
					<?php
						}
					?>
				</ul>
			</li>
			<li id="wp-admin-bar-catalogs" class="menupop"><a href="#"><span>Bibliothèque</span></a>
				<ul>
					<li><a href="./document_add.php"><img src='./design/images/icon_new/16x16/file_extension_wps.png' />&nbsp;Ajouter un document</a></li>
					<li><a href="./document_listing.php" title="Liste des documents"><img src='./design/images/icon_new/16x16/file_extension_doc.png' />&nbsp;Liste des Documents</a></li>
					<li><a href="./downdoc_listing.php" title="Documents"><img src='./design/images/icon_new/16x16/download.png' />&nbsp;Documents à Télécharger</a></li> <!-- file_extension_lnk.png -->
				</ul>
			</li>
			<li id="wp-admin-bar-catalogs" class="menupop"><a href="./search.php"><span>Recherche</span></a>
			</li>
			<?php
				}
			?>
		</ul>
	</div>
</div>

<div id="header"> 
	<div class="container">
		<div align="right">
		</div>
		<div id="header_image"></div>
	</div>
</div>

<?php

// var_dump($_SESSION);

?>