<footer class="footer fixed-bottom<?php if($userData['nightmode'] == '1') { echo ' bg-dark'; } ?>">
        <div class="container-fluid">
            <div class="row justify-content-between">
                <div class="col-auto mr-auto">
                    <span class="text-muted ml-3">By <a href="mailto:roeserc@oregonstate.edu">Carter Roeser</a>. <?php echo "Updated: " . date("M d, Y h:i A.", getlastmod()); ?></span>
                </div>
                <div id="editMode" class="col-auto <?php if($userData['nightmode'] == '1') { echo ' dark-edit'; } ?>">
				    <?php if(isset($currLink) && $currLink != '') {echo '<a href="'.$currLink.'" data-toggle="tooltip" data-placement="auto" target="_blank" title="Open in new tab" class="pull-right mr-3"><i class="fa fa-external-link" ></i></a>'; } ?>
                    <a href="javascript:void(0);" id="startEdit" data-toggle="tooltip" data-placement="auto" title="Enter Editing Mode" onclick="startEdit();" class="pull-right mr-3"><i class="fa fa-pencil" ></i></a>
                    <span id="editbuttons" style="display:none">
                        <a href="javascript:void(0);" id="darkmode" data-toggle="tooltip" <?php if($userData['nightmode'] == '1') { echo 'style="display:none"'; } ?> data-placement="auto" title="Turn on Dark Mode" onclick='nightMode()' class="pull-right mr-3"><i class="fa fa-moon-o" ></i></a>
                        <a href="javascript:void(0);" id="lightmode" data-toggle="tooltip" <?php if($userData['nightmode'] != '1') { echo 'style="display:none"'; } ?> data-placement="auto" title="Turn on Light Mode" onclick='lightMode()' class="pull-right mr-3"><i class="fa fa-sun-o" ></i></a>
                        <a href="javascript:void(0);" data-toggle="tooltip" data-placement="auto" title="Reset Layout" onclick='clearLayout();' class="pull-right mr-3"><i class="fa fa-trash" ></i></a>
                        <a href="javascript:void(0);" data-toggle="tooltip" data-placement="auto" title="Save Layout" onclick='endEdit();saveLayout();' class="pull-right mr-3"><i class="fa fa-save" ></i></a>
                    </span>
                </div>
            </div>
        </div>  
    </footer>
    <script src="frontend/node_modules/jquery/dist/jquery.min.js"></script>
    <script src="frontend/node_modules/popper.js/dist/umd/popper.min.js"></script>
    <script src="frontend/node_modules/bootstrap/dist/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-contextmenu/2.7.1/jquery.contextMenu.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-contextmenu/2.7.1/jquery.ui.position.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10.15.5/dist/sweetalert2.all.min.js"></script>
    <script src="frontend/assets/js/index.js?v=2"></script>
    <script>
        var startingOrder = sortable.toArray();
        var hiddenElements = ("<?php echo $userData['hidden']; ?>").split(',');
        var favorites = ("<?php echo $userData['favorites']; ?>").split(',');
        var recents = ("<?php echo $userRecents['recents']; ?>").split(',');
        var userLayout = ("<?php echo $userLayout; ?>").split(',');
        var nightmode = <?php echo $userData['nightmode']; ?>;
        if (userLayout != ' ' && userLayout != '') {
            sortable.sort(userLayout, false);
        }
        sortable.option("disabled", true);
        function updateRecents(id) {
            var order = sortable.toArray();
            if(recents.includes(id)) {
                recents.remove(id);
            }
            if (recents.length >= 5) {
                recents.splice(-1,1);
            }
            recents.unshift(id);
            $.ajax({
                type: "POST",
                url: "frontend/includes/recents.php",
                data: {
                    'user': '<?php echo $_SERVER['PHP_AUTH_USER']; ?>',
                    'recents': recents.toString(),
                }
            });
            $('#recentstable').load(document.URL +  ' #recentstable');
        }
        function addFavorite(id) {
			console.log("TEST1");
            var order = sortable.toArray();
            favorites.unshift(id);
            $.ajax({
                type: "POST",
                url: "frontend/includes/userLayout.php",
                data: {
                    'user': '<?php echo $_SERVER['PHP_AUTH_USER']; ?>',
                    'dark': nightmode,
                    'hidden': hiddenElements.toString(),
                    'order': order.toString(),
                    'favorites': favorites.toString(),
                }
            });
            $('#favoritestable').load(document.URL +  ' #favoritestable');
        }
        function removeFavorite(id) {
			console.log("TEST2");
            var order = sortable.toArray();
            favorites.remove(id);
            $.ajax({
                type: "POST",
                url: "frontend/includes/userLayout.php",
                data: {
                    'user': '<?php echo $_SERVER['PHP_AUTH_USER']; ?>',
                    'dark': nightmode,
                    'hidden': hiddenElements.toString(),
                    'order': order.toString(),
                    'favorites': favorites.toString(),
                }
            });
            $('#favoritestable').load(document.URL +  ' #favoritestable');
        }
        function saveLayout() {
			console.log("TEST3");
            var order = sortable.toArray();
            if (order.toString() == startingOrder.toString()) {
                var newOrder = ' '; 
            }
            else {
                var newOrder = order.toString();
            }
            $.ajax({
                type: "POST",
                url: "frontend/includes/userLayout.php",
                data: {
                    'user': '<?php echo $_SERVER['PHP_AUTH_USER']; ?>',
                    'dark': nightmode,
                    'hidden': hiddenElements.toString(),
                    'order': newOrder,
                    'favorites': favorites.toString(),
                }
            });
        }
        function clearLayout() {
			console.log("TEST4");
            nightmode = 0;
            $.ajax({
                type: "POST",
                url: "frontend/includes/userLayout.php",
                data: {
                    'user': '<?php echo $_SERVER['PHP_AUTH_USER']; ?>',
                    'dark': nightmode,
                    'hidden': ' ',
                    'order': ' ',
                    'favorites': favorites.toString(),
                },
                success: function () {
                    lightMode();
                    setTimeout(() => { location.reload(true); }, 200);
                }
            });
            
        }

        $(function(){
            $.contextMenu({
                selector: '.add-context-menutrue', 
                build: function($trigger, e) {
                    return {
                        callback: function(key, options) {
                            var id = options.$trigger.attr("data-coe-id");
                            if (key == 'delete') {
                                $trigger.hide();
                                hiddenElements.push(id);
                            }
                            else if (key == 'favorite') {
                                $trigger.addClass('favorite');
                                addFavorite(id);
                            }
                            else if (key == 'unfavorite') {
                                $trigger.removeClass('favorite');
                                removeFavorite(id);
                            }
                        },
                        items: {
                            "delete": {name: "Hide", icon: "fa-eye-slash"},
                            "favorite": {name: "Favorite", icon: "fa-star", visible:function(){ return !($trigger.hasClass("favorite")); }},
                            "unfavorite": {name: "Remove Favorite", icon: "fa-star-o", visible:function(){ return $trigger.hasClass("favorite"); }},
                        }
                    };
                }
            });
        });
        $(function(){
            $.contextMenu({
                selector: '.add-context-footrue', 
                build: function($trigger, e) {
                    return {
                        callback: function(key, options) {
                            var id = options.$trigger.children(":first").html();
                            if (key == 'favorite') {
                                $trigger.addClass('foo-favorite');
                                addFavorite(id);
                            }
                            else if (key == 'unfavorite') {
                                $trigger.removeClass('foo-favorite');
                                removeFavorite(id);
                            }
                        },
                        items: {
                            "favorite": {name: "Favorite", icon: "fa-star", visible:function(){ return !($trigger.hasClass("foo-favorite")); }},
                            "unfavorite": {name: "Remove Favorite", icon: "fa-star-o", visible:function(){ return $trigger.hasClass("foo-favorite"); }},
                        }
                    };
                }
            });
        });
        $(function(){
            $.contextMenu({
                selector: '.add-context-menu2true', 
                build: function($trigger, e) {
                    return {
                        callback: function(key, options) {
                            var id = options.$trigger.attr("data-sort");
                            if (key == 'delete') {
                                $trigger.hide();
                                hiddenElements.push(id);
                            }
                        },
                        items: {
                            "delete": {name: "Hide", icon: "fa-eye-slash"},
                        }
                    };
                }
            });
        });
        $(function(){
            $.contextMenu({
                selector: '.add-context-menu3true', 
                build: function($trigger, e) {
                    return {
                        callback: function(key, options) {
                            var id = options.$trigger.attr("data-sort");
                            if (key == 'delete') {
                                $( ".add-context-menu2true" ).each(function( index ) {
                                    $( this ).show();
                                });
                                $( ".add-context-menutrue" ).each(function( index ) {
                                    $( this ).show();
                                });
                                hiddenElements = [];
                            }
                        },
                        items: {
                            "delete": {name: "Unhide All", icon: "fa-eye"},
                        }
                    };
                }
            });
        });
		function checkMenuOverflow() {
			if (window.innerWidth >= 1200 && ($('#id01').width() >= ($('#navx').width() - $('#headertext').width())) && $('#navx').hasClass('navbar-expand-xl')) { 
				$('#navx').toggleClass('navbar-expand-xl');
			}
			else if (window.innerWidth >= 1200 && ($('#id01').width() < ($('#navx').width() - $('#headertext').width())) && !($('#navx').hasClass('navbar-expand-xl'))) {
				$('#navx').toggleClass('navbar-expand-xl');
			}	
		}	
		checkMenuOverflow();		
		window.onresize = checkMenuOverflow;
    </script>
