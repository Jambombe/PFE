// Utilisée dans image-card.html.twig
function buyImage(imageId) {

    swal({
        title: "Acheter cette image ?",
        // text : rewardPrice + " pièces",
        buttons: ['Annuler','Oui'],
    })
    .then((confirmed) => {
        if (confirmed) {
            window.fetch('/journal/api/c/buy-image/' + imageId)
                .then(r => r.json())
                .then(r => {

                    if (r.code === 200) {
                        swal(r.message, {
                            icon: "success",
                        }).then(() => {
                            window.location.reload()
                        });
                    } else {
                        swal(r.message, {
                            icon: "error",
                        });
                    }
                });
        }
    });
}

// Utilisée dans image-card.html.twig
function changeProfileImage(imageId) {

    swal({
        title: "Utiliser cette image de profil ?",
        // text : "",
        buttons: {
            confirm: "Oui",
            cancel: "Annuler"
        },
        // icon: "http://127.0.0.1:8000/assets/quest-book/img/gold-coins.png",
        // iconSize: 30
    })
        .then((confirmed) => {
            if (confirmed) {
                window.fetch('/journal/api/c/change-image/'+imageId)
                    .then(r => r.json())
                    .then(r => {
                        if (r.code === 200) {
                            swal(r.message, {
                                icon: "success"
                            }).then(() => {
                                window.location.reload();
                            });
                        } else {
                            swal(r.message, {
                                icon: "error"
                            });
                        }
                    });
            }
        });
}


// utilisée dans quest-card.html.twig
function returnQuest(questId) {

    swal({
        title: "Rendre cette quête ?",
        // text : "",
        buttons: {
            confirm: "Oui",
            cancel: "Annuler"
        },
        // icon: "http://127.0.0.1:8000/assets/quest-book/img/gold-coins.png",
        // iconSize: 30
    })
        .then((confirmed) => {
            if (confirmed) {
                window.fetch('/journal/api/c/return/' + questId)
                    .then(r => r.json())
                    .then(r => {
                        if (r.code === 400) {
                            swal(r.message, {
                                icon: "error"
                            });
                        } else if (r.code === 200) {
                            swal(r.message, {
                                icon: "success"
                            });

                            const questCard = document.getElementById('quest-card-' + questId);

                            questCard.remove();
                        }
                    });
            }
        });
}

// utilisée dans reward-card.html.twig
function buyItem(rewardId, rewardPrice) {
    // var xhttp = new XMLHttpRequest();
    // xhttp.onreadystatechange = function() {
    //     if (this.readyState === 4 && this.status === 200) {
    //         console.log('oui');
    //     }
    // };
    // xhttp.open('GET', '/api/c/return/'+questId, true);
    // xhttp.send();

    swal({
        title: "Acheter cette récompense ?",
        text : rewardPrice + " pièces",
        buttons: {
            confirm: "Oui",
            cancel: "Annuler"
        },
        // icon: "http://127.0.0.1:8000/assets/quest-book/img/gold-coins.png",
        // iconSize: 30
    })
    .then((confirmed) => {
        if (confirmed) {

            window.fetch('/journal/api/c/buy/'+rewardId)
                .then(r => r.json())
                .then(r => {

                    if (r.code === 200) {
                        swal(r.message, {
                            icon: "success",
                        }).then(() => {
                            window.location.reload()
                        });
                    }
                });
        }
    });
}
