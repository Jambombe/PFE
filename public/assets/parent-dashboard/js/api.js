function validQuest(questId) {

    swal({
        title: "Voulez-vous valider la quête ?",
        text: "L'enfant recevra les récompenses (points d'expérience et / ou pièces d'or)",
        icon: "warning",
        buttons: ['Annuler','Oui'],
        // dangerMode: true,
    })
        .then((willDelete) => {
            if (willDelete) {
                window.fetch('/api/p/valid/'+questId)
                    .then(r => r.json())
                    .then(r => {
                        if (r.code === 200) {
                            swal(r.message, {
                                icon: "success",
                            });

                            const questCard = document.getElementById('quest-post-'+questId);

                            questCard.remove();
                        } else {
                            swal("Une erreur est survenue", r.message, {
                                icon: "error",
                            });
                        }
                    });
            }
        });
}

function refuseQuest(questId) {

    swal({
        title: "Voulez-vous refuser la quête ?",
        text: "L'enfant ne recevra pas les récompenses",
        icon: "warning",
        buttons: {
            confirm: "Oui",
            restart: "Relancer la quête",
            cancel: "Annuler"
        },
        dangerMode: true,
    })
        .then((value) => {
            switch (value) {
                case "confirm" :
                    window.fetch('/api/p/refuse/'+questId)
                        .then(r => r.json())
                        .then(r => {
                            if (r.code === 200) {
                                swal(r.message, {
                                    icon: "success",
                                });

                                const questCard = document.getElementById('quest-post-'+questId);

                                questCard.remove();
                            } else {
                                swal("Une erreur est survenue", r.message, {
                                    icon: "error",
                                });
                            }
                        });
                    break;
                case "restart" :
                    window.fetch('/api/p/restart/'+questId)
                        .then(r => r.json())
                        .then(r => {
                            if (r.code === 200) {
                                swal(r.message, {
                                    icon: "success",
                                }).then(() => {
                                    window.location.reload();
                                });
                                // const questCard = document.getElementById('quest-post-'+questId);
                                //
                                // questCard.remove();
                            } else {
                                swal("Une erreur est survenue", r.message, {
                                    icon: "error",
                                });
                            }
                        });
                    break;
            }
        });
}

function editReward(rewardId) {

    window.fetch('/api/p/reward/'+rewardId)
        .then(r => r.json())
        .then(r => {
            if (r.code === 200) {
                // console.log(r);
                doModal('idMyModal', r.message);
            } else {
                // console.log(r);
            }
        });
}

function deleteReward(rewardId) {

    swal({
        title: "Voulez-vous vraiment supprimer cette récompense ?",
        text: "Vos enfant ne pourront plus l'acheter",
        icon: "warning",
        buttons: ['Annuler','Oui'],
        dangerMode: true,
    })
        .then((willDelete) => {
            if (willDelete) {
                window.fetch('/api/p/delete-reward/'+rewardId)
                    .then(r => r.json())
                    .then(r => {
                        if (r.code === 200) {
                            swal(r.message, {
                                icon: "success",
                            });
                            const rewardCard = document.getElementById('custom-reward-'+rewardId);

                            rewardCard.remove();
                        } else {
                            swal("Une erreur est survenue", r.message, {
                                icon: "error",
                            });
                        }
                    });


            }

        });
}



function deleteNotif(notifId) {
    // if (confirm("Rendre cette quête ?")) {

        window.fetch('/api/p/delete-notif/'+notifId)
            .then(r => r.json())
            .then(r => {
                if (r.code === 200) {
                } else {
                    alert(r.message);
                }
            });
    // }
}

function doModal(placementId, reward)
{

    var html = '<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">\n' +
        '  <div class="modal-dialog" role="document">\n' +
        '    <div class="modal-content">\n' +
        '      <div class="modal-header">\n' +
        '        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>\n' +
        '        <h4 class="modal-title" id="myModalLabel">Modifier la récompense</h4>\n' +
        '      </div>\n' +
        '      <div class="modal-body">\n' +


        '<form>' +
        '<div class="form-group"> <div> <label for="newName" class="required">Titre</label><input value="'+reward.name+'" id="newName" class="form-control" type="text"/></div></div>' +
        '<div class="form-group"> <div> <label for="newDescription" class="required">Description</label><textarea id="newDescription" class="form-control">'+reward.description+'</textarea></div></div>' +
        '<div class="form-group"> <div> <label for="newPrice" class="required">Pièces d\'or</label><input value="'+reward.price+'" id="newPrice" class="form-control" type="number"/></div></div>' +
        '<div class="form-group"> <div> <label for="newNbUnitAvailable" class="required">Nombre disponible</label><input value="'+  (reward.newNbUnitAvailable ===-1 ? '': reward.newNbUnitAvailable) +'" id="newNbUnitAvailable" class="form-control" type="number"/></div></div>' +
        '<div class="form-group"> <div> <label for="newImage" class="required">Image</label><input value="'+reward.image+'" id="newImage" class="form-control" type="text"/></div></div>' +
        '</form>' +

        '      </div>\n' +
        '      <div class="modal-footer">\n' +
        '        <button type="button" class="btn btn-default" data-dismiss="modal">Annuler</button>\n' +
        '        <button type="button" class="btn btn-primary" onclick="saveRewardModifications('+reward.id+')">Sauvegarder</button>\n' +
        '      </div>\n' +
        '    </div>\n' +
        '  </div>\n' +
        '</div>';


    $("#"+placementId).html(html);
    $("#myModal").modal();
}

function saveRewardModifications(rewardId) {

    const newName = document.getElementById('newName').value;
    const newDescription = document.getElementById('newDescription').value;
    const newPrice = document.getElementById('newPrice').value;
    const newNbUnitAvailable = document.getElementById('newNbUnitAvailable').value;
    const newImage = document.getElementById('newImage').value;

    const modifReward = {
        'id': rewardId,
        'name': newName,
        'description': newDescription,
        'price': newPrice,
        'newNbUnitAvailable': newNbUnitAvailable,
        'image': newImage
    };


    window.fetch('/api/p/modif-reward/'+ rewardId, { body: JSON.stringify(modifReward), method: 'PUT' })
        .then(r => r.json())
        .then(r => {

            if (r.code === 200) {
                swal(r.message, {
                    icon: "success",
                }).then(() => {
                    window.location.reload();
                });

            } else {
                swal(r.message, {
                    icon: "error",
                });
            }
        });

}

function deleteChildAccount(childId) {


    swal({
        title: "Voulez-vous vraiment supprimer ce compte Enfant ?",
        text: "Cette action est définitive et irréversible",
        icon: "warning",
        buttons: ['Annuler','Supprimer'],
        dangerMode: true,
    })
        .then((willDelete) => {
            if (willDelete) {
                window.fetch('/api/p/delete-child/'+childId)
                    .then(r => r.json())
                    .then(r => {
                        if (r.code === 200) {
                            swal(r.message, {
                                icon: "success",
                            }).then(() => {
                                document.location.href="/dashboard/mes-enfants";
                            });

                        } else {
                            swal("Une erreur est survenue", r.message, {
                                icon: "error",
                            });
                        }
                    });


            }

        });
}