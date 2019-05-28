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

        window.fetch('/api/c/return/'+questId)
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