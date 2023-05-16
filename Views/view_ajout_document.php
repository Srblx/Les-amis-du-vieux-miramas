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
<section id="section-ajout-document">
        <h2 id="titre-ajout-document">Ajouter des ressources</h2>
        <div id="flex-ajout">
            <div id="division-ajout-document">
                <div class="checkbox-wrapper">
                    <button id="popup-toggle-button">Ajouter une catégorie</button>
                    <!-- POPUP -->
                    <div class="popup-wrapper">
                        <div class="popup-content">
                            <button class="close-button">&times;</button>
                            <form id="popup-form" action="?controller=ajout_document&action=ajout_categorie"
                                method="POST">
                                <label for="popup-input">Saisissez votre nom :</label>
                                <input type="text" id="popup-input" name="input_ajout_categorie">
                                <span id="spanpop"></span>
                                <input type="submit" value="Ajouter">
                            </form>
                        </div>
                    </div>
                    <form action="?controller=ajout_document&action=ajout_document_bdd" method="POST"
                        enctype="multipart/form-data" id="ajout_doc_form">
                        <label for="titre_document">Titre du document : <sup>*</sup></label>
                        <span id="span_titre" class="texte-important"></span>
                        <input type="text" name="titre_document" class="titre_document">
                        <label for="description_document">Description du document : <sup>*</sup></label>
                        <span id="span_description" class="texte-important"></span>
                        <input type="text" name="description_document" class="description_document">
                        <label for="input-file-ajout-document">Ajouter un fichier : <sup>*</sup> <i
                                class="fa-regular fa-circle-question"
                                title="Votre fichier ne doit pas depasser les 5Mo et etre au format suivant : .png, .jpg, .jpeg, .mp3, .mp4."></i></label>
                        <input type="file" id="input-file-ajout-document" name="input-file-ajout-document">
                        <label for="date_document">Date de publication : <sup>*</sup></label>
                        <input type="date" name="date_document" class="date_document">
                        <label for="select_categorie">Catégorie : <sup>*</sup></label>
                        <select name="select_categorie" id="select_categorie">
                            <?php foreach ($select_document as $sc): ?>
                                <option value="<?= $sc->id ?>"><?= $sc->libelle ?></option>
                            <?php endforeach ?>
                        </select>
                        <label for="ajout_utilisateur_document"></label>
                        <input type="hidden" value="<?= $_SESSION['user']['id'] ?>" name="ajout_utilisateur_document"
                            id="ajout_utilisateur_document" readonly>
                        <div class="ajout_annuler">
                            <input type="reset" class="input-submit-supprimer" id="input-submit-supprimer"
                                value="Reset">
                            <input type="submit" name="submit" id="input-submit-ajout-document" value="Ajouter">
                        </div>
                    </form>
                </div>
                <sub class="info_form">* : Champs Obligatoires</sub>
            </div>

        </div>
    </section>
<script>
      const ajout_doc_form = document.querySelector('#ajout_doc_form');

ajout_doc_form.addEventListener('submit', (event) => {
    event.preventDefault();

    const titre_document = document.querySelector('.titre_document');
    const description_document = document.querySelector('.description_document');
    const input_file_ajout_document = document.querySelector('#input-file-ajout-document');

    let isFormValid = true;

    if (titre_document.value.trim() === '') {
        isFormValid = false;
        document.querySelector('#span_titre').textContent = 'Le titre est obligatoire.';
    } else {
        document.querySelector('#span_titre').textContent = '';
    }

    if (description_document.value.trim() === '') {
        isFormValid = false;
        document.querySelector('#span_description').textContent = 'La description est obligatoire.';
    } else {
        document.querySelector('#span_description').textContent = '';
    }

    if (input_file_ajout_document.value.trim() === '') {
        isFormValid = false;
        document.querySelector('#input-file-ajout-document').value = '';
        document.querySelector('#input-file-ajout-document').setCustomValidity('Le fichier est obligatoire.');
    } else {
        const allowedExtensions = /(\.png|\.jpg|\.jpeg|\.mp3|\.mp4)$/i;
        if (!allowedExtensions.exec(input_file_ajout_document.value)) {
            isFormValid = false;
            document.querySelector('#input-file-ajout-document').value = '';
            document.querySelector('#input-file-ajout-document').setCustomValidity('Le fichier doit être au format : .png, .jpg, .jpeg, .mp3, .mp4.');
        } else {
            document.querySelector('#input-file-ajout-document').setCustomValidity('');
        }
    }

    if (isFormValid) {
        ajout_doc_form.submit();
    }
});
</script>