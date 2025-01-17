  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="index3.html" class="brand-link">
      <img src="<?php echo base_url(); ?>/assets/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
      <span class="brand-text font-weight-bold">DigiFi</span>
    </a>
<?php
    $user=json_decode(json_encode($user));
?>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="<?php echo base_url(); ?>/assets/img/avatar.png" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
          <a href="#" class="d-block" id="usr-logged-in"><?php echo "{$user->LastName} {$user->FirstName}";?>
        </a>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
          <?php 
            
            $menuItems = [
              // Your array of objects here...
          ];
          
          // Initialize an empty associative array to group items by 'menu_category'
          $menuGroups = [];
          $category_icon = '';
          
          foreach ($user->menus as $menuItem) {
              $menuCategory = $menuItem->MenuGroup;
          
              // If the category doesn't exist as a key in the associative array, create it
              if (!isset($menuGroups[$menuCategory])) {
                  $menuGroups[$menuCategory] = [];
              }
          
              // Add the current menu item to the corresponding category
              $menuGroups[$menuCategory][] = [
                  'menu_id' => $menuItem->MenuID,
                  'menu_name' => $menuItem->MenuName,
                  'url' => $menuItem->MenuUrl,
                  'type' => $menuItem->MenuType,
                  'icon' => $menuItem->MenuIcon,
                  'order' => $menuItem->MenuOrder,
                  'cat_icon' => $menuItem->MenuGroupIcon
              ];
          }


          // $menuGroups now contains the menu items grouped by 'menu_category'
          foreach ($menuGroups as $key => $value) { ?>
          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon fas <?php echo $value[0]['cat_icon']; ?>"></i>
              <p>
                <?php echo $key;?>
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <?php if(is_array($value) && !empty($value)){ 
              ?>
              <ul class="nav nav-treeview"><?php
                foreach ($value as $link) { 
                  if($link['type']=='Link'){
                  ?>
                  <li class="nav-item ml-3">
                    <a href="<?php echo base_url().$link['url'];?>" class="nav-link">
                      <i class="<?php  echo $link['icon'];?>"></i>
                      <p>
                        <?php  echo $link['menu_name'];?>
                      </p>
                    </a>
                  </li>
               <?php  
                  }             
              } ?>
              </ul>

            <?php } ?>
          </li>
         <?php }
            ?>
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>
