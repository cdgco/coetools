<!doctype html>
<html lang="en">
   <head>
      <?php require_once("frontend/includes/head.html"); require_once("frontend/includes/menu.php"); ?>
      <title>COE Tools - Tool Directory</title>
      <link href="frontend/assets/footable/css/footable.bootstrap.min.css" rel="stylesheet">
      <link href="frontend/node_modules/bootstrap-select/dist/css/bootstrap-select.min.css" rel="stylesheet">
      <style>
         .btn.btn-default.dropdown-toggle {
            display: none;
         }
      </style>
   </head>

   <body <?php if($userData['nightmode'] == '1') { echo 'class="bg-dark text-white"'; } ?> >
      <div style="min-height:calc(100vh - 60px);">
         <!-- Spinny Spinner -->
         <div id="preloader">
            <img src="frontend/assets/img/loader.gif" id="loaderOSU" />
         </div>
         <!-- Dynamic Menubar -->
         <div id="navajax">
         <?php navbar("directory"); ?>
         </div>
         <main role="main" style="display:none;" class="container" id="container" >
            <div class="jumbotron<?php if($userData['nightmode'] == '1') { echo ' bg-dark darkfoo'; } ?>" id="jumbo">
               <h3>Tool Directory</h3>
               <br>
            <!-- Footable (Dynamically Loaded from MySQL / JS) -->
               <table id="showcase-example-1" class="table" data-paging="true" data-filtering="true" data-sorting="true" <?php if($currStaff == 1): ?> data-editing="true" <?php endif; ?> data-state="true"></table>
            <!-- Tool Editor Dialogue -->
               <div class="modal fade" id="editor-modal" tabindex="-1" role="dialog" aria-labelledby="editor-title">
                  <style scoped>
                     /* provides a red astrix to denote required fields - this should be included in common stylesheet */
                     .form-group.required .control-label:after {
                        content: "*";
                        color: red;
                        margin-left: 4px;
                     }
                  </style>
                  <div class="modal-dialog modal-lg" role="document">
                     <form class="modal-content form-horizontal<?php if($userData['nightmode'] == '1') { echo ' bg-dark'; } ?>" id="editor">
                        <div class="modal-header">
                     <h5>Edit Row</h5>
                           <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                        </div>
                        <div class="modal-body">
                           <input type="hidden" id="id" name="id" style="display:none;" />
                           <div class="form-group required">
                              <label for="name" class="col-sm-3 control-label">Name</label>
                              <div class="col-sm-12">
                                 <input type="text" class="form-control" id="name" name="name" required>
                              </div>
                           </div>
                           <div class="form-group required">
                              <label for="description" class="col-sm-3 control-label">Description</label>
                              <div class="col-sm-12">
                                 <textarea type="text" rows="2" cols="50" class="form-control" id="description" name="description" required></textarea>
                              </div>
                           </div>
                           <div class="form-group required">
                              <label for="category" class="col-sm-3 control-label">Category</label>
                              <div class="col-sm-12">
                                 <input type="text" list="categories" class="form-control" id="category" name="category" required>
                                 <datalist id="categories">
                                    <?php foreach ($categories as $category) { echo "<option value='" . $category . "'>"; } ?>
                                 </datalist>
                              </div>
                           </div>
                           <div class="form-group required">
                              <label for="link" class="col-lg-12 control-label">URL</label>
                              <div class="col-lg-12">
                                 <input type="url" class="form-control" id="link" name="link" placeholder="https://link.com/page.php">
                              </div>
                           </div>
                           <div class="form-group">
                              <label for="tab" class="col-sm-6 control-label">Open Link</label>
                              <div class="col-lg-12">
                                 <select class="form-control" id="tab" name="tab">
                                    <option value='1'>New Tab</option>
                                    <option value='0'>Same Tab</option>
                                    <option value='2'>Iframe</option>
                                 </select>
                              </div>
                           </div>
                           <div class="form-group">
                              <label for="staffOnly" class="col-sm-3 control-label">Staff Only</label>
                              <div class="col-lg-12">
                                 <select class="form-control" id="staffOnly" name="staffOnly">
                                    <option value='<span class="badge badge-danger"><i class="fa fa-times" aria-hidden="true"></i></span>'>No</option>
                                    <option value='<span class="badge badge-success"><i class="fa fa-check" aria-hidden="true"></i></span>'>Yes</option>
                                 </select>
                              </div>
                           </div>
                           <div class="form-group">
                              <label for="display" class="col-sm-3 control-label">Header Display</label>
                              <div class="col-lg-12">
                                 <select class="form-control" id="display" name="display">
                                    <option value='0'>Show in list</option>
                                    <option value='1'>Show directly on header</option>
                                    <option value='2'>Hide from header</option>
                                 </select>
                              </div>
                           </div>
                        </div>
                        <div class="modal-footer">
                           <!-- onclick handles ajax dynamic reloading of header content after editing using in-page modal --> 
                           <button type="submit" onclick='$("#navajax").load("directory.php #navx");' class="btn btn-primary">Save changes</button> 
                           <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        </div>
                     </form>
                  </div>
               </div>
            </div>
         </main>
      </div>
      <?php require_once("frontend/includes/dynamicPage.php"); ?>
      <script src="frontend/assets/footable/js/footable.js"></script>
      <script src="frontend/node_modules/bootstrap-select/dist/js/bootstrap-select.min.js"></script>
      <script>
      /* load footable and configure editor */
         jQuery(function($) {
         <?php if($currStaff == 1): ?>
            var $modal = $("#editor-modal"),
               $editor = $("#editor"),
               $editorTitle = $("#editor-title");
         <?php endif; ?>
               var ft = FooTable.init("#showcase-example-1", {
                  on: {
                     "postdraw.ft.table": function() { checkEdit(); },
                     "ready.ft.table": function() { 
                        $('#preloader').fadeOut();
                        $('#container').fadeIn(); 
                        $(".footable-filtering-search button").first().css( "border-radius", "0 .25rem .25rem 0" );
                        $('.footable-filtering select').selectpicker({liveSearch: true, style: 'btn-primary', liveSearchPlaceholder: 'Search . . .'});
                        
                     },
                  },
                  columns: [{
                        name: "id",
                        type: "number",
                        visible: false,
                     },
                     {
                        name: "name",
                        title: "Name",
                  sorted: true,
                        direction: "ASC"
                     },
                     {
                        name: "description",
                        title: "Description",
                        "breakpoints": "all"
                     },
                     {
                        name: "category",
                        title: "Category",
                        "breakpoints": "xs"
                     },
                     {
                        name: "link",
                        title: "Link",
                        "breakpoints": "all"
                     },
               <?php if($currStaff == 1): ?>
                     {
                        name: "staffOnly",
                        title: "Staff Only"
                     },
               <?php endif; ?>
                     {
                        name: "action",
                        title: "Action"
                     },
                     {
                        name: "display",
                        type: "number",
                        visible: false
                     },
                     {
                        name: "tab",
                        type: "number",
                        visible: false
                     },
                  ],
                  rows: [
                     <?php

                     
                     if ($coe_tools[0] != '') {
                        $x1 = 0;
                        do {
                           $toolfavorite = '';
                           if (array_search($coe_tools[$x1]['id'], $favorites) !== false) {
                              $toolfavorite = ' foo-favorite';
                           }
                     if (!($currStaff == 0 && strpos(addslashes($coe_tools[$x1]['staffOnly']), 'check'))) {
                        echo '{ 
                           "options": {
                              "classes": "add-context-foo'.$toolfavorite.'"
                           },
                           "value": {
                              
                              id: ' . $coe_tools[$x1]['id'] . ',
                              name: "' . addslashes($coe_tools[$x1]['name']) . '",
                              description: "' . addslashes($coe_tools[$x1]['description']) . '",
                              category: "' . addslashes($coe_tools[$x1]['category']) . '",
                              link: "<a href=\"' . addslashes($coe_tools[$x1]['link']) . '\">' . addslashes($coe_tools[$x1]['link']) . '</a>",
                              staffOnly: "' . addslashes($coe_tools[$x1]['staffOnly']) . '",
                              action: "<a onclick=\"updateRecents(\''.$coe_tools[$x1]['id'].'\')\" href=\"';
                              if ($coe_tools[$x1]['tab'] == 2) {
                                 echo "frame.php?coeFrameID=".$coe_tools[$x1]['id'];
                              }
                              else {
                                 echo addslashes($coe_tools[$x1]['link']);
                              }
                              echo '\" target=\"_blank\"><button type=\"button\" class=\"btn btn-outline-primary\"><i class=\"fa fa-external-link\" aria-hidden=\"true\"></i></button></a>",
                              display: ' . $coe_tools[$x1]['display'] . ',
                              tab: ' . $coe_tools[$x1]['tab'] . ',
                           }
                        },';
                     }
                           $x1++;
                        } while (isset($coe_tools[$x1]));
                     }
                     ?>
                  ],
                  components: {
                     filtering: FooTable.MyFiltering
                  },
               <?php if($currStaff == 1): ?>
                  editing: {
                     addRow: function() {
                        $modal.removeData("row");
                        $editor[0].reset();
                        $modal.modal("show");
                     },
                     editRow: function(row) {
                        var values = row.val();
                        $editor.find("#id").val(values.id);
                        $editor.find("#name").val(values.name);
                        $editor.find("#description").val(values.description);
                        $editor.find("#category").val(values.category);
                        if (values.link.includes('href=')) {
                           $editor.find("#link").val(values.link.match(/href="([^"]*)/)[1]);
                        }
                        else {
                           $editor.find("#link").val(values.link);
                        }
                        $editor.find("#staffOnly").val(values.staffOnly);
                        $editor.find("#display").val(values.display);
                        $editor.find("#tab").val(values.tab);
                        $modal.data("row", row);
                        $modal.modal("show");
                     },
                     deleteRow: function(row) {
                        var values = row.val();
                        if (confirm("Are you sure you want to delete the row?")) {
                           $.ajax({
                              type: "POST",
                              url: "frontend/includes/delete.php",
                              data: {
                                 'id': values.id
                              },
                              success: function(data) {
                                 if (data == '0') {
                                    row.delete();
                                 }
                              }
                           })
                        }
                     }
                  }
               <?php endif; ?>
               });
         <?php if($currStaff == 1): ?>
            $editor.on("submit", function(e) {
               if (this.checkValidity && !this.checkValidity()) return;
               e.preventDefault();
               var row = $modal.data("row"),
                  values = {
                     id: $editor.find("#id").val(),
                     name: $editor.find("#name").val(),
                     description: $editor.find("#description").val(),
                     link: $editor.find("#link").val(),
                     category: $editor.find("#category").val(),
                     staffOnly: $editor.find("#staffOnly option:selected").val(),
                     display: $editor.find("#display option:selected").val(),
                     tab: $editor.find("#tab option:selected").val()
                  };

               if (row instanceof FooTable.Row) {
                  $.ajax({
                     type: "POST",
                     url: "frontend/includes/change.php",
                     data: {
                        'id': values.id,
                        'name': values.name,
                        'description': values.description,
                        'category': values.category,
                        'link': values.link,
                        'staffOnly': values.staffOnly,
                        'display': values.display,
                        'tab': values.tab
                     },
                     success: function(data) {
                        if (data == '0') {
                           if (values.tab == 2) {
                              values.action = '<a onclick=\"updateRecents(\'' + values.id +'\')\" href=\"frame.php?coeFrameID=' + values.id + '\" target=\"_blank\"><button type=\"button\" class=\"btn btn-outline-primary\"><i class=\"fa fa-external-link\" aria-hidden=\"true\"></i></button></a>';
                           }
                           else {
                              values.action = '<a onclick=\"updateRecents(\'' + values.id +'\')\" href=\"' + values.link + '\" target=\"_blank\"><button type=\"button\" class=\"btn btn-outline-primary\"><i class=\"fa fa-external-link\" aria-hidden=\"true\"></i></button></a>'; 
                           }
                           row.val(values);
                        }
                     }
                  })
               } else {
                  var curdate = Date.now();
                  $.ajax({
                     type: "POST",
                     url: "frontend/includes/create.php",
                     data: {
                        'id': curdate,
                        'name': values.name,
                        'description': values.description,
                        'category': values.category,
                        'link': values.link,
                        'staffOnly': values.staffOnly,
                        'display': values.display,
                        'tab': values.tab
                     },
                     success: function(data) {
                        if (data == '0') {
                           values.link = "<a href=\"" + values.link + "\">" + values.link + "</a>";
                           values.id = curdate;
                           ft.rows.add(values);
                        } else {
                           console.log(data);
                        }

                     }
                  })
               }
               $modal.modal("hide");
            });
         <?php endif; ?>
         });

      /* Category sort system */
         FooTable.MyFiltering = FooTable.Filtering.extend({
            construct: function(instance) {
               this._super(instance);
               this.statuses = [<?php foreach ($categories as $category) { echo "'" . $category . "',"; } ?>];
               this.def = 'Any Category';
               this.$status = null;
            },
            $create: function() {
               this._super();
               var self = this,
                  $form_grp = $('<div/>', {
                     'class': 'form-group'
                  })
                  .append($('<label/>', {
                     'class': 'sr-only',
                     text: 'Category'
                  }))
                  .prependTo(self.$form);

               self.$status = $('<select/>', {
                     'class': 'form-control'
                  })
                  .on('change', {
                     self: self
                  }, self._onStatusDropdownChanged)
                  .append($('<option/>', {
                     text: self.def
                  }))
                  .appendTo($form_grp);

               $.each(self.statuses, function(i, status) {
                  self.$status.append($('<option/>').text(status));
               });
            },
            _onStatusDropdownChanged: function(e) {
               var self = e.data.self,
                  selected = $(this).val();
               if (selected !== self.def) {
                  self.addFilter('status', selected, ['category']);
               } else {
                  self.removeFilter('status');
               }
               self.filter();
            },
            draw: function() {
               this._super();
               var status = this.find('status');
               if (status instanceof FooTable.Filter) {
                  this.$status.val(status.query.val());
               } else {
                  this.$status.val(this.def);
               }
            }
         });
         FooTable.components.register('filtering', FooTable.MyFiltering);
         function checkEdit() {
            if($('#editbuttons').css('display') != 'none') {
               console.log("EDIT");
               setTimeout(function(){ $(".add-context-foo").removeClass("add-context-foo").addClass("add-context-footrue"); }, 300);
            }
            else {
               console.log("NON EDIT");
               setTimeout(function(){ $(".add-context-footrue").removeClass("add-context-footrue").addClass("add-context-foo"); }, 300);
            }
         }
      </script>
   </body>
</html>