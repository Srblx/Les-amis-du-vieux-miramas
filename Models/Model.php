<?php

class Model
{   //* Début de la Classe

    private $bd;

    private static $instance = null;

    /*
         * Constructeur créant l'objet PDO et l'affectant à $bd
         */
    private function __construct()
    {  //* Fonction qui sert à faire le lien avec la BDD

        $dsn = "mysql:host=localhost;dbname=les_amis_du_vieux_miramas";   //* Coordonnées de la BDD
        $login = "root";   //* Identifiant d'accès à la BDD
        $mdp = ""; //* Mot de passe d'accès à la BDD
        $this->bd = new PDO($dsn, $login, $mdp);
        $this->bd->query("SET NAMES 'utf8'");
        $this->bd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }


    //* get_model()

    public static function get_model()
    {
        //* Fonction qui sert à créer une instance de Model pour l'appeler dans chaque Controller (équivalent de $connex)
        if (is_null(self::$instance)) {
            self::$instance = new Model();
        }
        return self::$instance;
    }
    

    //^ Affichega des utilisateurs
    public function get_all_utilisateur()
    {
        // Préparer la requête SQL pour sélectionner tous les livres dans l'ordre alphabétique par prenom
        // ! requete a modifier
        $r = $this->bd->prepare("SELECT u.id, u.nom, u.prenom, u.mail, u.id_roles, r.admin, r.annonceur, r.abonnee FROM utilisateur u
        INNER JOIN roles r ON u.id_roles = r.id");

        // Exécuter la requête
        $r->execute();

        // Récupérer tous les résultats sous forme d'un tableau d'objets
        return $r->fetchAll(PDO::FETCH_OBJ);
    }

    public function get_all_utilisateur_nom()
    {
        // Préparer la requête SQL pour sélectionner tous les livres dans l'ordre alphabétique par prenom
        $r = $this->bd->prepare("SELECT distinct nom FROM utilisateur;");

        // Exécuter la requête
        $r->execute();

        // Récupérer tous les résultats sous forme d'un tableau d'objets
        return $r->fetchAll(PDO::FETCH_OBJ); 
    }

    public function get_all_utilisateur_nom_list()
    {
        if (isset($_POST['nom_utilisateur'])) {
            $nom = $this->valid_input($_POST['nom_utilisateur']);
        
            // Préparer la requête SQL pour sélectionner tous les utilisateurs ayant le même nom
            $r = $this->bd->prepare("SELECT u.id, u.nom, u.prenom, u.mail, r.admin, r.annonceur, r.abonnee FROM utilisateur u JOIN roles r ON u.id_roles = r.id WHERE nom = :nom;");
        
            $r->bindValue(':nom', $nom, PDO::PARAM_STR);
            // Exécuter la requête
            $r->execute();
        
            // Récupérer tous les résultats sous forme d'un tableau d'objets
            $resultats = $r->fetchAll(PDO::FETCH_OBJ);
            
            // Ajouter un message de débogage pour afficher les résultats de la requête SQL
            echo "Les résultats de la requête SQL sont les suivants : ";
            print_r($resultats);
            
            return $resultats;
        }
    }
    
    public function get_all_utilisateur_prenom()
    {
        // Préparer la requête SQL pour sélectionner tous les livres dans l'ordre alphabétique par prenom
        $r = $this->bd->prepare("SELECT distinct prenom FROM utilisateur;");

        // Exécuter la requête
        $r->execute();

        // Récupérer tous les résultats sous forme d'un tableau d'objets
        return $r->fetchAll(PDO::FETCH_OBJ); 
    }

    public function get_all_utilisateur_prenom_list()
    {
        $prenom = $this->valid_input($_POST['utilisateur_prenom']);
       
        // Préparer la requête SQL pour sélectionner tous les livres dans l'ordre alphabétique par prenom
        $r = $this->bd->prepare("SELECT u.id, u.nom, u.prenom, u.mail, r.admin, r.annonceur, r.abonnee FROM utilisateur u 
        JOIN roles r ON u.id_roles = r.id WHERE prenom = :prenom;");

        $r->bindValue(':prenom', $prenom, PDO::PARAM_STR);
        // Exécuter la requête
        $r->execute();

        // Récupérer tous les résultats sous forme d'un tableau d'objets
        return $r->fetchAll(PDO::FETCH_OBJ);
     
    }

    public function get_all_utilisateur_mail(){

        $r = $this->bd->prepare("SELECT DISTINCT mail FROM utilisateur");

        $r->execute();

        return $r->fetchAll(PDO::FETCH_OBJ);
    }

    public function get_all_utilisateur_mail_list()
    {
        $mail_utilisateur = $this->valid_input($_POST["mail_utilisateur"]); 

        $r = $this->bd->prepare("SELECT u.nom, u.prenom, u.mail, u.id_roles FROM utilisateur u  WHERE u.mail = :mail;");
        $r->bindValue(':mail', $mail_utilisateur, PDO::PARAM_STR);

        $r->execute();

        return $r->fetchAll(PDO::FETCH_OBJ);
    }

    public function get_update_utilisateur($id)
    {
        // $id = $_GET['id'];
        $r = $this->bd->prepare("SELECT * FROM utilisateur WHERE id = $id");
        
        $r->execute();
        
        return $r->fetch();
    }

    public function get_update_utilisateur_bdd()
    {
        // Récupérer les données du formulaire
        $id = $this->valid_input($_POST['id']);
        $nom = $this->valid_input($_POST['nom']);
        $prenom = $this->valid_input($_POST['prenom']);
        $mail = $this->valid_input($_POST['mail']);
        $id_roles = $this->valid_input($_POST['id_roles']);
        

        // UPDATE utilisateur u 
        //     INNER JOIN roles r ON u.id_roles = r.id 
        //     SET u.nom = :nom, u.prenom = :prenom, u.mail = :mail, r.abonnee = :abonnee, r.annonceur = :annonceur, r.admin = :admin 
        //     WHERE u.id = :id;
        // Mettre à jour les données dans la base de données
        $r = $this->bd->prepare("UPDATE utilisateur  
        SET nom = :nom, prenom = :prenom, mail = :mail, id_roles = :id_roles 
        WHERE id = :id;");
        $r->bindParam(':nom', $nom);
        $r->bindParam(':prenom', $prenom);
        $r->bindParam(':mail', $mail);
        $r->bindParam(':id_roles', $id_roles);
        $r->bindParam(':id', $id);
        $r->execute();
    }

    //^ Affichage des annonces
    public function get_all_annonce()
    {
        $r = $this->bd->prepare("SELECT a.texte, a.image, a.logo, u.nom, u.prenom, a.id FROM annonce a
        INNER JOIN utilisateur u ON u.id = a.id_utilisateur");

        // Exécuter la requête
        $r->execute();

        // Récupérer tous les résultats sous forme d'un tableau d'objets
        return $r->fetchAll(PDO::FETCH_OBJ);
    }


    //^ Affichage des messages 
    public function get_all_message()
    {
        $r = $this->bd->prepare("SELECT u.nom, u.prenom, u.mail, m.object, m.message, m.date_message, m.id FROM message m 
        INNER JOIN utilisateur u ON u.id = m.id_utilisateur");

        // Exécuter la requête
        $r->execute();

        // Récupérer tous les résultats sous forme d'un tableau d'objets
        return $r->fetchAll(PDO::FETCH_OBJ);
    }

    public function get_all_nom_message()
    {
        $r = $this->bd->prepare("SELECT DISTINCT nom FROM utilisateur");

        $r->execute();

        return $r->fetchAll(PDO::FETCH_OBJ);
    }

    public function get_all_nom_message_list($nom_message)
    {
        $nom_message = $this->valid_input($_POST["nom_message"]);
        $r = $this->bd->prepare("SELECT u.nom, u.prenom, u.mail, m.object, m.message, m.date_message FROM utilisateur u 
        INNER JOIN message m ON u.id = m.id_utilisateur WHERE u.nom = :nom;");
        $r->bindValue(':nom', $nom_message, PDO::PARAM_STR);

        $r->execute();

        return $r->fetchAll(PDO::FETCH_OBJ);
    }

    public function get_all_message_mail()
    {
        // Préparer la requête SQL pour sélectionner tous les livres dans l'ordre alphabétique par prenom
        $r = $this->bd->prepare("SELECT distinct mail FROM utilisateur;");

        // Exécuter la requête
        $r->execute();

        // Récupérer tous les résultats sous forme d'un tableau d'objets
        return $r->fetchAll(PDO::FETCH_OBJ); 
    }

    public function get_all_message_mail_list()
    {
        // if (isset($_POST['mail'])) {
            $mail = $this->valid_input($_POST['mail_message']);

            // Préparer la requête SQL pour sélectionner tous les utilisateurs ayant le même nom
            $r = $this->bd->prepare("SELECT u.nom, u.prenom, u.mail, m.object, m.message, m.date_message FROM utilisateur u 
            JOIN message m ON u.id = m.id_utilisateur WHERE mail = :mail;");

            $r->bindValue(':mail', $mail, PDO::PARAM_STR);
            // Exécuter la requête
            $r->execute();

            // Récupérer tous les résultats sous forme d'un tableau d'objets
            return $r->fetchAll(PDO::FETCH_OBJ);
        // }
    }

    public function get_all_message_objet()
    {
        // Préparer la requête SQL pour sélectionner tous les livres dans l'ordre alphabétique par prenom
        $r = $this->bd->prepare("SELECT distinct object FROM message;");

        // Exécuter la requête
        $r->execute();

        // Récupérer tous les résultats sous forme d'un tableau d'objets
        return $r->fetchAll(PDO::FETCH_OBJ); 
    }

    public function get_all_message_objet_list()
    {
        if (isset($_POST['objet'])) {
            $objet = $this->valid_input(($_POST['objet']));

            // Préparer la requête SQL pour sélectionner tous les utilisateurs ayant le même nom
            $r = $this->bd->prepare("SELECT u.nom, u.prenom, u.mail, m.object, m.message, m.date_message FROM utilisateur u 
            JOIN message m ON message m ON u.id = m.id_utilisateur  WHERE object = :objet;");

            $r->bindValue(':objet', $objet, PDO::PARAM_STR);
            // Exécuter la requête
            $r->execute();

            // Récupérer tous les résultats sous forme d'un tableau d'objets
            return $r->fetchAll(PDO::FETCH_OBJ);
        }
    }
    
    //^ Affichage des documents
    public function get_all_document()
    {
        $r = $this->bd->prepare("SELECT d.titre, d.format, d.description, u.nom, u.prenom, c.libelle, c.affichage, d.id FROM document d
        INNER JOIN utilisateur u ON u.id = d.id_utilisateur
        INNER JOIN categorie c ON c.id = d.id_categorie;");

        $r->execute();

        return $r->fetchAll(PDO::FETCH_OBJ);
    }

    public function get_all_titre_document()
    {

        // Préparer la requête SQL pour sélectionner tous les livres dans l'ordre alphabétique par titre
        $r = $this->bd->prepare("SELECT DISTINCT titre FROM document");

        // Exécuter la requête
        $r->execute();

        // Récupérer tous les résultats sous forme d'un tableau d'objets
        return $r->fetchAll(PDO::FETCH_OBJ);
    }

    public function get_all_titre_document_list($titre_document)
    {
        // Récupérer la valeur de localite choisie par l'utilisateur depuis le formulaire
        // !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
        $titre = $this->valid_input($_POST['titre_document']);

        // Préparer la requête SQL en utilisant une variable liée pour éviter les attaques par injection SQL
        $r = $this->bd->prepare("SELECT u.nom, u.prenom, d.titre, d.format, d.description, c.libelle, c.affichage FROM document d  
        INNER JOIN utilisateur u ON u.id = d.id_utilisateur 
        INNER JOIN categorie c ON c.id = d.id_categorie WHERE titre = :titre");
        $r->bindValue(':titre', $titre, PDO::PARAM_STR);

        // Exécuter la requête
        $r->execute();

        // Récupérer tous les résultats sous forme d'un tableau d'objets
        return $r->fetchAll(PDO::FETCH_OBJ);
    }

    public function get_all_format_document()
    {
        $r = $this->bd->prepare("SELECT DISTINCT format FROM document");

        $r->execute();

        return $r->fetchAll(PDO::FETCH_OBJ);
    }

    public function get_all_format_document_list($format_document)
    {
        $format_document = $this->valid_input($_POST["format_document"]);
        $r = $this->bd->prepare("SELECT u.nom, u.prenom, d.titre, d.format, d.description, d.date_publication, c.libelle, c.affichage FROM utilisateur u 
        INNER JOIN document d ON u.id = d.id_utilisateur 
        INNER JOIN categorie c ON c.id = d.id_categorie WHERE format = :format;");
        $r->bindValue(':format', $format_document, PDO::PARAM_STR);

        $r->execute();

        return $r->fetchAll(PDO::FETCH_OBJ);
    }

    public function get_all_utilisateur_document()
    {
        $r = $this->bd->prepare("SELECT DISTINCT nom FROM utilisateur");

        $r->execute();

        return $r->fetchAll(PDO::FETCH_OBJ);
    }

    public function get_all_utilisateur_document_list($utilisateur_document)
    {
        $utilisateur_document = $this->valid_input($_POST["utilisateur_document"]);
        $r = $this->bd->prepare("SELECT u.nom, u.prenom, d.titre, d.format, d.description, d.date_publication, c.libelle, c.affichage FROM utilisateur u 
        INNER JOIN document d ON u.id = d.id_utilisateur 
        INNER JOIN categorie c ON c.id = d.id_categorie WHERE nom = :utilisateur;");
        $r->bindValue(':utilisateur', $utilisateur_document, PDO::PARAM_STR);

        $r->execute();

        return $r->fetchAll(PDO::FETCH_OBJ);
    }

    //! Delete 
    // ^ Delete un utilisateur 
    public function get_delete_utilisateur($id)
    {
        $r = $this->bd->prepare("DELETE FROM utilisateur WHERE id = :id");
        $r->bindParam(':id', $id);
        $r->execute();
        //   header("Location: index.php?controller=utilisateur&action=all_utilisateur");
    }
    
    // ^ Delete un message
    public function get_delete_message($id)
    {
        $r = $this->bd->prepare("DELETE FROM message WHERE id = :id");
        $r->bindParam(':id', $id);
        $r->execute();
        //   header("Location: index.php?controller=utilisateur&action=all_utilisateur");
    }
    
    // ^ Delete un document
    public function get_delete_document($id)
    {
        $r = $this->bd->prepare("DELETE FROM document WHERE id = :id");
        $r->bindParam(':id', $id);
        $r->execute();
        //   header("Location: index.php?controller=utilisateur&action=all_utilisateur");
    }

    // ^ Delete une annonce 
    public function get_delete_annonce($id)
    {
        $r = $this->bd->prepare("DELETE FROM annonce WHERE id = :id");
        $r->bindParam(':id', $id);
        $r->execute();
        //   header("Location: index.php?controller=utilisateur&action=all_utilisateur");
    }


    function valid_input($data)
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
    }
}
