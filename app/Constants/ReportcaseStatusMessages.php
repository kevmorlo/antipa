<?php

namespace App\Constants;

class ReportcaseStatusMessages
{
    const DISPLAY_ERROR = 'Une erreur est survenue lors de l\'affichage des cas reportés. ';
    const CREATE_ERROR = 'Une erreur est survenue lors de la création du cas reporté. ';
    const PERMISSION_ERROR = 'L\'utilisateur n\'a pas la permission de créer un cas reporté.';
    const UPDATE_ERROR = 'Une erreur est survenue lors de la mise à jour du cas reporté. ';
    const DELETE_ERROR = 'Une erreur est survenue lors de la suppression du cas reporté. ';
    const CREATE_SUCCESS = 'Cas reporté créé avec succès. ';
    const UPDATE_SUCCESS = 'Cas reporté mis à jour avec succès. ';
    const DELETE_SUCCESS = 'Cas reporté supprimé avec succès. ';
}