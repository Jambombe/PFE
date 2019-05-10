function validQuest(questId) {
    if (confirm("Rendre cette quête ?")) {

        window.fetch('/api/p/valid/'+questId)
            .then(r => r.json())
            .then(r => {
                if (r.code === 400) {
                    alert(r.message);
                } else if (r.code === 200){
                    alert(r.message);

                    const questCard = document.getElementById('quest-post-'+questId);

                    questCard.remove();
                }
            });
    }
}

function refuseQuest(questId) {
    if (confirm("Rendre cette quête ?")) {

        window.fetch('/api/p/refuse/'+questId)
            .then(r => r.json())
            .then(r => {
                if (r.code === 400) {
                    alert(r.message);
                } else if (r.code === 200){
                    alert(r.message);

                    const questCard = document.getElementById('quest-post-'+questId);

                    questCard.remove();
                }
            });
    }
}



function deleteNotif(notifId) {
    // if (confirm("Rendre cette quête ?")) {

        window.fetch('/api/p/delete-notif/'+notifId)
            .then(r => r.json())
            .then(r => {
                if (r.code === 400) {
                    alert(r.message);
                } else if (r.code === 200){
                    // alert(r.message);

                    // const questCard = document.getElementById('quest-post-'+questId);

                    // questCard.remove();
                }
            });
    // }
}