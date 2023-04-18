<form action="?controller=utilisateur&action=update_utilisateur" method="post" id="addForm">
    <fieldset>
        <legend id="legend"><b>Modifier les informations d'un utilisateur</b></legend>
        <input type="hidden" name="id" value="<?= $utilisateur['id'] ?>">
        <label for="nom">Nom :</label>
        <input type="text" name="nom" id="nom" value="<?= valid_input($utilisateur['nom']) ?>">
        <label for="prenom">Prénom :</label>
        <input type="text" name="prenom" id="prenom" value="<?= valid_input($utilisateur['prenom']) ?>">
        <label for="mail">Mail :</label>
        <input type="text" name="mail" id="mail" value="<?= valid_input($utilisateur['mail']) ?>">
        <label for="id_roles">Roles Abonnée : </label>
        <input type="text" name="id_roles" id="id_roles" value="<?= valid_input($utilisateur['id_roles']) ?>">
        <input type="submit" value="Modifier" name="submit" id="submit">
        <sup class="information_boolean">Roles utilisateurs : 1 => Administrateur | 2 => Annonceur | 3 => Abonné</sup>
    </fieldset>
</form>
<?php var_dump($utilisateur) ?>


<?php function valid_input($data)
    {
        //todo Supprime les espaces en début et fin de chaîne
        $data = trim($data);
        //todo Supprime les barres obliques inverses de la chaîne
        $data = stripslashes($data);
        //todo Supprime les balises et les caractères spéciaux
        $data = filter_var($data, FILTER_SANITIZE_STRING);
        //todo Convertit les caractères spéciaux en entités HTML
        $data = filter_var($data, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        //todo Encode les caractères spéciaux en UTF-8
        $data = filter_var($data, FILTER_SANITIZE_ENCODED);
        //todo Retourne la chaîne de caractères validée
        return $data;
    } ?>