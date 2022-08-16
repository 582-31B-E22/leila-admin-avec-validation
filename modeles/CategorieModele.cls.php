<?php
class CategorieModele extends AccesBd
{
    /**
     * Cherche tout les enregistrements de la table categorie
     */
    public function tout()
    {
        return $this->lireTout('SELECT * FROM categorie ORDER BY cat_id ASC');
    }

    public function ajouter($categorie)
    {
        extract($categorie);
        return $this->creer(
            "INSERT INTO categorie VALUES (0, :cat_nom, :cat_type)"
            , ["cat_nom" => $cat_nom, "cat_type" => $cat_type]);
    }

    public function retirer($catId)
    {
        return $this->supprimer("DELETE FROM categorie WHERE cat_id=:cat_id"
            , ['cat_id' => $catId]);
    }

    public function changer($categorie)
    {
        extract($categorie);
        return $this->modifier("UPDATE categorie 
                            SET cat_nom=:cat_nom, cat_type=:cat_type
                        WHERE cat_id=:cat_id"
            , ['cat_id' => $cat_id, 'cat_nom' => $cat_nom, 'cat_type'=> $cat_type]);
    }
}
