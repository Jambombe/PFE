// Utilisée dans image-card.html.twig
function buyImage(imageId) {

    if (confirm("Acheter cette image ?")) {

        window.fetch('/journal/api/c/buy-image/'+imageId)
            .then(r => r.json())
            .then(r => {
                if (r.code === 200) {
                    // alert(r.message);
                    document.location.reload();
                } else {
                    alert(r.message);
                }
            });
    }
}

// Utilisée dans image-card.html.twig
function changeProfileImage(imageId) {

    if (confirm("Activer en tant qu'image de profil?")) {

        window.fetch('/journal/api/c/change-image/'+imageId)
            .then(r => r.json())
            .then(r => {
                if (r.code === 200) {
                    // alert(r.message);
                    document.location.reload();
                } else {
                    alert(r.message);
                }
            });
    }
}


// utilisée dans quest-card.html.twig
function returnQuest(questId) {

    if (confirm("Rendre cette quête ?")) {

        window.fetch('/journal/api/c/return/'+questId)
            .then(r => r.json())
            .then(r => {
                if (r.code === 400) {
                    alert(r.message);
                } else if (r.code === 200){
                    alert(r.message);

                    const questCard = document.getElementById('quest-card-'+questId);

                    questCard.remove();
                }
            });
    }
}

// utilisée dans reward-card.html.twig
function buyItem(rewardId) {
    // var xhttp = new XMLHttpRequest();
    // xhttp.onreadystatechange = function() {
    //     if (this.readyState === 4 && this.status === 200) {
    //         console.log('oui');
    //     }
    // };
    // xhttp.open('GET', '/api/c/return/'+questId, true);
    // xhttp.send();

    if (confirm("Acheter cette récompense ?")) {

        window.fetch('/journal/api/c/buy/'+rewardId)
            .then(r => r.json())
            .then(r => {
                alert(r.message);
                if (r.code === 200) {
                    document.location.reload();
                }
            });
    }
}
