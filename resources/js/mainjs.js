$(document).ready(function() {
    
    jQuery('.jClever').jClever({
        applyTo: {
            checkbox:true,
            radio:true,
            file:true,
            select:true
        }
    });

    $("a.forgot").click(function(){
    	$("div.auth").fadeOut(function(){
    		$("div.restore").fadeIn();
    	});
    });

    $("div.field-block")

    $("#cancel").click(function(){
    	$("div.restore").fadeOut(function(){
    		$("div.auth").fadeIn();
    	});
    });
});

function userEditToggle(openMod) {
    if (openMod == true) {
        $("div.popupBg").fadeIn(function(){
            var leftPos = ($("div#uEditModal").outerWidth()*(-1));
            $("div#uEditModal").fadeIn();
            $("div.modal").css("margin-left",(leftPos/2)/12+"em");
        });
    } else {
        $("div#uEditModal").fadeOut(function(){
            $("div.popupBg").fadeOut();
        });
    };
};