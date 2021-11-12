Array.prototype.remove = function(value) {
  for (var i = this.length; i--; ) {
      if (this[i] === value) {
          this.splice(i, 1);
      }
  }
}

var stores_li = document.querySelectorAll('.nav-item');

[].slice.call(stores_li).sort(function(a, b) {
    var textA = a.getAttribute('data-sort').toLowerCase()
    var textB = b.getAttribute('data-sort').toLowerCase()
    return (textA < textB) ? -1 : (textA > textB) ? 1 : 0;
}).forEach(function(el) {el.parentNode.appendChild(el)});
    
var el = document.getElementById('id01');
var sortable = Sortable.create(el, {delay: 10000, delayOnTouchOnly: true});

function nightMode() {
   $("body").addClass("bg-dark text-white");
   $(".jumbotron, .footer, #editor").addClass("bg-dark");
   $("#recentstable, #favoritestable").children("table").addClass("table-dark");
   $("#recentstable, #favoritestable").children("table").children("thead").removeClass("thead-light");
   $("#jumbo").addClass("darkfoo");
   $("#editMode").addClass("dark-edit");
   $("#darkmode").hide();
   $("#lightmode").show();
   nightmode = 1;
}
function lightMode() {
   $("body").removeClass("bg-dark text-white");
   $(".jumbotron, .footer, #editor").removeClass("bg-dark");
   $("#recentstable, #favoritestable").children("table").removeClass("table-dark");
   $("#recentstable, #favoritestable").children("table").children("thead").addClass("thead-light");
   $("#jumbo").removeClass("darkfoo");
   $("#editMode").removeClass("dark-edit");
   $("#darkmode").show();
   $("#lightmode").hide();		
   nightmode = 0;
}
$(function () {
    $('[data-toggle="tooltip"]').tooltip()
  });

function startEdit() {
    sortable.option("disabled", false);
    Swal.fire({
        position: 'top-end',
        title: '<h5>Editing Mode Enabled</h5>',
        html: '<div style="text-align:left"><ul>' +
            '<li>Drag and drop menu items to rearrange them.</li>' +
            '<li>Right click on menu items to hide them.</li>' +
            '<li>Right click on the "Tool Directory" or empty space on the navbar to unhide hidden items.</li>' +
            '<li>Use the sun / moon buttons in the bottom right corner to toggle light / dark mode.</li>' +
            '<li>Use the trash button in the bottom right corner to reset your layout to the default.</li>' +
            '<li>Use the save button in the bottom right corner to save and exit editing mode.</li>' +
        '</ul></div>',
        showConfirmButton: false,
        showCloseButton: true,
        allowOutsideClick: false,
        backdrop: false,
      })
    $(".add-context-menu").removeClass("add-context-menu").addClass("add-context-menutrue");
    $(".add-context-menu2").removeClass("add-context-menu2").addClass("add-context-menu2true");
    $(".add-context-menu3").removeClass("add-context-menu3").addClass("add-context-menu3true");
    $(".add-context-foo").removeClass("add-context-foo").addClass("add-context-footrue");
    $("#editbuttons").show();
    $("#startEdit").hide();
}
function endEdit() {
   sortable.option("disabled", true);
    Swal.close()
    $(".add-context-menutrue").removeClass("add-context-menutrue").addClass("add-context-menu");
    $(".add-context-menu2true").removeClass("add-context-menu2true").addClass("add-context-menu2");
    $(".add-context-menu3true").removeClass("add-context-menu3true").addClass("add-context-menu3");
    $(".add-context-footrue").removeClass("add-context-footrue").addClass("add-context-foo");
    $("#editbuttons").hide();
    $("#startEdit").show();
    setTimeout(() => {  Swal.fire({
        position: 'top-end',
        html: '<h5>Editing Mode Disabled</h5>',
        timer: 1500,
        timerProgressBar: true,
        showConfirmButton: false,
        allowOutsideClick: false,
        backdrop: false,
        width: '100%'
      })
    }, 300);

}