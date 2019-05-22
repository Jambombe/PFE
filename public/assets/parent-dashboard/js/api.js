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
        buttons: ['Annuler','Oui'],
        dangerMode: true,
    })
        .then((willDelete) => {
            if (willDelete) {
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
            }
        });
}

function editReward(rewardId) {
    console.log(rewardId);
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