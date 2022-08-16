<?php
// [MODIF HORS COURS]
// On utilise le composant Validator de Symfony pour faire la validation des formulaires
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class CategorieControleur extends Controleur
{
    // [MODIF HORS COURS]
    // Les messages d'erreurs de ce contrôleur : les codes préfixés par _ sont de mon 
    // invention, les autres sont ceux retournés par MySQL.
    protected $messagesUI = [
        '1451'  =>  "Ne peut pas supprimer/changer enregistrement parent (contrainte d'intégrité référencielle).",
        '1062'  =>  "Doublons.",
        '_0001'  =>  "Nom/Type de catégorie vide.",
        '_0010'  => "Catégorie ajoutée",
        '_0020'  => "Catégorie modifiée",
        '_0030'  => "Catégorie supprimée"
    ];

    public function __construct($modele, $module, $action, $params)
    {
        // S'il n'y a pas d'utilisateur connecté, on redirige vers le formulaire de 
        // connexion (avec un message d'erreur)
        if(!isset($_SESSION['utilisateur'])) {
            Utilitaire::nouvelleRoute('utilisateur/index/msg=_1000');
        }

        parent::__construct($modele, $module, $action, $params);
    }

    /**
     * Méthode invoquée par défaut si aucune action n'est indiquée
     */
    public function index()
    {
        $this->gabarit->affecterActionParDefaut('tout');
        $this->tout();
    }

    /**
     * Chercher toutes les catégories
     */
    public function tout()
    {
        $this->gabarit->affecter('categories', $this->modele->tout());
    }

    /**
     * Ajouter une catégorie
     */
    public function ajouter() {
        // [MODIF HORS COURS]
        // Valider la saisie de l'utilisateur
        $catNomErreurs = $this->validateur->validate($_POST['cat_nom'], [new NotBlank()]);
        $catTypeErreurs = $this->validateur->validate($_POST['cat_type'], [new NotBlank()]);

        // S'il y a erreur on réaffiche la page avec le message d'erreur adéquat
        if(count($catNomErreurs)>0 || count($catTypeErreurs)>0) {
            $this->gabarit->affecter('erreur', $this->messagesUI['_0001']);
            $this->gabarit->affecterActionParDefaut('tout');
            $this->tout();
        }
        else {
            // Ajouter la nouvelle catégorie (dont les valeurs sont reçues par POST) dans la BD
            $res = $this->modele->ajouter($_POST);
            // Rediriger vers l'affichage des catégories
            Utilitaire::nouvelleRoute('categorie/tout'.(str_starts_with($res, '/msg=')?$res:'/msg=_0010'));
        }

        
    }

    /**
     * Retirer une catégorie
     */
    public function retirer() {
        $res = $this->modele->retirer($_POST['cat_id']);
        Utilitaire::nouvelleRoute('categorie/tout'.(str_starts_with($res, '/msg=')?$res:'/msg=_0030'));
    }

    /**
     * Changer une catégorie
     */
    public function changer() {
        // [MODIF HORS COURS]
        // Valider la saisie de l'utilisateur
        $catNomErreurs = $this->validateur->validate($_POST['cat_nom'], [new NotBlank()]);
        $catTypeErreurs = $this->validateur->validate($_POST['cat_type'], [new NotBlank()]);

        // S'il y a erreur on réaffiche la page avec le message d'erreur adéquat
        if(count($catNomErreurs)>0 || count($catTypeErreurs)>0) {
            $this->gabarit->affecter('erreur', $this->messagesUI['_0001']);
            $this->gabarit->affecterActionParDefaut('tout');
            $this->tout();
        }
        else {
            $res = $this->modele->changer($_POST);
            Utilitaire::nouvelleRoute('categorie/tout'.(str_starts_with($res, '/msg=')?$res:'/msg=_0020'));
        }
    }
}
