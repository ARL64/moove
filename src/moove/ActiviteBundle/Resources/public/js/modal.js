/* TEST EN COUR */

function modalRefusActivite($activite, $utilisateur)
{
    document.write('class="modal fade" id="modalRefus" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">');
    document.write('<div class="modal-dialog" role="document">');
    document.write('<div class="modal-content">');
    document.write('<button type="button" class="close" data-dismiss="modal" aria-label="Fermer"><span aria-hidden="true">&times;</span></button>');
    document.write('<h4 class="modal-title" id="myModalLabel">Confirmation</h4>');
    document.write('</div>');
    document.write('<div class="modal-body">');
    document.write('<p>');
    document.write('Êtes-vous sur de vouloir refuser la participation de');
    document.write('<a href="{{ path(\'moove_utilisateur_profileUtilisateur\', {idUtilisateur: utilisateur.id}) }}" >');
    document.write('{{ utilisateur.prenom ~ " " ~ utilisateur.nom }}');
    document.write('</a>');
    document.write('à votre activité ?');
    document.write('<a href="{{ path(\'moove_activite_detailsActivite\', {idActivite: activite.id}) }}" >');
    document.write('{{ activite.sportPratique.nom | lower }} du {{ activite.dateHeureRDV | date("d/m/y") }}');
    document.write('</a>');
    document.write('</p>');
    document.write('</div>');
    document.write('<div class="modal-footer">');
    document.write('<button type="button" class="btn btn-default" data-dismiss="modal">Annuler</button>');
    document.write('<a href="{{ path(\'moove_activite_refuser_demande_participation\', {\'idActivite\': activite.id, \'idUtilisateur\': utilisateur.id}) }}">');
    document.write('<button type="button" class="btn btn-danger">Oui, refuser</button>');
    document.write('</a>');
    document.write('</div>');
    document.write('</div>');
    document.write('</div>');
    document.write('</div>');
    
    
    
}
