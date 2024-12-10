$(function() {
    console.log("dom is ready");

    $(".formaction").submit(function(e) {
        e.preventDefault();
    });

    $("#search-box").on("input", function() {
        let searchstring = this.value;
        var gettr = $("tr.data-row");
        for (var i = 0; i < gettr.length; i++) {
            var datastring = "";
            $(gettr[i]).find("td").each(function() {
                datastring += $(this).text() + " ";
            });

            var isPresent = (datastring.toLowerCase()).includes(searchstring.toLowerCase());
            if (isPresent) {
                $(gettr[i]).removeClass('display-class');
            } else {
                $(gettr[i]).addClass('display-class');
            }
        }
    });

    $("tr.data-row").on("click", function() {
        let getid = $(this).find(".column1").text();
        let selectedUser = {
            id: getid,
            nom: $(this).find(".column2").text(),
            prenom: $(this).find(".column3").text(),
            email: $(this).find(".column4").text(),
            role: $(this).find(".column5").text(),
            description: $(this).find(".column6").text(),
            adresse: $(this).find(".column7").text(),
            ville: $(this).find(".column8").text(),
            pays: $(this).find(".column9").text(),
            code_postal: $(this).find(".column10").text()
        };

        $(".data-row").removeClass('active');
        $(this).addClass('active');
        changerightside(selectedUser);
    });

    function changerightside(user) {
        $(".info-name").html(`<b>User Selected: </b> ${user.nom} ${user.prenom}`);
        $("textarea").text(`${user.description}`);
        $(".adress").html(`<b>Address:</b> ${user.adresse}`);
        $(".city").html(`<b>City:</b> ${user.ville}`);
        $(".state").html(`<b>State:</b> ${user.pays}`);
        $(".zipcode").html(`<b>Zip:</b> ${user.code_postal}`);
        $("#delete-user").data('userid', user.id); // Store the user ID in the button
        $("#generate-pdf").data('userid', user.id); // Store the user ID in the button
    }

    $("#delete-user").on("click", function() {
        let userid = $(this).data('userid');
        if (confirm("Are you sure you want to delete this user?")) {
            $.post("delete_user.php", { id: userid }, function(response) {
                if (response.success) {
                    location.reload(); // Reload the page to reflect changes
                } else {
                    alert("Failed to delete user.");
                }
            }, "json");
        }
    });

    $("#generate-pdf").on("click", function() {
        let userid = $(this).data('userid');
        window.location.href = 'generate_pdf.php?id=' + userid;
    });
});
