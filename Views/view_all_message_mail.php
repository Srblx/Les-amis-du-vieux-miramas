<form action="?controller=message&action=all_message_mail_list" method="POST">
    <fieldset>
        <legend>Recherche d'un message par l'E-mail de l'utilisateur :</legend>
        <select name="nom_message" id="nom_message">
            <?php foreach ($mail_message as $m) : ?>
                <option value="<?= $m->mail ?>"><?= $m->mail ?></option>
                <?php endforeach ?>
            </select>
            <input type="submit" value="Rechercher" name="submit">
        </fieldset>
    </form>
    

    <?php     
if ($position !== 1) : 
    var_dump($mail_message_list);
    //!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!! 
    ?>
    <table class='table'>
        <thead>
            <tr>
                <th>nom</th>
                <th>prénom</th>
                <th>email</th>
                <th>Objet</th>
                <th>Message</th>
                <th>Date d'envoi</th>
                <th></th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($mail_message_list as $ml) : ?>
                <tr>
                    <td class="td"> <?= $ml->nom ?> </td>
                    <td class="td"> <?= $ml->prenom ?> </td>
                    <td class="td"> <?= $ml->mail ?> </td>
                    <td class="td"> <?= $ml->object ?> </td>
                    <td class="td"> <?= $ml->message ?> </td>
                    <td class="td"> <?= $ml->date_message ?></td>
                    <td><a href='?controller=livre&action=update_livre&id=<?= $ml->id ?>'><i class=" fa-solid fa-pen"></i></a></td>
                    <td class='trash'><a href='?controller=utilisateur&action=delete_utilisateur&id=<?= $ml->id ?>' style='color:red;' onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?')"><i class='fa fa-trash'></i></a></td>

                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php 
endif;
 ?>