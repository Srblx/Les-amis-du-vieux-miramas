<header>
	<!-- Barre de navigation -->
	<nav class="navbar navbar-expand-lg navbar-light bg-light">
		<a class="navbar-brand" href="?controller=home&action=home">
			<img src="Content/img/Logo_Amis_vieux_Miramas.jpg" alt="Logo" class="logo" id="logo_asso"/>
			<!-- <span class="navbar-title">Les amis du vieux Miramas</span> -->
		</a>
		<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
			aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
			<span class="navbar-toggler-icon" id="menu_hamburger"></span>
		</button>
		<div class="collapse navbar-collapse" id="navbarNav">
			<ul class="navbar-nav ml-auto">
				<li class="nav-item">
					<a class="nav-link" href="?controller=presentation&action=presentation">Presentation</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="?controller=partenaires&action=partenaires">Partenaires</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="?controller=recherche&action=recherche">Document</a>
				</li>
				<?php
				// var_dump($_SESSION);
				if (isset($_SESSION['user']) && $_SESSION['user']['id_roles'] !== 4) { ?>
					<li class="nav-item">
						<a class="nav-link" href="?controller=ajout_document&action=ajout_document">Ajout de document</a>
					</li>
				<?php }
				?>
				<?php
				if (!isset($_SESSION['user']['id_roles'])) {

					?>
					<li class="nav-item">
						<a href="?controller=connexion&action=connexion" class="nav-link button_connexion">Connexion</a>
					</li>
				<?php }
				if (isset($_SESSION['user']['id_roles'])) {

					?>
					<li class="nav-item">
						<a class="nav-link" href="?controller=connexion&action=deconnexion" id="deco" class="button_deconnexion"
							onclick="return confirm('Êtes-vous sûr de vouloir vous déconnecter ?');">Déconnexion</a>
					</li>
				<?php } ?>

			</ul>
		</div>
	</nav>
</header>
<main>
<form action="?controller=utilisateur&action=all_utilisateur_prenom_list" method="POST" class="form_crud">
    <fieldset>
        <legend>Recherche par Prénom utilisateur</legend>
        <select name="prenom_utilisateur" id="prenom_utilisateur">
            <?php foreach ($utilisateur_prenom as $up) : ?>
                <option value="<?= $up->prenom ?>"><?= $up->prenom ?></option>
            <?php endforeach ?>
        </select>
        <input type="submit" value="Rechercher" name="utilisateur_prenom">
    </fieldset>
</form>

<?php if ($position !== 1) : ?>
    <table class='table'>
        <thead>
            <tr>
                <th>Nom</th>
                <th>Prénom</th>
                <th>E-mail</th>
                <th>Roles <sup>*</sup></th>
                <th></th>
                <th></th>
            </tr>
        </thead>
        <tbody>
<?php foreach ($utilisateur_prenom_list as $upl) : ?>
        <tr>
            <td class="td"> <?= $upl->nom ?> </td>
            <td class="td"> <?= $upl->prenom ?> </td>
            <td class="td"> <?= $upl->mail ?> </td>
            <td class="td"> <?= $upl->id_roles ?> </td>
            <td class="td"><a href="?controller=utilisateur&action=update_utilisateur&id=<?= $upl->id ?>"><i class="fa-solid fa-pen"></i></a></td>
            <td class="trash td ">
            <a href="?controller=utilisateur&action=delete_utilisateur&id=<?= $upl->id ?>" style="color: red;" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?')">
            <i class="fa fa-trash"></i>
            </a>
            </tr>
            <?php endforeach; ?> 
    </tbody>
</table>
<p class="information_boolean">Roles utilisateurs : 1 => Administrateur | 2 => Annonceur | 3 => Abonné | 4 => Visiteur qui a envoyé un message</p>
<?php endif; ?>
</main>