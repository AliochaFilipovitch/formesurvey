<?php

namespace App\Entity;

class Utilities
{
    
    /**
     * Public fonction qui ne marche pas...
     */
    public function isPostedByUser(User $user): bool
    {
        foreach ($this->$answers as $answer) {
            if($answer->getAuthor() === $user) {
                return true;

            }
        }

        return false;
    }

    /**
     * Public fonction remplaceFunction
     */
    public function remplaceFunction($sentence)
    {

        $aremplacer = array(",",".",";",":","!","?","(",")","[","]","{","}","\"","'"," ");
        $enremplacement = " ";
        $sansponctuation = trim(str_replace($aremplacer, $enremplacement, $sentence));
        $separateur = "#[ ]+#";
        $mots = preg_split($separateur, $sansponctuation);
        $phrase = "";
        foreach ($mots as $mot) {
            $phrase .= "$mot+";
        }

        return $phrase;
    }
}
